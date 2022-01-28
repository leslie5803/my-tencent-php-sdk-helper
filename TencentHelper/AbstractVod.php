<?php

namespace TencentHelper;

use TencentCloud\Vod\V20180717\VodClient;
use TencentHelper\AbstractBase;

/**
 * 云点播抽象类
 *
 * @date       2022-01-06 21:24:36
 */
abstract class AbstractVod extends AbstractBase
{
    /**
     * 接口
     *
     * @var string
     * @date       2022-01-06 21:24:29
     */
    public $endPoint = 'vod.tencentcloudapi.com';

    /**
     * 云点播实例
     *
     * @return void
     * @date       2022-01-06 21:24:51
     */
    public function setClient()
    {
        $this->client = new VodClient($this->credential, $this->region, $this->clientProfile);
    }
}
