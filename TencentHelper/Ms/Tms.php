<?php

namespace TencentHelper\Ms;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Tms\V20201229\Models\TextModerationRequest;
use TencentHelper\AbstractTms;

/**
 * 文本内容安全
 *
 * @date 2022-09-27 14:28:05
 */
final class Tms extends AbstractTms {

    /**
     * 检查
     *
     * @return void
     * @date 2022-09-27 14:28:15
     */
    public function check(string $content, string $id, $type = '', array $user = [], array $device = []): array{
        try {
            $req = new TextModerationRequest();

            
            $params = array(
                "Content" => $content,
                'DataId' => $id
            );

            if($type) {
                $params['BizType'] = $type;
            }

            if(!empty($user)) {
                $params['User'] = $user;
            }

            if(!empty($device)) {
                $params['Device'] = $device;
            }
            $req->fromJsonString(json_encode($params));

            $resp = $this->getJson($this->client->TextModeration($req));

            return $resp;
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}