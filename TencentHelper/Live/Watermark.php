<?php

namespace TencentHelper\Live;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\Models\{
    AddLiveWatermarkRequest,
    CreateLiveWatermarkRuleRequest,
    DeleteLiveWatermarkRequest,
    DeleteLiveWatermarkRuleRequest,
    DescribeLiveWatermarkRequest,
    DescribeLiveWatermarkRulesRequest,
    DescribeLiveWatermarksRequest,
    UpdateLiveWatermarkRequest
};

/**
 * 水印
 *
 * @date       2022-03-11 14:38:02
 */
final class Watermark extends LiveBase {

    
    /**
     * 获取水印列表
     *
     * @return array
     * @date       2022-03-09 11:13:57
     */
    public function list(): array
    {
        try{
            $req = new DescribeLiveWatermarksRequest();
            $req->fromJsonString(json_encode([]));

            return $this->getJson($this->client->DescribeLiveWatermarks($req))['WatermarkList'];
        } catch (TencentCloudSDKException $e){
            throw new \Exception($e, 1);
        }
    }

    /**
     * 创建直播水印
     *
     * @param [type] $PictureUrl
     * @param [type] $XPosition
     * @param [type] $YPosition
     * @param [type] $yun_width
     * @param [type] $yun_height
     * @param [type] $name
     *
     * @return int 水印ID
     * @date       2022-03-08 19:20:28
     */
    public function add($PictureUrl,$XPosition, $YPosition, $yun_width, $yun_height ,$name)
    {
        try {
            $req = new AddLiveWatermarkRequest();

            $params = $this->watermark($PictureUrl, $XPosition, $YPosition, $yun_width, $yun_height, $name);
            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->AddLiveWatermark($req))['WatermarkId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    
    /**
     * 水印模板
     *
     * @param [type] $PictureUrl
     * @param [type] $x
     * @param [type] $y
     * @param [type] $yun_width
     * @param [type] $yun_height
     * @param [type] $name
     *
     * @return void
     * @date       2022-03-09 11:38:44
     */
    private function watermark($PictureUrl, $x, $y, $yun_width, $yun_height, $name)
    {
        $request = [
            'PictureUrl'=>$PictureUrl, // 水印图片 URL。URL中禁止包含的字符：(){}$>`#"\'|
            'WatermarkName'=>$name, //水印名称。最长16字节。
            'XPosition'=>intval($x),//显示位置，X轴偏移，单位是百分比，默认 0。
            'YPosition'=>intval($y),//显示位置，Y轴偏移，单位是百分比，默认 0。
        ];
        if($yun_width != 0){
            $request['Width'] = intval($yun_width);//水印宽度，占直播原始画面高度百分比，建议高宽只设置一项，另外一项会自适应缩放，避免变形。默认原始高度。
        }
        if($yun_height != 0){
            $request['Height'] = intval($yun_height);//水印高度，占直播原始画面高度百分比，建议高宽只设置一项，另外一项会自适应缩放，避免变形。默认原始高度。
        }
        return $request;
    }

    

    /**
     * 更新水印
     *
     * @param [type] $WatermarkId
     * @param [type] $PictureUrl
     * @param [type] $XPosition
     * @param [type] $YPosition
     * @param [type] $yun_width
     * @param [type] $yun_height
     * @param [type] $name
     *
     * @return string
     * @date       2022-03-09 16:29:54
     */
    public function update($WatermarkId,$PictureUrl,$XPosition, $YPosition, $yun_width, $yun_height, $name): string
    {
        try{
            $req = new UpdateLiveWatermarkRequest();

            $params = $this->watermark($PictureUrl, $XPosition, $YPosition, $yun_width, $yun_height, $name);

            $params['WatermarkId'] = intval($WatermarkId);//水印模板唯一标识。
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->UpdateLiveWatermark($req))['RequestId'];
        } catch(TencentCloudSDKException $e) {
            throw new \Exception($e, 1);
        }
    }

    /**
     * 删除水印
     *
     * @param [type] $WatermarkId
     *
     * @return string
     * @date       2022-03-09 16:29:47
     */
    public function delete(int $WatermarkId): string
    {
        try{
            $req = new DeleteLiveWatermarkRequest();

            $params = [
                'WatermarkId'=> $WatermarkId,//水印模板唯一标识。
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteLiveWatermark($req))['RequestId'];
        } catch(TencentCloudSDKException $e) {
           throw new \Exception($e, 1);
        }
    }

    /**
     * 获取单条
     *
     * @param [type] $WatermarkId
     *
     * @return array
     * @date       2022-03-09 16:29:41
     */
    public function get($WatermarkId): array
    {
        try{
            $req = new DescribeLiveWatermarkRequest();
            $params = [
                'WatermarkId' => $WatermarkId,
            ];
            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->DescribeLiveWatermark($params))['Watermark'];
        } catch (TencentCloudSDKException $e){
            throw new \Exception($e, 1);
        }
    }


    /**
     * 创建水印规则
     *
     * @param [type] $TemplateId 水印Id，即调用AddLiveWatermark接口返回的WatermarkId
     * @param string $AppName 推流路径，与推流和播放地址中的AppName保持一致，默认为live
     *
     * @return string
     * @date       2022-03-09 16:29:03
     */
    public function bindToStream(string $TemplateId, $AppName = 'live')
    {
        try{
            $req = new CreateLiveWatermarkRuleRequest();
            $params = [
                'DomainName' => $this->config->getPushDomain(),
                'AppName' => $AppName,
                'StreamName' => $this->streamName,
                'TemplateId' => (int)$TemplateId
            ];
            $params = json_encode($params);

            $req->fromJsonString($params);

            return $this->getJson($this->client->CreateLiveWatermarkRule($req))['RequestId'];
        } catch (TencentCloudSDKException $e){
            throw new \Exception($e, 1);
        }
    }

    /**
     * 删除水印规则
     *
     * $config须调用setPushDomain
     *
     * @param string $AppName 推流路径。与推流和播放地址中的 AppName 保持一致，默认为live
     *
     * @return string
     * @date       2022-03-09 16:28:32
     */
    public function unBindFromStream($AppName = 'live')
    {
        try{
            $req = new DeleteLiveWatermarkRuleRequest();
            $params = [
                'DomainName' => $this->config->getPushDomain(),
                'AppName' => $AppName,
                'StreamName' => $this->streamName,
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteLiveWatermarkRule($req))['RequestId'];
        } catch (TencentCloudSDKException $e){
           throw new \Exception($e, 1);
        }
    }


    /**
     * 获取水印规则列表
     *
     * @return array
     * @date       2022-03-09 16:28:24
     */
    public function rules()
    {
        try{
            $req = new DescribeLiveWatermarkRulesRequest();
            $params = [];
            $params = json_encode($params);
            $req->fromJsonString($params);
            return $this->getJson($this->client->DescribeLiveWatermarkRules($req))['Rules'];
        } catch (TencentCloudSDKException $e){
            throw new \Exception($e, 1);
        }
    }
}