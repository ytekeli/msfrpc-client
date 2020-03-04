<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Ytekeli\MsfRpcClient\Client;

class ClientTestCase extends TestCase
{
    /**
     * @var array Msf RPC Client Config
     */
    public static $config;

    /**
     * @var Client Msf RPC Client Object
     */
    protected $client;

    public function setUp(): void
    {
        parent::setUp();

        self::$config = [
            'host'          => '127.0.0.1',
            'port'          => 55553,
            'ssl'           => true,
            'authenticate'  => false,
        ];

        $this->client = new Client(self::$config);
    }

    public function client(array $config = [])
    {
        $config = array_merge(self::$config, $config);

        return new Client($config);
    }

    public function clientWithHttpMock(array $responses = [], array $config = [])
    {
        $mock = new MockHandler($responses);

        $config = array_merge([
            'authenticate'   => true,
            'guzzle_options' => [
                'handler' => HandlerStack::create($mock),
            ],
        ], $config);

        return $this->client($config);
    }

    public function createHttpResponsePack(array $params = [], int $statusCode = 200)
    {
        return new Response($statusCode, [], msgpack_pack($params));
    }

    public function generateFakeToken()
    {
        return uniqid('TEMP');
    }

    public function clientMock(array $responseParams = [], bool $authenticate = true)
    {
        $queues = [];

        if ($authenticate === true) {
            $queues[] = $this->createHttpResponsePack(['result' => 'success', 'token' => $this->generateFakeToken()]);
        }

        if (!$responseParams) {
            $queues[] = $this->createHttpResponsePack([]);
        }

        foreach ($responseParams as $responseKey => $responseParam) {
            if (is_array($responseParam)) {
                $queues[] = $this->createHttpResponsePack(
                    is_string($responseKey) ? [$responseKey => $responseParam] : $responseParam
                );
            } elseif ($responseParam instanceof Response) {
                $queues[] = $responseParam;
            } else {
                $queues[] = $this->createHttpResponsePack($responseParams);
                break;
            }
        }

        return $this->clientWithHttpMock($queues);
    }

    public function fakeLogin()
    {
        return [
            'result' => 'success',
            'token' => $this->generateFakeToken()
        ];
    }

    public function fakeThreads()
    {
        return [
            [
                'status'    => 'sleep',
                'critical'  => false,
                'name'      => 'StreamServerListener',
                'started'   => '2020-02-25 15:01:43 +0300',
            ],
            [
                'status'    => 'run',
                'critical'  => false,
                'name'      => 'StreamServerClientMonitor',
                'started'   => '2020-02-25 15:01:43 +0300',
            ],
            [
                'status'    => 'sleep',
                'critical'  => true,
                'name'      => 'MetasploitRPCServer',
                'started'   => '2020-02-25 15:01:43 +0300',
            ],
        ];
    }

    public function fakeSuccess()
    {
        return ['result' => 'success'];
    }
}
