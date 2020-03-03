<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Handler;

use Exception;
use GuzzleHttp\Exception\ConnectException;
use ReflectionClass;
use ReflectionException;
use Ytekeli\MsfRpcClient\Contract\Handler as HandlerContract;
use Ytekeli\MsfRpcClient\Client;
use Ytekeli\MsfRpcClient\Support\MsfRpcMethod;
use Ytekeli\MsfRpcClient\Resource\Collection;

class Handler implements HandlerContract
{
    /**
     * @var Client Msf RPC Client
     */
    public $rpc;

    /**
     * {@inheritdoc}
     *
     * @throws ReflectionException
     */
    public function getName(): string
    {
        $className = strtolower((new ReflectionClass($this))->getShortName());

        if ($className != 'handler') {
            $className = str_replace('handler', '', $className);
        }

        return $className;
    }

    /**
     * {@inheritdoc}
     *
     * @param Client $client MsfRpc client instance
     *
     * @return $this|mixed
     */
    public function setRpc(Client $client)
    {
        $this->rpc = $client;

        return $this;
    }

    /**
     * @param string               $method
     * @param callable|string|null $callback
     * @param array                $params
     *
     * @return mixed
     */
    public function call(string $method = '', $callback = null, array $params = [])
    {
        $items = [];

        try {
            $items = $this->rpc->call($method, $params);
        } catch (ConnectException $connectException) {
            // set connection error
            $this->rpc->exception->setException($connectException);
        } catch (\Exception $exception) {
            // set other stuff
            $this->rpc->exception->setException($exception);
        }

        if ($this->rpc->exception->isError()) {
            // if error is connection and method core.stop return success message
            if ($this->rpc->exception->instanceOf(ConnectException::class) &&
                $method == MsfRpcMethod::CORE_STOP) {
                $items = ['result' => 'success'];
            } else {
                throw new Exception($this->rpc->exception->get()); // TODO improve error handling
            }
        }

        $items = collect($items);

        if (is_callable($callback)) {
            return $callback($items, $this);
        }

        if (is_string($callback) && class_exists($callback)) {
            if (get_parent_class($callback) == Collection::class) {
                $items = $items->toArray();
            }

            return new $callback($items, $this);
        }

        return $items;
    }
}
