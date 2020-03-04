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

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Resource\Module\Exploit;
use Ytekeli\MsfRpcClient\Resource\Module\ModuleCollection;
use Ytekeli\MsfRpcClient\Support\MsfRpcMethod;

class ModuleHandler extends Handler
{
    /**
     * Returns a list of exploit names. The 'exploit/' prefix will not be included.
     *
     * @return Collection
     */
    public function exploits()
    {
        return $this->list('exploit', MsfRpcMethod::MODULE_EXPLOITS);
    }

    /**
     * Returns a list of modules.
     *
     * @param string $type
     * @param string $method
     *
     * @return mixed
     */
    private function list(string $type = '', string $method = '')
    {
        return $this->call($method, function ($items) use ($type) {
            return collect($items->get('modules'))->map(function ($list) use ($type) {
                return new ModuleCollection($type, $list, $this);
            });
        });
    }

    /**
     * Returns the metadata for a module.
     *
     * @param string $moduleType
     * @param string $moduleName
     *
     * @return mixed
     */
    public function info(string $moduleType = '', string $moduleName = '')
    {
        return $this->call(MsfRpcMethod::MODULE_INFO, null, [$moduleType, $moduleName]);
    }

    /**
     * Returns the compatible payloads for a specific exploit.
     *
     * @param string $moduleName
     *
     * @return mixed
     */
    public function compatiblePayloads(string $moduleName = '')
    {
        return $this->call(MsfRpcMethod::MODULE_COMPATIBLE_PAYLOADS, function (Collection $items) {
            return collect($items->get('payloads'));
        }, [$moduleName]);
    }

    /**
     * Returns the module's datastore options.
     *
     * @param string $moduleType
     * @param string $moduleName
     *
     * @return mixed
     */
    public function options(string $moduleType = '', string $moduleName = '')
    {
        return $this->call(MsfRpcMethod::MODULE_OPTIONS, null, [$moduleType, $moduleName]);
    }
}
