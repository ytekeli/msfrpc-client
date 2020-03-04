<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Resource\Module;

use Ytekeli\MsfRpcClient\Exception\MsfRpcException;
use Ytekeli\MsfRpcClient\Handler\ModuleHandler;

class ModuleCollection
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var ModuleHandler
     */
    protected $handler;

    /**
     * ModuleCollection constructor.
     *
     * @param string $type
     * @param string $name
     * @param ModuleHandler $handler
     */
    public function __construct(string $type, string $name, ModuleHandler $handler)
    {
        $this->type = $type;
        $this->name = $name;
        $this->handler = $handler;
    }

    /**
     * Gets a specific module.
     *
     * @return mixed
     */
    public function get()
    {
        $info = $this->handler->info($this->type, $this->name);

        if ($this->type == 'exploit') {
            return new ExploitModule($info, $this->handler);
        }

        throw new \BadMethodCallException(sprintf(
            'Unknown module type %s not: exploit, post, encoder, auxiliary, nop, or payload',
            $this->type
        ));
    }
}
