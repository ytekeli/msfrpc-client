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

use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class ClientFeatureTestCase extends ClientTestCase
{
    public function client(array $config = [])
    {
        $options = array_merge([
            'authenticate' => true,
            'password'     => 'test',
        ], $config);

        return parent::client($options);
    }
}
