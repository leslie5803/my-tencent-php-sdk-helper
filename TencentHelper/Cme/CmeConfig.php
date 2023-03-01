<?php
declare(strict_types=1);

namespace TencentHelper\Cme;

use TencentHelper\AbstractConfig;

/**
 * 配置
 *
 * @date       2022-01-06 14:51:44
 */
final class CmeConfig extends AbstractConfig {

    /**
     * 子应用ID
     *
     * @var string
     * @date       2022-03-22 16:25:29
     */
    private $SUB_APP_ID = 0;

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
}