<?php

declare(strict_types=1);

namespace TencentHelper;

use TencentCloud\Cme\V20191029\CmeClient;

abstract class AbstractCme extends AbstractBase {

    /**
     * 接口
     *
     * @var string
     * @date 2023-02-28 11:03:24
     */
    public $endPoint = 'cme.tencentcloudapi.com';

    /**
     * 智能创作实例
     *
     * @date 2023-02-28 11:04:27
     */
    public function setClient() {
        $this->client = new CmeClient($this->credential, $this->region, $this->clientProfile);
    }
}