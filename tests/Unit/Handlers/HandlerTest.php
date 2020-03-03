<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Handlers;

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Handlers\CoreHandler;
use Ytekeli\MsfRpcClient\Handlers\Handler;
use Ytekeli\MsfRpcClient\Client;
use Ytekeli\MsfRpcClient\Support\BaseResponse;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class HandlerTest extends ClientTestCase
{
    public function testItReturnsClassName()
    {
        $this->assertEquals((new Handler())->getName(), 'handler');
    }

    public function testItReturnsClassNameFromReflection()
    {
        $this->assertEquals((new CoreHandler())->getName(), 'core');
    }

    public function testItSetsRpcClientToHandler()
    {
        $handler = (new Handler())->setRpc($this->client());

        $this->assertInstanceOf(Client::class, $handler->rpc);
    }

    public function testCallMethodWithoutCallback()
    {
        $handler = (new Handler())->setRpc($this->clientMock());

        $items = $handler->call('test.request', null, []);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertEquals($items->toArray(), []);
    }

    public function testCallMethodWithCallable()
    {
        $handler = (new Handler())->setRpc($this->clientMock([
            1, 2, 3,
        ]));

        $result = $handler->call('test.request', function ($items, $handler) {
            return [
                $handler, $items->toArray(),
            ];
        });

        $this->assertEquals([
            $handler, [1, 2, 3],
        ], $result);
    }

    public function testCallMethodWithClass()
    {
        $handler = (new Handler())->setRpc($this->clientMock([
            $this->fakeSuccess(),
        ]));

        $this->assertTrue($handler->call('test.request', BaseResponse::class)->success());
    }

    public function testCallMethodWithException()
    {
        $this->expectException(\Exception::class);

        $this->clientWithHttpMock([
            $this->createHttpResponsePack([
                'result' => 'false',
            ]),
        ], ['authenticate' => false])->core->version();
    }
}
