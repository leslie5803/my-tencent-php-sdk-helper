<?php
declare(strict_types=1);

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

    /**
     * 子应用ID
     *
     * @var string
     * @date       2022-03-22 16:25:29
     */
    private $SUB_APP_ID = 0;

    /**
     * 临时目录
     *
     * @var int
     * @date 2022-10-09 15:27:52
     */
    private $temporary_class = -1;


    /**
     * 设置URL防盗链key
     *
     * @param string $key
     *
     * @return self
     * @date       2022-01-06 14:59:28
     */
    public function setUrlEncodeKey(string $key): self
    {
        $this->URL_ENCODE_KEY = $key;

        return $this;
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

    /**
     * 获取子应用ID
     *
     * @return int
     * @date       2022-03-22 16:27:49
     */
    public function getSubAppId(): int
    {
        return $this->SUB_APP_ID;
    }

    /**
     * 获取子应用ID
     *
     * @param int $id
     *
     * @return self
     * @date       2022-03-22 16:31:28
     */
    public function setSubAppId(?int $id): self
    {
        $this->SUB_APP_ID = $id;

        return $this;
    }

    /**
     * 获取临时目录
     *
     * @return int
     * @date 2022-10-09 15:28:57
     */
    public function getTempClass(): int {
        return $this->temporary_class;
    }

    /**
     * 设置临时目录
     *
     * @param int $classId
     *
     * @return self
     * @date 2022-10-09 15:29:05
     */
    public function setTempClass(int $classId): self {
        $this->temporary_class = $classId;

        return $this;
    }
}