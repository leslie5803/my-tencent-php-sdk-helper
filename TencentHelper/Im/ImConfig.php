<?php

namespace TencentHelper\Im;

/**
 * 云通讯IM配置
 *
 * @date       2022-04-06 14:05:55
 */
class ImConfig {

    /**
     * SDKAppID
     *
     * @var string
     * @date       2022-04-06 14:06:36
     */
    private $sdkAppId = '';

    /**
     * 密钥
     *
     * @var string
     * @date       2022-04-06 14:06:55
     */
    private $secret = '';

    /**
     * 管理员账号
     *
     * @var array
     * @date       2022-04-06 14:45:49
     */
    private $admin_accounts = [];

    /**
     * administrator usersig
     *
     * @var string
     * @date       2022-04-06 16:40:18
     */
    private $sig = '';

    public function __construct()
    {
        
    }

    /**
     * 设置SDKAppID
     *
     * @param string $id
     *
     * @return self
     * @date       2022-04-06 14:08:34
     */
    public function setSDKAppId(string $id): self
    {
        $this->sdkAppId = $id;

        return $this;
    }

    /**
     * 获取SDKAppID
     *
     * @return string
     * @date       2022-04-06 14:08:11
     */
    public function getSDKAppId(): string
    {
        return $this->sdkAppId;
    }

    /**
     * 设置IM密钥
     *
     * @return self
     * @date       2022-04-06 14:09:06
     */
    public function setIMKey(string $key): self
    {
        $this->secret = $key;
        
        return $this;
    }

    /**
     * 获取IM密钥
     *
     * @return string
     * @date       2022-04-06 14:10:03
     */
    public function getIMKey(): string
    {
        return $this->secret;
    }

    /**
     * 添加管理员账号
     *
     * @param [type] $account
     *
     * @return self
     * @date       2022-04-06 15:00:00
     */
    public function addAdmin($account): self
    {
        $this->admin_accounts[] = $account;

        return $this;
    }

    /**
     * 获取管理员账号
     *
     * @return array
     * @date       2022-04-06 15:00:08
     */
    public function getAdmin(): array
    {
        return $this->admin_accounts;
    }

    /**
     * 设置sig
     *
     * @param string $sig
     *
     * @return self
     * @date       2022-04-06 16:41:32
     */
    public function setSig(string $sig): self
    {
        $this->sig = $sig;

        return $this;
    }

    /**
     * 获取sig
     *
     * @return string
     * @date       2022-04-06 16:41:23
     */
    public function getSig(): string
    {
        return $this->sig;
    }
}