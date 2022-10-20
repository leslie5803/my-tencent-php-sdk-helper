<?php

namespace TencentHelper\Ivld\Custom;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\CreateCustomCategoryRequest;
use TencentHelper\AbstractIvld;

/**
 * 人物分类
 *
 * @date 2022-09-09 10:14:22
 */
class Category extends AbstractIvld {

    /**
     * 创建人物分类
     *
     * @param string $level1
     * @param string $level2
     *
     * @return string
     * @date 2022-09-09 10:14:36
     */
    public function create(string $level1, string $level2 = ''): string{
        try {
            $req = new CreateCustomCategoryRequest();
            
            $params = array(
                "L1Category" => $level1,
                "L2Category" => $level2
            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateCustomCategory($req);

            return $this->getJson($resp)['CategoryId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

}