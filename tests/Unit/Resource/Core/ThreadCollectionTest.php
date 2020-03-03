<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Resource\Core;

use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ThreadCollectionTest extends ClientTestCase
{
    public function testItReturnsCriticalThreads()
    {
        $critical = $this->clientMock([$this->fakeThreads()])->core->threads()->critical()->first();

        $this->assertEquals($critical->id, 2);
        $this->assertEquals($critical->status, 'sleep');
        $this->assertEquals($critical->critical, true);
        $this->assertEquals($critical->name, 'MetasploitRPCServer');
        $this->assertEquals($critical->started, '2020-02-25 15:01:43 +0300');
    }
}
