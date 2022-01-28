<?php

namespace TencentHelper\Vod;

/**
 * 云点播签名
 *
 * @date       2022-01-26 20:24:27
 */
class Sign
{

    /**
     * 配置
     *
     * @var [type]
     * @date       2022-01-26 20:36:33
     */
    private $config = null;

    /**
     * Undocumented function
     *
     * @param VodConfig $config
     * @date       2022-01-26 20:40:12
     */
    public function __construct(VodConfig $config)
    {
        $this->config = $config;
    }

    /**
     * 获取上传签名
     *
     * @param string $procedure
     * @param string $sessionContext
     * @param int $expire
     *
     * @return void
     * @date       2022-01-26 20:48:24
     */
    public function getSignature(string $procedure = '', string $sessionContext = '', int $expire = 86400)
    {
        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + $expire; // 签名有效期

        // 向参数列表填入参数
        $arg_list = array(
            "secretId"         => $this->config->getSecretId(),
            "currentTimeStamp" => $current,
            "expireTime"       => $expired,
            "random"           => rand(),
        );

        // 云点播任务流名称
        if (!empty($procedure)) {
            $arg_list['procedure'] = $procedure;
        }

        // 自定义参数,根据业务需要使用
        if (!empty($sessionContext)) {
            $arg_list['sessionContext'] = $sessionContext;
        }

        // 计算签名
        $orignal = http_build_query($arg_list);

        return base64_encode(hash_hmac('SHA1', $orignal, $this->config->getSecretKey(), true) . $orignal);
    }

    /**
     * URL添加防盗链
     *
     * @param string $url
     * @param int $expire
     *
     * @return void
     * @date       2022-01-26 20:49:33
     * @link https://cloud.tencent.com/document/product/266/33469
     */
    public function urlEncode(string $url, int $expire = 864000)
    {
        $key  = $this->config->getUrlEncodeKey();
        $path = parse_url($url, PHP_URL_PATH);
        // 视频路径
        $dir = substr($path, 0, strrpos($path, '/') + 1);
        // 过期时间,小写16进制
        $t = strtolower(dechex(time() + $expire));
        // 试看时间,不填或者填0表示不试看（即返回完整视频）
        $exper = 0;
        // IP数量限制
        $rlimit = '';
        // 标识
        $us = dechex(microtime(true));
        // 签名
        $sign = md5($key . $dir . $t . $us);

        return $url . '?t=' . $t . '&us=' . $us . '&sign=' . $sign;
    }
}
