<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Contract;

use Ytekeli\MsfRpcClient\Client;

interface Handler
{
    /**
     * Gets handler name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Sets Msf RPC client.
     *
     * @param Client $client
     *
     * @return mixed
     */
    public function setRpc(Client $client);
}
