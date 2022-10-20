<?php

namespace TencentHelper\Ivld;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\DeleteMediaRequest;
use TencentCloud\Ivld\V20210903\Models\DescribeMediaRequest;
use TencentCloud\Ivld\V20210903\Models\ImportMediaRequest;
use TencentHelper\AbstractIvld;

/**
 * 媒资相关
 *
 * @date 2022-09-01 14:50:03
 */
class Media extends AbstractIvld {

    /**
     * 导入视频
     *
     * @param string $url
     * @param string $name
     * @param string $label
     *
     * @return string
     * @date 2022-09-02 09:44:17
     */
    public function import(string $url, $name = '', $label = '', int $type = 2): string {
        try {
            $req = new ImportMediaRequest();
            
            $params = array(
                "URL" => $url,
                "Name" => $name,
                "Label" => $label,
                'WriteBackCosPath' => sprintf('https://%s-%s.cos.%s.myqcloud.com/', $this->config->getBucket(), $this->config->getAppId(), $this->config->getRegion()),
                'MediaType' => $type
            );

            $req->fromJsonString(json_encode($params));


            $resp = $this->getJson($this->client->ImportMedia($req));
            return $resp['MediaId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 删除媒体文件
     *
     * @param string $mediaId
     *
     * @return bool
     * @date 2022-09-22 11:38:00
     */
    public function delete(string $mediaId): bool{
        try {
            $req = new DeleteMediaRequest();
            
            $params = array(
                "MediaId" => $mediaId
            );
            $req->fromJsonString(json_encode($params));
        
            $resp = $this->client->DeleteMedia($req);
        
            return $this->getJson($resp)['RequestId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 获取媒体信息
     *
     * @param [type] $mediaId
     *
     * @return array
     * @date 2022-09-26 10:30:26
     */
    public function info($mediaId): array{
        try {
            $req = new DescribeMediaRequest();
            
            $params = array(
                "MediaId" => $mediaId
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeMedia($req));
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}