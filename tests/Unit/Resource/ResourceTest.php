<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Resource;

use Ytekeli\MsfRpcClient\Resource\Resource;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ResourceTest extends ClientTestCase
{
    public function testSuccessMethodReturnsFalseForDefault()
    {
        $this->assertFalse((new Resource())->success());
    }
}
