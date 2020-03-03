<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit;

use Exception;
use InvalidArgumentException;
use Ytekeli\MsfRpcClient\Exception\MsfRpcAuthException;
use Ytekeli\MsfRpcClient\Handler\CoreHandler;
use Ytekeli\MsfRpcClient\Support\MsfRpcMethod;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ClientTest extends ClientTestCase
{
    public function testNoHandlers()
    {
        $client = $this->client(['no_handlers' => true]);

        $this->expectException(InvalidArgumentException::class);

        $client->core;
    }

    /**
     * @throws Exception
     */
    public function testAddHandlerSuccess(): void
    {
        $client = $this->client()->addHandler(new CoreHandler(), true);

        $this->assertInstanceOf(get_class($client->core), new CoreHandler());
    }

    /**
     * @throws Exception
     */
    public function testAddHandlerFailsWithoutOverride(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Handler core is already exist.');

        $this->client()->addHandler(new CoreHandler());
    }

    public function testDynamicallyRetrieverFailsWhenHandlerNotDefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Property "foo" does not exist.');

        $this->client()->foo;
    }

    public function testAuthFailed()
    {
        $this->assertFalse($this->clientMock()->auth->login('username', 'password'));
    }

    /**
     * @throws MsfRpcAuthException
     * @throws \Ytekeli\MsfRpcClient\Exception\MsfRpcException
     */
    public function testUnauthenticated()
    {
        $this->expectException(MsfRpcAuthException::class);
        $this->expectExceptionMessage(MsfRpcAuthException::UNAUTHENTICATED);

        $this
            ->client()
            ->call(MsfRpcMethod::CORE_VERSION);
    }

    public function testDecode()
    {
        $this->assertEquals($this->client()->decode(msgpack_pack([1, 2, 3])), [1, 2, 3]);

        $this->assertEquals($this->client()->decode(msgpack_pack(new \stdClass())), []);
    }

    public function testCallWithServerException()
    {
        $this->expectExceptionMessage('MsfRpc server error.');
        $this->expectExceptionCode(0);

        $this->clientMock([
            'error'         => true,
            'error_message' => 'MsfRpc server error.',
        ])->core->version();
    }
}
