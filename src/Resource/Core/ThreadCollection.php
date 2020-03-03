<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Resource\Core;

use Ytekeli\MsfRpcClient\Handlers\CoreHandler;
use Ytekeli\MsfRpcClient\Resource\Collection;
use Ytekeli\MsfRpcClient\Resource\Thread;

/**
 * Thread Collection Instance.
 */
class ThreadCollection extends Collection
{
    /**
     * Create a new thread collection instance.
     *
     * @param array            $threads The thread list.
     * @param CoreHandler|null $handler Core handler instance.
     */
    public function __construct(array $threads, CoreHandler $handler = null)
    {
        foreach ($threads as $key => $item) {
            if (!$item instanceof Thread) {
                $item['id'] = $key;
                $threads[$key] = new Thread(collect($item), $handler);
            }
        }

        parent::__construct($threads);
    }

    /**
     * Get critical threads.
     *
     * @return ThreadCollection
     */
    public function critical()
    {
        return $this->filter(function ($thread) {
            return $thread->critical === true;
        });
    }
}
