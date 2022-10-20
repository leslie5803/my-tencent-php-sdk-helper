<?php

namespace TencentHelper\Ivld;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\QueryCallbackRequest;
use TencentHelper\AbstractIvld;

/**
 * 回调
 *
 * @date 2022-10-20 09:37:41
 */
class Callback extends AbstractIvld {

    /**
     * 查询回调配置
     *
     * @return void
     * @date 2022-10-20 09:38:32
     */
    public function query(){
        try {
            $req = new QueryCallbackRequest();
            
            $params = array(

            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->QueryCallback($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}