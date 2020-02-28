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

/**
 * Exception Handler Instance
 * @package Ytekeli\MsfRpcClient\Handlers
 */
class Exception extends Handler
{
    /**
     * @var \Exception|mixed
     */
    protected $exception = false;

    /**
     * Set a new exception instance
     *
     * @param \Exception $exception Exception thrown class
     * @return void
     */
    public function setException(\Exception $exception): void
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception|mixed
     */
    public function get()
    {
        return $this->exception;
    }

    /**
     * @return bool|\Exception
     */
    public function isError()
    {
        return $this->exception;
    }

    /**
     * @param string $exceptionClassName
     * @return bool
     */
    public function instanceOf(string $exceptionClassName = '')
    {
        return $this->exception instanceof $exceptionClassName;
    }
}
