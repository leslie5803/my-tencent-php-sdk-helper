<?php

namespace TencentHelper\Ms;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ims\V20201229\Models\ImageModerationRequest;
use TencentHelper\AbstractIms;

/**
 * 图片内容安全
 *
 * @date 2022-09-27 14:28:05
 */
final class Ims extends AbstractIms {

    /**
     * 检查
     *
     * @return array
     * @date 2022-09-27 14:28:15
     */
    public function check(string $content, $id, $type = '', array $user = [], array $device = []): array{
        try {
            $req = new ImageModerationRequest();

            
            $params = array(
                "FileContent" => $content,
                "Interval" => 3,
                "MaxFrames" => 600,
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

            $resp = $this->getJson($this->client->ImageModeration($req));

            return $resp;
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}