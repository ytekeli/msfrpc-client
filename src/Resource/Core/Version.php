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
use Ytekeli\MsfRpcClient\Contracts\Response;

/**
 * Core version instance.
 *
 * @property string|null $version Metasploit version.
 * @property string|null $ruby    Ruby version.
 * @property string|null $api     Api version.
 */
class Version implements Response
{
    /**
     * Create a new version instance.
     *
     * @param Collection $items
     */
    public function __construct(Collection $items)
    {
        $this->version = $items->get('version');
        $this->ruby = $items->get('ruby');
        $this->api = $items->get('api');
    }

    /**
     * Checks any version is not null.
     */
    public function success(): bool
    {
        return !in_array(null, [$this->version, $this->ruby, $this->api]);
    }
}
