<?php

namespace TencentHelper;

use TencentCloud\Ims\V20201229\ImsClient;

/**
 * 图片内容安全
 *
 * @date 2022-09-27 14:25:21
 */
abstract class AbstractIms extends AbstractBase {

    /**
     * end point
     *
     * @var string
     * @date 2022-09-27 14:25:30
     */
    public $endPoint = 'ims.tencentcloudapi.com';

    /**
     * 设置client
     *
     * @return void
     * @date 2022-09-27 14:25:39
     */
    public function setClient()
    {
        $this->client = new ImsClient($this->credential, $this->region, $this->clientProfile);
    }
}