<?php

namespace TencentHelper;

use TencentCloud\Ivld\V20210903\IvldClient;

/**
 * 视频智能标签
 *
 * @date 2022-09-01 14:40:31
 */
abstract class AbstractIvld extends AbstractBase {

    /**
     * 接口地址
     *
     * @var string
     * @date 2022-09-01 14:46:17
     */
    public $endPoint = 'ivld.tencentcloudapi.com';

    /**
     * 地区
     *
     * @var string
     * @date 2022-09-07 09:52:59
     */
    public $region = 'ap-shanghai';

    /**
     * client
     *
     * @return void
     * @date 2022-09-01 14:46:35
     */
    public function setClient()
    {
        $this->client = new IvldClient($this->credential, $this->region, $this->clientProfile);
    }
}