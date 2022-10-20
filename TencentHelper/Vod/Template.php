<?php

namespace TencentHelper\Vod;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\Models\CreateAIAnalysisTemplateRequest;
use TencentCloud\Vod\V20180717\Models\CreateAIRecognitionTemplateRequest;
use TencentHelper\AbstractVod;

class Template extends AbstractVod {

    public function recognition(){
        try {
            $req = new CreateAIRecognitionTemplateRequest();
            
            $params = array(
                "Name" => "test",
                "HeadTailConfigure" => array(
                    "Switch" => "on"
                ),
                "SegmentConfigure" => array(
                    "Switch" => "on"
                ),
                "FaceConfigure" => array(
                    "Switch" => "on",
                    "Score" => 80
                ),
                "OcrFullTextConfigure" => array(
                    "Switch" => "on"
                ),
                "OcrWordsConfigure" => array(
                    "Switch" => "on"
                ),
                "AsrFullTextConfigure" => array(
                    "Switch" => "on"
                ),
                "AsrWordsConfigure" => array(
                    "Switch" => "on"
                ),
                "ObjectConfigure" => array(
                    "Switch" => "on"
                )
            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateAIRecognitionTemplate($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    public function analysis(){
        try {
            $req = new CreateAIAnalysisTemplateRequest();
            
            $params = array(
                "Name" => "ff",
                "ClassificationConfigure" => array(
                    "Switch" => "on"
                ),
                "TagConfigure" => array(
                    "Switch" => "on"
                ),
                "CoverConfigure" => array(
                    "Switch" => "on"
                ),
                "FrameTagConfigure" => array(
                    "Switch" => "on"
                ),
                "HighlightConfigure" => array(
                    "Switch" => "on"
                )
            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateAIAnalysisTemplate($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}