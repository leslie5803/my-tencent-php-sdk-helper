<?php

namespace TencentHelper;

use TencentCloud\Live\V20180801\LiveClient;

/**
 * 云直播
 *
 * @date       2022-03-07 21:21:41
 */
abstract class AbstractLive extends AbstractBase {

    /**
     * 接口
     *
     * @var string
     * @date       2022-01-06 21:24:29
     */
    public $endPoint = 'live.tencentcloudapi.com';

    /**
     * 初始化client
     *
     * @return void
     * @date       2022-03-07 21:21:56
     */
    public function setClient()
    {
        $this->client = new LiveClient($this->credential, $this->region, $this->clientProfile);
    }
}