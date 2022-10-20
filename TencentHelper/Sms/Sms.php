<?php

namespace TencentHelper\Sms;

use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentHelper\AbstractSms;

/**
 * 短信
 *
 * @date 2022-10-20 09:35:19
 */
class Sms extends AbstractSms {


    /**
     * 发送短信
     *
     * @param array $phone
     * @param array $data
     *
     * @return array
     * @date 2022-06-29 15:42:28
     */
    public function send(array $phone, array $data = []){
        try{
            $req = new SendSmsRequest();
            $params = [
                "PhoneNumberSet" => array_map(function($tel){
                    return "+86" . $tel;
                }, $phone),
                'SmsSdkAppId' => $this->config->getAppId(),
                'SignName' => $this->config->getSignName(),
                'TemplateId' => $this->config->getTemplateId()
            ];
            if(!empty($params)) {
                $params['TemplateParamSet'] = array_map(function($item){
                    return (string)$item;
                }, $data);
            }
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->SendSms($req));
        }catch(\Exception $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}