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

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Contract\Handler;
use Ytekeli\MsfRpcClient\Handler\CoreHandler;
use Ytekeli\MsfRpcClient\Resource\Resource;

/**
 * Thread Resource Instance.
 *
 * @property int|null     $id       Thread ID.
 * @property string|null  $status   Thread status.
 * @property bool|null $critical Thread is critical.
 * @property string|null  $name     Thread name.
 * @property string|null  $started  Timestamp of when the thread started.
 * @property-read Handler $handler  Handler instance.
 */
class Thread extends Resource
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var bool
     */
    public $critical;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $started;

    /**
     * @var CoreHandler
     */
    protected $handler;

    /**
     * Create a new thread instance.
     *
     * @param Collection       $collection Thread property collection.
     * @param CoreHandler|null $handler    Core Handler instance.
     */
    public function __construct(Collection $collection, CoreHandler $handler = null)
    {
        parent::__construct($collection, $handler);
    }

    /**
     * Kills the current thread.
     *
     * @return bool
     */
    public function kill()
    {
        return $this->handler->kill($this->id);
    }
}
