<?php
/**
 * This file is part of ytekeli/msfrpc-client.
 *
 * (c) Yahya Tekeli <yahyatekeli@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ytekeli\MsfRpcClient\Support;

use Tightenco\Collect\Support\Collection;
use Ytekeli\MsfRpcClient\Contract\Responsable;

/**
 * Class BaseResponse.
 *
 * @property mixed $result MsfRpc success message
 */
class BaseResponse implements Responsable
{
    /**
     * @var Collection|null
     */
    protected $response;

    /**
     * @var callable|null
     */
    protected $successCallback;

    /**
     * BaseResponse constructor.
     *
     * @param Collection|null $response
     * @param callable|null $successCallback
     */
    public function __construct(Collection $response = null, $successCallback = null)
    {
        if (is_null($response)) {
            $response = collect([]);
        }

        $this->response = $response;

        if (is_callable($successCallback)) {
            $this->successCallback = $successCallback;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function success(): bool
    {
        if ($this->successCallback) {
            return call_user_func($this->successCallback, $this->response);
        }
        return $this->response->get('result') === 'success';
    }

    /**
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '')
    {
        return $this->response->get($key);
    }
}
