<?php

namespace TencentHelper\Ivld;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\QueryCallbackRequest;
use TencentHelper\AbstractIvld;

class Callback extends AbstractIvld {

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