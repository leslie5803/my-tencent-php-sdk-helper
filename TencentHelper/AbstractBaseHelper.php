<?php

namespace TencentHelper;

/**
 * AbstractBaseHelper
 *
 * @date       2022-04-07 20:30:18
 */
abstract class AbstractBaseHelper {
    
    /**
     * 腾讯云配置
     *
     * @var [type]
     * @date       2022-04-06 17:42:33
     */
    protected static $setting = null;

    /**
     * 配置
     *
     * @var [type]
     * @date       2022-04-06 17:42:42
     */
    protected static $config = null;

    /**
     * 获取配置实例
     *
     * @return void
     * @date       2022-04-06 17:42:49
     */
    protected static function getSettingInstance()
    {
        if (self::$setting === null) {
            self::$setting = \Setting::get('tencent');
        }

        return self::$setting;
    }

    /**
     * 获取配置实例
     *
     * @date       2022-04-06 17:43:01
     */
    abstract protected static function getConfigInstance();

}