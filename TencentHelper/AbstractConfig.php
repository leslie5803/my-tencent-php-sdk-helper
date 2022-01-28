<?php

namespace TencentHelper;

/**
 * 腾讯云配置
 *
 * @date       2022-01-06 15:01:59
 */
abstract class AbstractConfig implements InterfaceConfig
{

    /**
     * Secret id
     *
     * 访问管理-API密钥管理
     *
     * @var string
     * @date       2022-01-06 14:54:38
     */
    protected $SEC_ID  = '';

    /**
     * Secret key
     *
     * 访问管理-API密钥管理
     *
     * @var string
     * @date       2022-01-06 14:55:57
     */
    protected $SEC_KEY = '';

    /**
     * construct
     *
     * @param string $secId
     * @param string $secKey
     * @date       2022-01-06 16:03:30
     */
    public function __construct(string $secId = '', string $secKey = '')
    {
        $this->SEC_ID = $secId;
        $this->SEC_KEY = $secKey;
    }

    /**
     * 设置secret id
     *
     * @param string $secId
     *
     * @return void
     * @date       2022-01-06 14:59:06
     */
    public function setSecretId(string $secId)
    {
        $this->SEC_ID = $secId;
    }

    /**
     * 设置secret key
     *
     * @param string $secKey
     *
     * @return void
     * @date       2022-01-06 14:59:22
     */
    public function setSecretKey(string $secKey)
    {
        $this->SEC_KEY = $secKey;
    }

    /**
     * 获取secret id
     *
     * @return string
     * @date       2022-01-06 15:24:36
     */
    public function getSecretId(): string
    {
        return $this->SEC_ID;
    }

    /**
     * 获取secret key
     *
     * @return string
     * @date       2022-01-06 15:24:49
     */
    public function getSecretKey(): string
    {
        return $this->SEC_KEY;
    }

}
