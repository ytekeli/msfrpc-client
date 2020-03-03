<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient;

use Exception;
use GuzzleHttp\Client as HttpClient;
use InvalidArgumentException;
use Pimple\Container;
use Ytekeli\MsfRpcClient\Contracts\Handler;
use Ytekeli\MsfRpcClient\Exceptions\MsfRpcAuthException;
use Ytekeli\MsfRpcClient\Exceptions\MsfRpcException;
use Ytekeli\MsfRpcClient\Handler\AuthHandler;
use Ytekeli\MsfRpcClient\Handler\CoreHandler;
use Ytekeli\MsfRpcClient\Handler\Exception as ExceptionHandler;
use Ytekeli\MsfRpcClient\Support\MsfRpcMethod;

/**
 * Class Client.
 *
 * @property AuthHandler    $auth
 * @property CoreHandler    $core
 * @property ExceptionHandler $exception
 */
class Client extends Container
{
    /**
     * @var mixed
     */
    protected $host;
    /**
     * @var mixed
     */
    protected $port;
    /**
     * @var mixed
     */
    protected $uri;
    /**
     * @var mixed
     */
    protected $ssl;
    /**
     * @var mixed|null
     */
    public $token = null;

    /**
     * @var mixed
     */
    protected $guzzleOptions;

    /**
     * @var bool
     */
    protected $authenticate = true;

    /**
     * Client constructor.
     *
     * @param array $options Required configuration options
     *
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        parent::__construct();

        $options = array_merge([
            'host'          => '127.0.0.1',
            'port'          => 55553,
            'uri'           => '/api/',
            'username'      => 'msf',
            'password'      => '',
            'ssl'           => true,
            'token'         => null,
            'guzzle_options'=> [],
            'login'         => true,
            'authenticate'  => true,
            'no_handlers'   => false,
        ], $options);

        $this->host = $options['host'];
        $this->port = $options['port'];
        $this->uri = $options['uri'];
        $this->ssl = $options['ssl'];
        $this->token = $options['token'] ?? null;
        $this->guzzleOptions = $options['guzzle_options'];
        $this->authenticate = $options['authenticate'];

        $this->addHandler(new AuthHandler());
        $this->addHandler(new ExceptionHandler());

        if ($this->authenticate !== false || !is_null($this->token)) {
            if (!is_null($this->token)) {
                // TODO login with token method will be added.
            } else {
                $this->auth->login($options['username'], $options['password']);
            }
        }

        if ($options['no_handlers'] !== true) {
            $this->addHandler(new CoreHandler());
        }
    }

    /**
     * @param string $method
     * @param array  $params
     *
     * @throws MsfRpcAuthException
     * @throws MsfRpcException
     * @throws Exception
     *
     * @return array
     */
    public function call($method = '', array $params = [])
    {
        if ($method != MsfRpcMethod::AUTH_LOGIN) {
            if (!$this->isAuthenticated()) {
                throw new MsfRpcAuthException(MsfRpcAuthException::UNAUTHENTICATED);
            }
        }

        // prepend the token when method is not login
        if ($method != MsfRpcMethod::AUTH_LOGIN) {
            array_unshift($params, $this->token);
        }

        // prepend the method
        array_unshift($params, $method);

        // encode params with msgpack
        $payload = $this->encode($params);

        $client = new HttpClient();

        $guzzleOptions = array_merge([
            'headers' => [
                'Content-Type' => 'binary/message-pack',
            ],
            'body'  => $payload,
            'verify'=> false,
        ], $this->guzzleOptions);

        $result = $client->post('http'.($this->ssl ? 's' : '').'://'.
            $this->host.':'.
            $this->port.
            $this->uri, $guzzleOptions);

        $unpack = $this->decode($result->getBody());

        if (isset($unpack['error']) && $unpack['error'] == true) {
            throw new MsfRpcException($unpack['error_message'], $unpack['error_code'] ?? 0);
        }

        return $unpack;
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return !is_null($this->token);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function encode(array $params = []): string
    {
        return msgpack_pack($params);
    }

    /**
     * @param string $str
     *
     * @return mixed|array
     */
    public function decode($str = '')
    {
        if (!is_array($data = msgpack_unpack($str))) {
            return [];
        }

        return $data;
    }

    /**
     * Dynamically retrieve managers on the client.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this[$name])) {
            return $this[$name];
        } else {
            throw new InvalidArgumentException("Property \"$name\" does not exist.");
        }
    }

    /**
     * Adds a new custom handler to instance.
     *
     * @param Handler $handler
     * @param bool    $override
     *
     * @return Client
     */
    public function addHandler(Handler $handler, bool $override = false): self
    {
        if (isset($this[$handler->getName()]) && $override === false) {
            throw new InvalidArgumentException("Handler {$handler->getName()} is already exist.");
        }

        $this[$handler->getName()] = function ($rpc) use ($handler) {
            return $handler->setRpc($rpc);
        };

        return $this;
    }
}
