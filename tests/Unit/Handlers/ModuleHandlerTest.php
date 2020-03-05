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

use Ytekeli\MsfRpcClient\Exception\MsfRpcException;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ModuleHandlerTest extends ClientTestCase
{
    public function testExploitsMethod()
    {
        $exploits = $this->clientMock([
            'modules' => [
                'linux/http/asuswrt_lan_rce',
                'linux/http/dlink_dcs931l_upload',
                'linux/local/sophos_wpa_clear_keys',
            ],
        ])->module->exploits();

        $this->assertEquals($exploits->count(), 3);
    }

    public function testCompatiblePayloadsMethod()
    {
        $payloads = $this->clientMock([
            'payloads' => [
                'cmd/unix/bind_perl',
                'cmd/unix/bind_perl_ipv6',
                'cmd/unix/reverse_perl',
                'cmd/unix/reverse_perl_ssl',
            ],
        ])->module->compatiblePayloads('exploit/aix/local/ibstat_path');

        $this->assertEquals($payloads->count(), 4);
    }

    public function testExecuteMethodReturnsSuccess()
    {
        $execute = $this->clientMock([
            'job_id' => 0,
            'uuid' => 'yHKjJtaKzuuclfx7guF3UF6C',
        ])->module->execute('exploit', 'scanner/netbios/nbname');

        $this->assertTrue($execute->success());
        $this->assertEquals($execute->get('job_id'), 0);
        $this->assertEquals($execute->get('uuid'), 'yHKjJtaKzuuclfx7guF3UF6C');
    }

    public function testExecuteMethodFailsWhenJobIdNotInteger()
    {
        $execute = $this->clientMock([
            'job_id' => "None",
            'uuid' => 'yHKjJtaKzuuclfx7guF3UF6C',
        ])->module->execute('exploit', 'scanner/netbios/nbname');

        $this->assertFalse($execute->success());
        $this->assertEquals($execute->get('job_id'), 'None');
        $this->assertEquals($execute->get('uuid'), 'yHKjJtaKzuuclfx7guF3UF6C');
    }

    public function testExecuteMethodFailsIfModuleTypeNotRecognize()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Unknown module type nops not: exploit, post, auxiliary, evasion, or payload'
        );

        $this->clientMock()->module->execute('nops', 'scanner/netbios/nbname');
    }

    public function testExecuteMethodHandleServerError()
    {
        $this->expectException(MsfRpcException::class);
        $this->expectExceptionMessage(
            'Invalid module.'
        );

        $this->clientMock([
            'error' => true,
            'error_message' => 'Invalid module.',
        ])->module->execute('exploit');
    }
}
