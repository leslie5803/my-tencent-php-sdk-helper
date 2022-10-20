<?php

namespace TencentHelper\Im;

use GuzzleHttp\Client;

/**
 * IM
 *
 * @date       2022-04-06 14:18:18
 */
abstract class AbstractIm {

    /**
     * 域名
     *
     * @var string
     * @date       2022-04-06 14:14:31
     */
    public $domain = 'https://console.tim.qq.com';

    /**
     * 版本
     *
     * @var string
     * @date       2022-04-06 14:15:39
     */
    public $version = 'v4';

    /**
     * 服务
     *
     * @var string
     * @date       2022-04-06 14:15:31
     */
    public $service = '';

    /**
     * 配置
     *
     * @var \TencentHelper\Im\ImConfig|null
     * @date       2022-04-06 14:42:50
     */
    public $config = null;

    /**
     * 请求地址
     *
     * @var string
     * @date       2022-04-06 15:37:44
     */
    public $uri = '';

    /**
     * 接口名
     *
     * @var string
     * @date       2022-04-06 16:05:16
     */
    public $method = '';


    public function __construct(ImConfig $config)
    {
        $this->config = $config;
    }

    /**
     * 设置地址
     *
     * @param string $method
     *
     * @return self
     * @date       2022-04-06 15:13:02
     */
    public function setUri(string $method): self
    {
        $this->uri = $this->domain . '/' . $this->version . '/' . $this->service . '/' . $method;

        return $this;
    }

    /**
     * 获取地址
     *
     * @return string
     * @date       2022-04-06 15:13:16
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * 获取随机数,腾讯云限制最大4294967295
     *
     * @return int
     * @date       2022-04-06 16:59:36
     */
    public function getRandom(): int
    {
        return random_int(0, 21474836);
    }

    /**
     * 调用接口
     *
     * @param [type] $name
     * @param [type] $arguments
     *
     * @return void
     * @date       2022-04-06 16:53:38
     */
    public function __call($name, $arguments)
    {
        $this->setUri($name);

        $uri = $this->getUri()
            . '?sdkappid=' . $this->config->getSDKAppId()
            . '&identifier=' . $this->config->getAdmin()[0]
            . '&usersig=' . $this->config->getSig()
            . '&random=' . $this->getRandom()
            . '&contenttype=json';
        
        try{
            $response = json_decode(
                (new Client())->request('POST', $uri, ['json' => $arguments[0]['params'] ?? []])->getBody()->getContents(),
                true
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }

        if($response['ActionStatus'] === 'FAIL') {
            throw new \Exception($response['ErrorInfo'], $response['ErrorCode']);
        }


        return $response;
    }
}