<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Exception;

use Exception;

class MsfRpcAuthException extends Exception
{
    const UNAUTHENTICATED = 'Unauthenticated.';

    const AUTH_FAILED = 'Authentication failed.';

    /**
     * Create a new authentication exception.
     *
     * @param string $message
     */
    public function __construct($message = 'Unauthenticated.')
    {
        $message = 'MsfRpc: '.$message;

        parent::__construct($message);
    }
}
