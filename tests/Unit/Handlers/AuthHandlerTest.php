<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Tests\Unit\Handlers;

use Ytekeli\MsfRpcClient\Tests\ClientTestCase;

class AuthHandlerTest extends ClientTestCase
{
    public function testAuthHandlerLogin()
    {
        $token = $this->generateFakeToken();

        $client = $this->clientWithHttpMock([
            $this->createHttpResponsePack(['result' => 'success', 'token' => $token]),
        ], ['authenticate' => false]);

        $this->assertTrue($client->auth->login());
        $this->assertEquals($client->token, $token);
    }

    public function testLoginWithTokenSuccess()
    {
        $client = $this->client();

        $this->assertEquals($this->client(['token' => $client->token])->token, $client->token);
    }

    public function testAddTokenSuccess()
    {
        $client = $this->clientMock([
            $this->fakeSuccess(),
        ]);

        $this->assertTrue($client->auth->add($this->generateFakeToken()));
    }

    public function testTokens()
    {
        $client = $this->clientMock([
            'token1', 'token2', 'token3'
        ]);

        $this->assertEquals($client->auth->tokens()->toArray(), [
            'token1', 'token2', 'token3'
        ]);
    }
}
