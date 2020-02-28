<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient;

/**
 * Class MsfRpcMethod
 * @package Ytekeli\MsfRpcClient
 */
class MsfRpcMethod
{
    const AUTH_LOGIN            = 'auth.login';
    const AUTH_TOKEN_ADD        = 'auth.token_add';
    const AUTH_TOKEN_LIST       = 'auth.token_list';
    const CORE_VERSION          = 'core.version';
    const CORE_STOP             = 'core.stop';
    const CORE_GETG             = 'core.getg';
    const CORE_SETG             = 'core.setg';
    const CORE_UNSETG           = 'core.unsetg';
    const CORE_SAVE             = 'core.save';
    const CORE_RELOAD_MODULES   = 'core.reload_modules';
    const CORE_ADD_MODULE_PATH  = 'core.add_module_path';
    const CORE_STATS            = 'core.module_stats';
    const CORE_THREAD_LIST      = 'core.thread_list';
    const CORE_THREAD_KILL      = 'core.thread_kill';
    const MODULE_EXPLOITS       = 'module.exploits';
}
