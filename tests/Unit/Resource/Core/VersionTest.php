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

use Ytekeli\MsfRpcClient\Resource\Core\Version;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class VersionTest extends ClientTestCase
{
    public function testVersion()
    {
        $version = new Version(collect([
            'version' => 1,
            'ruby' => 2,
            'api' => 3
        ]));
        
        $this->assertTrue($version->success());
    }
}
