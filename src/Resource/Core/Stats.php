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
use Ytekeli\MsfRpcClient\Resource\Resource;

/**
 * Stats Instance
 * @package Ytekeli\MsfRpcClient\Resource\Core
 * @property int $exploits  The number of exploits.
 * @property int $auxiliary The number of auxiliary modules.
 * @property int $post      The number of post modules.
 * @property int $encoders  The number of encoders.
 * @property int $nops      The number of NOP modules.
 * @property int $payloads  The number of payloads.
 */
class Stats extends Resource
{
    /**
     * @var int
     */
    public $exploits = 0;

    /**
     * @var int
     */
    public $auxiliary = 0;

    /**
     * @var int
     */
    public $post = 0;

    /**
     * @var int
     */
    public $encoders = 0;

    /**
     * @var int
     */
    public $nops = 0;

    /**
     * @var int
     */
    public $payloads = 0;

    /**
     * Create a new stats instance.
     *
     * @param Collection|null $collection Stats property collection.
     */
    public function __construct(Collection $collection = null)
    {
        parent::__construct($collection);
    }

    public function success(): bool
    {
        return
            $this->exploits > 0 &&
            $this->auxiliary > 0 &&
            $this->post > 0 &&
            $this->encoders > 0 &&
            $this->nops > 0 &&
            $this->payloads > 0;
    }
}
