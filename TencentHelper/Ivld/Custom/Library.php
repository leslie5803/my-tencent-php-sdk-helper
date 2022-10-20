<?php

namespace TencentHelper\Ivld\Custom;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\CreateCustomGroupRequest;
use TencentCloud\Ivld\V20210903\Models\DescribeCustomGroupRequest;
use TencentHelper\AbstractIvld;

/**
 * 人物库
 *
 * @date 2022-09-09 09:59:44
 */
class Library extends AbstractIvld {

    /**
     * 创建自定义人物库
     *
     * @param string $bucket 去掉http://或https://的bucket地址
     *
     * @return string
     * @date 2022-09-09 10:03:01
     */
    public function create(string $bucket): string{
        try {
            $req = new CreateCustomGroupRequest();
            
            $params = array(
                "Bucket" => $bucket
            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateCustomGroup($req);

            return $this->getJson($resp)['RequestId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 获取人物库信息
     *
     * @return array
     * @date 2022-09-26 16:20:00
     */
    public function info():array {
        try {
            $req = new DescribeCustomGroupRequest();
            
            $params = array(

            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeCustomGroup($req));
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}