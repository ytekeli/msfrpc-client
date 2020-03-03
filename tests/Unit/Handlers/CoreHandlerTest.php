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

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Ytekeli\MsfRpcClient\Resource\Core\Stats;
use Ytekeli\MsfRpcClient\Resource\Core\Version;
use Ytekeli\MsfRpcClient\Resource\Thread;
use Ytekeli\MsfRpcClient\Resource\Core\ThreadCollection;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class CoreHandlerTest extends ClientTestCase
{
    public function testVersion()
    {
        $result = $this->clientmock([
            'version'   => 1,
            'ruby'      => 2,
            'api'       => 3,
        ])->core->version();

        $this->assertInstanceOf(Version::class, $result);
        $this->assertEquals($result->version, 1);
        $this->assertEquals($result->ruby, 2);
        $this->assertEquals($result->api, 3);
    }

    public function testStopService()
    {
        $client = $this->clientWithHttpMock([
            $this->createHttpResponsePack([
                'result' => 'success',
                'token'  => $this->generateFakeToken(),
            ]),
            new ConnectException('cURL error 52: Empty reply from server', new Request('POST', 'test')),
        ]);

        $this->assertTrue($client->core->stop());
    }

    public function testCoreGetGlobal()
    {
        $client = $this->clientMock([
            'ConsoleLogging' => 'true',
        ]);

        $this->assertEquals($client->core->getg('ConsoleLogging'), 'true');
    }

    public function testCoreSetGlobal()
    {
        $client = $this->clientMock([
            'result' => 'success',
        ]);

        $this->assertTrue($client->core->setg('consoleLogging', 'false'));
    }

    public function testCoreUnSetGlobal()
    {
        $client = $this->clientMock([
            'result' => 'success',
        ]);

        $this->assertTrue($client->core->unsetg('consoleLogging'));
    }

    public function testCoreSave()
    {
        $client = $this->clientMock([
            'result' => 'success',
        ]);

        $this->assertTrue($client->core->save());
    }

    public function testCoreReloadModules()
    {
        $client = $this->clientMock([
            'exploits'  => 1962,
            'auxiliary' => 1094,
            'post'      => 336,
            'encoders'  => 45,
            'nops'      => 10,
            'payloads'  => 558,
        ]);

        $this->assertTrue($client->core->reload());
    }

    public function testCoreAddModulePath()
    {
        $this->assertTrue($this->clientMock()->core->addModulePath('/tmp/modules'));
    }

    public function testCoreStats()
    {
        $client = $this->clientMock([
            'exploits'  => 1,
            'auxiliary' => 2,
            'post'      => 3,
            'encoders'  => 4,
            'nops'      => 5,
            'payloads'  => 6,
        ]);

        $this->assertEquals($client->core->stats(), new Stats(collect([
            'exploits'  => 1,
            'auxiliary' => 2,
            'post'      => 3,
            'encoders'  => 4,
            'nops'      => 5,
            'payloads'  => 6,
        ])));
    }

    public function testCoreThreads()
    {
        $threads = $this->clientMock([$this->fakeThreads()])->core->threads();

        $this->assertEquals($threads->count(), 3);
        $this->assertInstanceOf(ThreadCollection::class, $threads);

        foreach ($threads as $thread) {
            $this->assertInstanceOf(Thread::class, $thread);
        }
    }

    public function testCoreThreadsWithEmptyResponse()
    {
        $threads = $this->clientMock([0 => []])->core->threads();

        $this->assertInstanceOf(ThreadCollection::class, $threads);
        $this->assertEquals($threads->all(), []);
    }

    public function testCoreKillThread()
    {
        // TODO improve kill test

        $this->assertTrue($this->clientMock(['result' => 'success'])->core->kill());
    }

    public function testItKillsThreadInThreadObject()
    {
        // TODO improve kill test

        $client = $this->clientMock([$this->fakeThreads(), $this->fakeSuccess()]);

        $this->assertTrue($client->core->threads()->first()->kill());
    }
}
