<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Resource;

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Contract\Handler;
use Ytekeli\MsfRpcClient\Contract\Responsable;

/**
 * Resource Instance.
 *
 * @property Handler $handler Handler instance.
 */
class Resource implements Responsable
{
    /**
     * Create a new resource instance.
     *
     * @param Collection $collection The resource collection.
     * @param Handler|null $handler Handler instance.
     */
    public function __construct(Collection $collection = null, Handler $handler = null)
    {
        if (!is_null($collection)) {
            foreach (get_object_vars($this) as $property => $defaultValue) {
                $this->{$property} = $collection->get($property, $defaultValue);
            }
        }

        if (!is_null($handler) && property_exists($this, 'handler')) {
            $this->handler = $handler;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function success(): bool
    {
        return false;
    }
}
