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
     * @var mixed
     */
    protected $result;

    /**
     * BaseResponse constructor.
     *
     * @param Collection|null $response
     */
    public function __construct(Collection $response = null)
    {
        if (!is_null($response)) {
            $this->result = $response->get('result');
        }
    }

    /**
     * @return bool
     */
    public function success(): bool
    {
        return $this->result === 'success';
    }
}
