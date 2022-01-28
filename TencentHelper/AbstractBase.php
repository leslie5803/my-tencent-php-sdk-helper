<?php

namespace TencentHelper;

use \TencentCloud\Common\Credential;
use \TencentCloud\Common\Profile\ClientProfile;
use \TencentCloud\Common\Profile\HttpProfile;

/**
 * Abstract base
 *
 * @date       2021-12-28 16:09:26
 */
abstract class AbstractBase
{

    /**
     * 接口地址
     *
     * @var string
     * @date       2021-12-24 10:22:28
     */
    public $endPoint = '';

    /**
     * 凭证
     *
     * @var [type]
     * @date       2021-12-24 10:46:10
     */
    protected $credential = null;

    /**
     * clientProfile
     *
     * @var [type]
     * @date       2021-12-24 10:46:19
     */
    protected $clientProfile = null;

    /**
     * 地域参数
     *
     * @var string
     * @date       2021-12-24 10:45:59
     */
    public $region = "ap-guangzhou";

    /**
     * 实例化对象
     *
     * @var [type]
     * @date       2021-12-24 10:47:12
     */
    public $client = null;

    /**
     * 配置
     *
     * @var [type]
     * @date       2022-01-26 18:45:36
     */
    public $config = null;

    /**
     * construct
     *
     * @param InterfaceConfig $config
     * @date       2022-01-26 18:45:59
     */
    public function __construct(InterfaceConfig $config)
    {
        $this->credential = new Credential($config->getSecretId(), $config->getSecretKey());

        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint($this->endPoint);

        $this->clientProfile = new ClientProfile();
        $this->clientProfile->setHttpProfile($httpProfile);

        $this->config = $config;
        $this->setClient();
    }

    /**
     * 每个子类实例不同的接口
     *
     * 如:
     * ```
     * NLP:
     * $this->client = new NlpClient();
     * vod:
     * $this->client = new VodClient();
     * ```
     *
     * @return void
     * @date       2021-12-24 11:22:08
     */
    abstract public function setClient();

    /**
     * 解析腾讯云结果json字符串
     *
     * @param [type] $resp
     *
     * @return array
     * @date       2022-01-26 16:14:23
     */
    public function getJson($resp): array
    {
        return json_decode($resp->toJsonString(), true);
    }
}
