<?php

namespace TencentHelper\Live;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\Models\{
    CreateLiveTranscodeRuleRequest,
    CreateLiveTranscodeTemplateRequest,
    DeleteLiveTranscodeTemplateRequest,
    DescribeLiveTranscodeTemplateRequest,
    ModifyLiveTranscodeTemplateRequest
};

/**
 * 转码
 *
 * @date       2022-03-09 16:19:18
 */
final class Transcode extends LiveBase
{

    /**
     * 创建直播转码模板
     *
     * @param string $name           模板名称
     * @param int $VideoBitrate      视频码率
     * @param string $Vcodec         视频编码方式
     * @param int $height            高,范围[0-3000],数值必须是2的倍数，0是原始高度,极速高清模板（AiTransCode = 1 的时候）必须传
     * @param int $AiTransCode
     * @param int $ShortEdgeAsHeight
     * @param int $BitrateToOrig
     * @param int $HeightToOrig
     * @param int $FpsToOrig
     * @param int $Fps
     * @param int $Gop
     * @param string $Profile
     * @param string $Description
     *
     * @return int
     * @date       2022-03-09 16:18:06
     */
    public function create(
        string $name,
        int $VideoBitrate = 0,
        string $Vcodec = 'origin',
        int $height = 0,
        int $AiTransCode = 0,
        int $ShortEdgeAsHeight = 0,
        int $BitrateToOrig = 0,
        int $HeightToOrig = 0,
        int $FpsToOrig = 0,
        int $Fps = 0,
        int $Gop = 0,
        string $Profile = '',
        string $Description = ''
    ): int
    {
        try {
            $req    = new CreateLiveTranscodeTemplateRequest();

            $params = $this->setParams(
                $name,
                $VideoBitrate,
                $Vcodec,
                $AiTransCode,
                $height,
                $ShortEdgeAsHeight,
                $BitrateToOrig,
                $HeightToOrig,
                $FpsToOrig,
                $Fps,
                $Gop,
                $Profile,
                $Description
            );

            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->CreateLiveTranscodeTemplate($req))['TemplateId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    /**
     * 修改转码模板配置
     *
     * @param [type] $template_id
     * @param [type] $name
     * @param int $VideoBitrate
     * @param string $Vcodec
     * @param string $Description
     * @param int $ShortEdgeAsHeight
     * @param [type] $resolution
     * @param int $Fps
     * @param int $Gop
     * @param string $Profile
     * @param int $BitrateToOrig
     * @param int $HeightToOrig
     * @param int $FpsToOrig
     * @param int $AiTransCode
     *
     * @return string
     * @date       2022-03-09 16:20:22
     */
    public function modify(
        int $template_id,
        int $VideoBitrate = 0,
        string $Vcodec = '',
        int $AiTransCode = 0,
        int $height = 0,
        int $ShortEdgeAsHeight = 0,
        int $BitrateToOrig = 0,
        int $HeightToOrig = 0,
        int $FpsToOrig = 0,
        int $Fps = 0,
        int $Gop = 0,
        string $Profile = '',
        string $Description = ''
    ): string
    {
        try {
            $req = new ModifyLiveTranscodeTemplateRequest();

            $params = $this->setParams(
                '',
                $VideoBitrate,
                $Vcodec,
                $AiTransCode,
                $height,
                $ShortEdgeAsHeight,
                $BitrateToOrig,
                $HeightToOrig,
                $FpsToOrig,
                $Fps,
                $Gop,
                $Profile,
                $Description
            );

            // 名称不可修改
            unset($params['TemplateName']);

            $params['TemplateId'] = intval($template_id);

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ModifyLiveTranscodeTemplate($req))['RequestId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    /**
     * 直播转码模板参数处理
     *
     * @param string $name           模板名称
     * @param int $VideoBitrate      视频码率
     * @param string $Vcodec         视频编码
     * @param int $AiTransCode       是否是极速高清模板
     * @param int $height            高
     * @param int $ShortEdgeAsHeight 是否以短边作为高度
     * @param int $BitrateToOrig     当设置的码率>原始码率时，是否以原始码率为准
     * @param int $HeightToOrig      当设置的高度>原始高度时，是否以原始高度为准
     * @param int $FpsToOrig         当设置的帧率>原始帧率时，是否以原始帧率为准
     * @param int $Fps               帧率
     * @param int $Gop               关键帧间隔
     * @param string $Profile        编码质量
     * @param string $Description    模板描述
     *
     * @return array
     * @date       2022-03-10 17:57:39
     */
    private function setParams(
        string $name,
        int $VideoBitrate = 0,
        string $Vcodec = 'origin',
        int $AiTransCode = 0,
        int $height,
        int $ShortEdgeAsHeight = 0,
        int $BitrateToOrig = 0,
        int $HeightToOrig = 0,
        int $FpsToOrig = 0,
        int $Fps = 0,
        int $Gop = 0,
        string $Profile = 'baseline',
        string $Description = ''
    ): array
    {
        $params = [
            'TemplateName'      => $name, //模板名称
            'VideoBitrate'      => $VideoBitrate, //视频码率。范围：0kbps - 8000kbps。
        ];

        if($Vcodec) {
            $params['Vcodec'] = $Vcodec;
        }

        if($AiTransCode) {
            $params['AiTransCode'] = $AiTransCode;
            $params['Height'] = $height;
        } else if($height) {
            $params['Height'] = $height;
        }

        if($BitrateToOrig) {
            $params['BitrateToOrig'] = $BitrateToOrig;
        }

        if($HeightToOrig) {
            $params['HeightToOrig'] = $HeightToOrig;
        }

        if($FpsToOrig) {
            $params['FpsToOrig'] = $FpsToOrig;
        }

        if($ShortEdgeAsHeight) {
            $params['ShortEdgeAsHeight'] = $ShortEdgeAsHeight;
        }

        if($Profile) {
            $params['Profile'] = $Profile;
        }

        if ($Fps) {
            $params['Fps'] = intval($Fps); // 帧率，默认0。范围0-60fps
        }

        if ($Gop) {
            $params['Gop'] = intval($Gop); //关键帧间隔，单位：秒。默认原始的间隔范围2-6
        }

        if($Description) {
            $params['Description'] = $Description;
        }

        return $params;
    }

    /**
     * 删除直播转码模板
     *
     * @param [type] $template_id
     *
     * @return string
     * @date       2022-03-09 16:26:02
     */
    public function delete($template_id): string
    {
        try {
            $req                  = new DeleteLiveTranscodeTemplateRequest();
            $params['TemplateId'] = intval($template_id);
            $params               = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->DeleteLiveTranscodeTemplate($req))['RequestId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    /**
     * 创建转码规则
     *
     * @param [type] $TemplateId 指定已有的模板Id
     * @param [type] $AppName 推流路径，与推流和播放地址中的AppName保持一致。如果只绑定域名，则此处填空
     *
     * @return string
     * @date       2022-03-09 16:25:17
     */
    public function bindToStream($TemplateId, $AppName = 'live'): string
    {
        try {
            $req    = new CreateLiveTranscodeRuleRequest();
            $params = [
                'DomainName' => $this->config->getPlayDomain(),
                'AppName'    => $AppName,
                'StreamName' => $this->streamName,
                'TemplateId' => intval($TemplateId),
            ];
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->CreateLiveTranscodeRule($req))['RequestId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    /**
     * 获取单个直播转码模板
     *
     * @param [type] $TemplateId
     *
     * @return array
     * @date       2022-03-09 16:25:00
     */
    public function get(int $TemplateId)
    {
        try {
            $req                  = new DescribeLiveTranscodeTemplateRequest();
            $params['TemplateId'] = $TemplateId;
            $params               = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->DescribeLiveTranscodeTemplate($req))['Template'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

}
