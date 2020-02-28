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

class AuthTest extends ClientFeatureTestCase
{
    public function testTokens()
    {
        $this->assertTrue($this->client()->auth->tokens()->count() > 0);
    }
}
