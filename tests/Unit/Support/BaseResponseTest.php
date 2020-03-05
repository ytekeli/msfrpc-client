<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Support;

use Ytekeli\MsfRpcClient\Support\BaseResponse;
use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class BaseResponseTest extends ClientTestCase
{
    public function testHandleNullResponseCollection()
    {
        $response = new BaseResponse(null, function ($item) {
            return $item->get('a') === $item->get('b');
        });

        $this->assertTrue($response->success());
    }
}
