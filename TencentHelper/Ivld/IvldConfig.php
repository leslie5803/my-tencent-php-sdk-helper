<?php

namespace TencentHelper\Ivld;

use TencentHelper\AbstractConfig;

/**
 * 配置
 *
 * @date 2022-09-07 10:28:22
 */
final class IvldConfig extends AbstractConfig {

    /**
     * 视频智能标签在cos中的bucket,不包括后面的appid部分
     * 如:一个bucket名为:abc-23222205,则,这里应是abc
     *
     * @var string
     * @date 2022-09-07 10:10:44
     */
    private $bucket = '';

    /**
     * cos地域
     *
     * @var string
     * @date 2022-09-07 10:11:31
     */
    private $region = '';

    /**
     * appId
     *
     * @var string
     * @date 2022-09-07 10:36:11
     */
    private $appId = '';

    public function setBucket($bucket){
        $this->bucket = $bucket;

        return $this;
    }

    public function getBucket(){
        return $this->bucket;
    }

    public function setRegion($region){
        $this->region = $region;
        return $this;
    }

    public function getRegion(){
        return $this->region;
    }

    public function setAppId($appId){
        $this->appId = $appId;
        return $this;
    }

    public function getAppId(){
        return $this->appId;
    }
}