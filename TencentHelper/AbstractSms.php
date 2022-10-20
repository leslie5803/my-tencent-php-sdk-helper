<?php

namespace TencentHelper;

use TencentCloud\Sms\V20210111\SmsClient;

/**
 * Abstract Sms
 *
 * @date       2021-12-28 16:09:03
 */
abstract class AbstractSms extends AbstractBase {
    
    /**
     * 接口地址
     *
     * @var string
     * @date       2021-12-28 16:08:53
     */
    public $endPoint = 'sms.tencentcloudapi.com';

    /**
     * 实例对象
     *
     * @return void
     * @date       2021-12-24 11:17:52
     */
    public function setClient()
    {
        $this->client = new SmsClient($this->credential, $this->region, $this->clientProfile);
    }
}