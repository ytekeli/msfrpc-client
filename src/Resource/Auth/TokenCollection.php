<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Resource\Auth;

use Ytekeli\MsfRpcClient\Resource\ResourceCollection;

class TokenCollection extends ResourceCollection
{
    /**
     * Create a new token collection instance.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
