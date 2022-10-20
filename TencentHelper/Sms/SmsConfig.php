<?php
declare(strict_types=1);

namespace TencentHelper\Sms;

use TencentHelper\AbstractConfig;

/**
 * 配置
 *
 * @date       2022-01-06 14:51:44
 */
final class SmsConfig extends AbstractConfig {

    /**
     * 短信应用ID
     *
     * @var string
     * @date 2022-06-29 15:35:10
     */
    private $appId = '';

    /**
     * 签名内容
     *
     * @var string
     * @date 2022-06-29 15:35:35
     */
    private $signName = '';

    /**
     * 模板ID
     *
     * @var int
     * @date 2022-06-29 15:36:05
     */
    private $templateId = 0;

    public function setAppId(string $id) {
        $this->appId = $id;

        return $this;
    }

    public function getAppId() {
        return $this->appId;
    }

    public function setSignName(string $name) {
        $this->signName = $name;

        return $this;
    }

    public function getSignName() {
        return $this->signName;
    }

    public function setTemplateId(string $id) {
        $this->templateId = $id;

        return $this;
    }

    public function getTemplateId() {
        return $this->templateId;
    }
}