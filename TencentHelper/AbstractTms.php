<?php

namespace TencentHelper;

use TencentCloud\Tms\V20201229\TmsClient;

/**
 * 文本内容安全
 *
 * @date 2022-09-27 14:25:21
 */
abstract class AbstractTms extends AbstractBase {

    /**
     * end point
     *
     * @var string
     * @date 2022-09-27 14:25:30
     */
    public $endPoint = 'tms.tencentcloudapi.com';

    /**
     * 设置client
     *
     * @return void
     * @date 2022-09-27 14:25:39
     */
    public function setClient()
    {
        $this->client = new TmsClient($this->credential, $this->region, $this->clientProfile);
    }
}