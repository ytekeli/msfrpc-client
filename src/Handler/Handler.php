<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Handler;

use GuzzleHttp\Exception\ConnectException;
use ReflectionClass;
use ReflectionException;
use Ytekeli\MsfRpcClient\Client;
use Ytekeli\MsfRpcClient\Contract\Handler as HandlerContract;
use Ytekeli\MsfRpcClient\Resource\Collection;
use Ytekeli\MsfRpcClient\Support\MsfRpcMethod;

class Handler implements HandlerContract
{
    /**
     * @var Client Msf RPC Client
     */
    public $rpc;

    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     */
    public function getName(): string
    {
        $className = strtolower((new ReflectionClass($this))->getShortName());

        if ($className != 'handler') {
            $className = str_replace('handler', '', $className);
        }

        return $className;
    }

    /**
     * {@inheritdoc}
     *
     * @param Client $client MsfRpc client instance
     *
     * @return Handler
     */
    public function setRpc(Client $client)
    {
        $this->rpc = $client;

        return $this;
    }

    /**
     * @param string $method
     * @param callable|string|null $callback
     * @param array $params
     *
     * @return mixed
     */
    public function call(string $method = '', $callback = null, array $params = [])
    {
        $items = [];

        try {
            $items = $this->rpc->call($method, $params);
        } catch (\Exception $exception) {
            // set exception
            $this->rpc->exception->setException($exception);
        }

        if ($this->checkConnectionError($method) === true) {
            $items = ['result' => 'success'];
        }

        $items = collect($items);

        if (is_callable($callback)) {
            return $callback($items, $this);
        }

        if (is_string($callback) && class_exists($callback)) {
            if (get_parent_class($callback) == Collection::class) {
                $items = $items->toArray();
            }

            return new $callback($items, $this);
        }

        return $items;
    }

    /**
     * @param string $method
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function checkConnectionError(string $method = '')
    {
        if ($this->rpc->exception->isError()) {
            if ($this->isConnectionError() && $this->isNoResponseMethod($method)) {
                return true;
            }

            throw $this->rpc->exception->get(); // TODO improve error handling
        }

        return false;
    }

    /**
     * @return bool
     */
    private function isConnectionError()
    {
        return $this->rpc->exception->instanceOf(ConnectException::class);
    }

    /**
     * @param string $method
     * @return bool
     */
    private function isNoResponseMethod(string $method = '')
    {
        return in_array($method, [MsfRpcMethod::CORE_STOP]);
    }
}
