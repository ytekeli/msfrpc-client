<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Contracts;

use Ytekeli\MsfRpcClient\MsfRpcClient;

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
     * @param MsfRpcClient $client
     *
     * @return mixed
     */
    public function setRpc(MsfRpcClient $client);
}
