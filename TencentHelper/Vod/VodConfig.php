<?php

namespace TencentHelper\Vod;

use TencentHelper\AbstractConfig;

/**
 * 配置
 *
 * @date       2022-01-06 14:51:44
 */
final class VodConfig extends AbstractConfig {

    /**
     * 云点播播放鉴权key
     *
     * 云点播-域名管理-设置-访问控制
     *
     * @var string
     * @date       2022-01-06 14:57:10
     */
    private $URL_ENCODE_KEY = '';

    public function __construct(string $secId = '', string $secKey = '', string $urlKey = '')
    {
        $this->SEC_ID = $secId;
        $this->SEC_KEY = $secKey;
        $this->URL_ENCODE_KEY = $urlKey;
    }

    /**
     * 设置URL防盗链key
     *
     * @param string $key
     *
     * @return void
     * @date       2022-01-06 14:59:28
     */
    public function setUrlEncodeKey(string $key)
    {
        $this->URL_ENCODE_KEY = $key;
    }

    /**
     * 获取URL防盗链key
     *
     * @return string
     * @date       2022-01-06 16:04:37
     */
    public function getUrlEncodeKey(): string
    {
        return $this->URL_ENCODE_KEY;
    }
}