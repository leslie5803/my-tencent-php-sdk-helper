<?php

namespace TencentHelper\Live;

use TencentHelper\AbstractConfig;

/**
 * 云直播配置
 *
 * @date       2022-03-07 21:42:17
 */
final class LiveConfig extends AbstractConfig {

    /**
     * 保留时间,单位天
     *
     * @var int
     * @date       2022-03-08 09:56:11
     */
    private $keep = 7;

    /**
     * 播放域名
     *
     * @var string
     * @date       2022-03-08 10:07:09
     */
    private $play_domain = '';

    /**
     * 播放鉴权key
     *
     * @var string
     * @date       2022-03-08 10:08:49
     */
    private $play_key = '';

    /**
     * 过期时间
     *
     * @var string
     * @date       2022-03-08 10:30:11
     */
    private $expire = '';

    /**
     * 播放域名
     *
     * @var string
     * @date       2022-03-08 10:40:33
     */
    private $push_domain = '';

    /**
     * 推流鉴权key
     *
     * @var string
     * @date       2022-03-08 10:45:55
     */
    private $push_key = '';

    /**
     * 延迟播放
     *
     * @var int
     * @date       2022-04-07 21:26:54
     */
    private $play_delay = 0;


    /**
     * 设置保留时间
     *
     * @param int $days
     *
     * @return self
     * @date       2022-03-08 10:06:34
     */
    public function setKeep(int $days): self
    {
        $this->keep = $days;

        return $this;
    }

    /**
     * 获取保留时间
     *
     * @return int
     * @date       2022-03-08 10:10:52
     */
    public function getKeep(): int
    {
        return $this->keep;
    }

    /**
     * 设置播放域名
     *
     * @param string $domain
     *
     * @return self
     * @date       2022-03-08 10:07:56
     */
    public function setPlayDomain(string $domain): self
    {
        $this->play_domain = $domain;

        return $this;
    }

    /**
     * 获取播放域名
     *
     * @return string
     * @date       2022-03-08 10:11:32
     */
    public function getPlayDomain(): string
    {
        return $this->play_domain;
    }

    /**
     * 设置播放鉴权key
     *
     * @param string $key
     *
     * @return self
     * @date       2022-03-08 10:09:23
     */
    public function setPlayKey(string $key): self
    {
        $this->play_key = $key;

        return $this;
    }

    /**
     * 获取鉴权key
     *
     * @return string
     * @date       2022-03-08 10:12:21
     */
    public function getPlayKey(): string
    {
        return $this->play_key;
    }

    /**
     * 设置过期时间
     *
     * @param string $time
     *
     * @return self
     * @date       2022-03-08 14:53:09
     */
    public function setExpire(string $time): self
    {
        $this->expire = $time;

        return $this;
    }
    
    /**
     * 获取过期时间
     *
     * @return string
     * @date       2022-03-08 10:31:24
     */
    public function getExpire(): string
    {
        return $this->expire;
    }

    /**
     * 设置推流域名
     *
     * @param string $domain
     *
     * @return self
     * @date       2022-03-08 10:41:13
     */
    public function setPushDomain(string $domain): self
    {
        $this->push_domain = $domain;

        return $this;
    }

    /**
     * 获取推流域名
     *
     * @return string
     * @date       2022-03-08 10:41:55
     */
    public function getPushDomain(): string
    {
        return $this->push_domain;
    }

    /**
     * 设置推流鉴权key
     *
     * @param string $key
     *
     * @return self
     * @date       2022-03-08 10:46:51
     */
    public function setPushKey(string $key): self
    {
        $this->push_key = $key;

        return $this;
    }

    /**
     * 获取推流鉴权key
     *
     * @return string
     * @date       2022-03-08 10:47:29
     */
    public function getPushKey(): string
    {
        return $this->push_key;
    }

    /**
     * 设置延迟播放
     *
     * @param int $time
     *
     * @return self
     * @date       2022-04-07 21:28:05
     */
    public function setPlayDelay(int $time): self
    {
        $this->play_delay = $time;

        return $this;
    }

    /**
     * 获取延迟播放
     *
     * @return int
     * @date       2022-04-07 21:28:23
     */
    public function getPlayDelay(): int
    {
        return $this->play_delay;
    }
}