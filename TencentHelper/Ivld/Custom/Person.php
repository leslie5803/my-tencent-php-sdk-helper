<?php

namespace TencentHelper\Ivld\Custom;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\CreateCustomPersonRequest;
use TencentCloud\Ivld\V20210903\Models\DeleteCustomPersonRequest;
use TencentCloud\Ivld\V20210903\Models\UpdateCustomPersonRequest;
use TencentHelper\AbstractIvld;

class Person extends AbstractIvld {

    /**
     * 创建自定义人物
     *
     * @param string $name
     * @param string $intro
     * @param string $cate
     * @param string $image
     * @param string $url
     *
     * @return array
     * @date 2022-09-26 16:55:00
     */
    public function create(string $name, string $intro, string $cate, $image = '', string $url = ''): array{
        try {
            $req = new CreateCustomPersonRequest();
            
            $params = array(
                "Name" => $name,
                "BasicInfo" => $intro,
                "CategoryId" => $cate,
            );
            if($image) {
                $params['Image'] = $image;
            }

            if($url) {
                $params['ImageURL'] = $url;
            }

            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateCustomPerson($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 更新人物信息
     *
     * @param [type] $id
     * @param string $name
     * @param string $intro
     * @param string $cate
     *
     * @return string
     * @date 2022-09-26 16:50:03
     */
    public function update($id, $name = '', $intro = '', $cate = ''): string {
        try {
            $req = new UpdateCustomPersonRequest();
            
            $params = array(
                "PersonId" => $id,
                "Name" => $name,
                "BasicInfo" => $intro,
                "CategoryId" => $cate
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->UpdateCustomPerson($req))['PersonId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 删除人物
     *
     * @param [type] $id
     *
     * @return bool
     * @date 2022-09-26 16:57:19
     */
    public function delete($id):bool {
        try {
            $req = new DeleteCustomPersonRequest();
            
            $params = array(
                "PersonId" => $id
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteCustomPerson($req))['PersonId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}