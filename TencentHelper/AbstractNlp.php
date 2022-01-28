<?php

namespace TencentHelper;

use TencentCloud\Nlp\V20190408\NlpClient;

/**
 * Abstract NLP
 *
 * @date       2021-12-28 16:09:03
 */
abstract class AbstractNlp extends AbstractBase {
    
    /**
     * 接口地址
     *
     * @var string
     * @date       2021-12-28 16:08:53
     */
    public $endPoint = 'nlp.tencentcloudapi.com';

    /**
     * 实例对象
     *
     * @return void
     * @date       2021-12-24 11:17:52
     */
    public function setClient()
    {
        $this->client = new NlpClient($this->credential, $this->region, $this->clientProfile);
    }
}