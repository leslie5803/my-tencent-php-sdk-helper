<?php

namespace TencentHelper;

/**
 * 腾讯云配置接口
 *
 * @date       2022-01-06 14:51:44
 */
interface InterfaceConfig {

    /**
     * construct
     *
     * @param string $secId
     * @param string $secKey
     * @date       2022-01-06 15:11:58
     */
    public function __construct(string $secId = '', string $secKey = '');

    /**
     * 设置secret id
     *
     * @param string $secId
     *
     * @return void
     * @date       2022-01-06 14:59:06
     */
    public function setSecretId(string $secId);

    /**
     * 设置secret key
     *
     * @param string $secKey
     *
     * @return void
     * @date       2022-01-06 14:59:22
     */
    public function setSecretKey(string $secKey);

    /**
     * 获取secret id
     *
     * @return string
     * @date       2022-01-06 15:19:55
     */
    public function getSecretId(): string;

    /**
     * 获取secret key
     *
     * @return string
     * @date       2022-01-06 15:20:18
     */
    public function getSecretKey(): string;

}