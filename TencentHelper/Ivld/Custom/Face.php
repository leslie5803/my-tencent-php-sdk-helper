<?php

namespace TencentHelper\Ivld\Custom;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\AddCustomPersonImageRequest;
use TencentCloud\Ivld\V20210903\Models\DeleteCustomPersonImageRequest;
use TencentHelper\AbstractIvld;

/**
 * 自定义人脸
 *
 * @date 2022-09-27 16:10:03
 */
class Face extends AbstractIvld {

    /**
     * 增加自定义人脸
     *
     * @param [type] $id
     * @param string $img
     * @param string $url
     *
     * @return void
     * @date 2022-09-27 16:10:15
     */
    public function add($id, $img = '', $url = ''){
        try {
            $req = new AddCustomPersonImageRequest();
            
            $params = array(
                "PersonId" => $id,
            );

            if($img) {
                $params['Image'] = $img;
            }
            
            if($url) {
                $params['ImageURL'] = $url;
            }

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->AddCustomPersonImage($req))['ImageInfo'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    public function delete($personId, $imageId): bool {
        try {
            $req = new DeleteCustomPersonImageRequest();
            
            $params = array(
                "PersonId" => $personId,
                "ImageId" => $imageId
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteCustomPersonImage($req))['ImageId'] === $imageId;
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}