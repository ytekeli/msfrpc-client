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
                "cmd/unix/bind_perl",
                "cmd/unix/bind_perl_ipv6",
                "cmd/unix/reverse_perl",
                "cmd/unix/reverse_perl_ssl",
            ],
        ])->module->compatiblePayloads('exploit/aix/local/ibstat_path');

        $this->assertEquals($payloads->count(), 4);
    }
}
