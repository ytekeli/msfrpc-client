<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Handlers;

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\MsfRpcMethod;
use Ytekeli\MsfRpcClient\Resource\Auth\Tokens;
use Ytekeli\MsfRpcClient\Support\BaseResponse;

class AuthHandler extends Handler
{
    /**
     * Handles client authentication. The authentication token will expire 5 minutes after the
     * last request was made.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username = '', string $password = ''): bool
    {
        $result = $this->call(MsfRpcMethod::AUTH_LOGIN, null, [$username, $password]);

        if ($result->get('result') === 'success') {
            $this->rpc->token = $result->get('token');
            return true;
        }

        return false;
    }

    /**
     * Adds a new token to the database.
     *
     * @param string $token A unique token.
     * @return bool
     */
    public function add(string $token = ''): bool
    {
        return $this->call(MsfRpcMethod::AUTH_TOKEN_ADD, function ($items) {
            return new BaseResponse($items);
        }, [$token])->success();
    }

    /**
     * Returns a list of authentication tokens, including the ones that are
     * temporary, permanent, or stored in the backend.
     *
     * @return Collection|Tokens
     */
    public function tokens()
    {
        return $this->call(MsfRpcMethod::AUTH_TOKEN_LIST, Tokens::class);
    }
}
