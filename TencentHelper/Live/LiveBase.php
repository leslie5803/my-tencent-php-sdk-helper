<?php

namespace TencentHelper\Live;

use TencentHelper\AbstractLive;

/**
 * 直播基础类
 *
 * @date       2022-03-09 16:37:25
 */
class LiveBase extends AbstractLive {

    
    /**
     * 配置
     *
     * @var [type]
     * @date       2022-03-08 09:57:49
     */
    public $config;

    /**
     * 流名称
     *
     * @var string
     * @date       2022-03-08 11:23:18
     */
    public $streamName = '';

    /**
     * construct
     *
     * @param LiveConfig $config
     * @date       2022-03-08 10:28:32
     */
    public function __construct(LiveConfig $config, string $stream = '')
    {
        parent::__construct($config);
        
        $this->config = $config;
        $this->streamName = $stream;
    }

    /**
     * 设置流名称
     *
     * @param string $name
     *
     * @return self
     * @date       2022-03-11 14:42:54
     */
    public function setStreamName(string $name): self
    {
        $this->streamName = $name;

        return $this;
    }
}