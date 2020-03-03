<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Resource\Auth;

use Ytekeli\MsfRpcClient\Resource\Auth\TokenCollection;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class TokenCollectionTest extends ClientTestCase
{
    public function testTokenCollectionWithData()
    {
        $collection = new TokenCollection([
            'token1', 'token2', 'token3'
        ]);

        $this->assertEquals($collection->toArray(), [
            'token1', 'token2', 'token3'
        ]);
    }

    public function testTokenCollectionWithoutData()
    {
        $this->assertEquals((new TokenCollection())->toArray(), []);
    }
}
