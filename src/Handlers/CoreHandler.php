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
use Ytekeli\MsfRpcClient\Resource\Core\Stats;
use Ytekeli\MsfRpcClient\Resource\Core\Version;
use Ytekeli\MsfRpcClient\Resource\Core\ThreadCollection;
use Ytekeli\MsfRpcClient\Support\BaseResponse;

/**
 * Class CoreHandler.
 */
class CoreHandler extends Handler
{
    /**
     * Returns the RPC service versions.
     *
     * @return Version
     */
    public function version(): Version
    {
        return $this->call(MsfRpcMethod::CORE_VERSION, Version::class);
    }

    /**
     * Stops the RPC service.
     *
     * @return bool
     */
    public function stop(): bool
    {
        return $this->call(MsfRpcMethod::CORE_STOP, function ($items) {
            return new BaseResponse($items);
        })->success();
    }

    /**
     * Returns a global datastore option.
     *
     * @param string $option The name of the global datastore.
     *
     * @return string|null
     */
    public function getg(string $option = '')
    {
        return $this->call(MsfRpcMethod::CORE_GETG, function ($items) use ($option) {
            return $items->get($option);
        }, [$option]);
    }

    /**
     * Sets a global datastore option.
     *
     * @param string $option The hash key of the global datastore option.
     * @param string $value  The value of the global datastore option.
     *
     * @return bool
     */
    public function setg(string $option = '', string $value = ''): bool
    {
        return $this->call(MsfRpcMethod::CORE_SETG, function ($items) {
            return new BaseResponse($items);
        }, [$option, $value])->success();
    }

    /**
     * Unsets a global datastore option.
     *
     * @param string $option The global datastore option.
     *
     * @return bool
     */
    public function unsetg(string $option = ''): bool
    {
        return $this->call(MsfRpcMethod::CORE_UNSETG, function ($items) {
            return new BaseResponse($items);
        }, [$option])->success();
    }

    /**
     * Saves current framework settings.
     *
     * @return bool
     */
    public function save(): bool
    {
        return $this->call(MsfRpcMethod::CORE_SAVE, function ($items) {
            return new BaseResponse($items);
        })->success();
    }

    /**
     * Reloads framework modules. This will take some time to complete.
     *
     * @return bool
     */
    public function reload(): bool
    {
        return $this->call(MsfRpcMethod::CORE_RELOAD_MODULES, Stats::class)->success();
    }

    /**
     * Adds a new local file system path (local to the server) as a module path.
     *
     * @param string $path The new path to load.
     *
     * @return bool
     */
    public function addModulePath(string $path = ''): bool
    {
        return $this->call(MsfRpcMethod::CORE_ADD_MODULE_PATH, function () {
            return new BaseResponse(collect(['result' => 'success']));
        }, [$path])->success();
    }

    /**
     * Returns the module stats.
     *
     * @return Stats
     */
    public function stats()
    {
        return $this->call(MsfRpcMethod::CORE_STATS, Stats::class);
    }

    /**
     * Returns a list of framework threads.
     *
     * @return Collection
     */
    public function threads()
    {
        return $this->call(MsfRpcMethod::CORE_THREAD_LIST, ThreadCollection::class);
    }

    /**
     * Kills a framework thread.
     *
     * @param int|null $threadId The thread ID to kill.
     *
     * @return bool
     */
    public function kill(int $threadId = null)
    {
        return $this->call(MsfRpcMethod::CORE_THREAD_KILL, function ($items) {
            return new BaseResponse($items);
        }, [$threadId])->success();
    }
}
