<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Feature;

use Ytekeli\MsfRpcClient\Resource\Threads;

class CoreTest extends ClientFeatureTestCase
{
    public function testItReturnsCoreVersion()
    {
        $this->assertTrue($this->client()->core->version()->success());
    }

    public function testThreadCollection()
    {
        $threads = $this->client()->core->threads();

        $this->assertInstanceOf(Threads::class, $threads);
        $this->assertTrue($threads->count() > 0);
    }
}
