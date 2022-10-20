<?php

namespace TencentHelper\Live;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\Models\{
    CreateLiveRecordTemplateRequest,
    CreateRecordTaskRequest,
    DeleteRecordTaskRequest,
    DescribeLiveRecordRulesRequest,
    DescribeRecordTaskRequest,
    StopRecordTaskRequest
};

/**
 * 云直播录制
 *
 * @date       2022-03-09 18:41:40
 */
final class Record extends LiveBase {

    
    /**
     * 创建录制模板
     *
     * @param [type] $name
     * @param [type] $type
     *
     * @return void
     * @date       2022-03-09 18:47:00
     */
    public function createTemplate($name,$type)
    {
        try {
            $req = new CreateLiveRecordTemplateRequest();

            $params = [
                'TemplateName' => $name,
            ];
            $RecordParam = [
                'RecordInterval'=>7200,//录制间隔。单位秒，默认：1800。取值范围：300-7200。此参数对 HLS 无效
                'StorageTime'=>0,//录制存储时长。单位秒，取值范围： 0 - 93312000。0：表示永久存储。
                'Enable'=>1,//是否开启当前格式录制，默认值为0，0：否， 1：是。
            ];
            if($type == 'hls'){
                $params['HlsParam'] = $RecordParam;
                $params['HlsSpecialParam'] = ['FlowContinueDuration'=>300];
            }
            if($type == 'mp4'){
                $params['Mp4Param'] = $RecordParam;
            }
            if($type == 'aac'){
                $params['AacParam'] = $RecordParam;
            }
            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->CreateLiveRecordTemplate($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 创建录制任务
     *
     * @param [type] $StreamName
     * @param [type] $StartTime
     * @param [type] $EndTime
     * @param [type] $TemplateId
     * @param [type] $live_id
     *
     * @return string
     * @date       2022-03-09 18:47:07
     */
    public function createTask($StartTime,$EndTime,$TemplateId, $appName = 'live'):string
    {
        try {
            $req = new CreateRecordTaskRequest();

            $params = [
                'StreamName' => $this->getStreamName(),//流名称。
                'DomainName' => $this->config->getPushDomain(),//推流域名
                'AppName' => $appName,//推流路径。
                'EndTime' => $EndTime,//录制任务结束时间，Unix时间戳。设置时间必须大于StartTime，且不能超过从当前时刻开始24小时之内的时间。。
                'StartTime' => $StartTime,//录制任务开始时间，Unix时间戳。
                'StreamType' => 0,//推流类型，默认0。取值：0-直播推流。1-合成流
                'TemplateId' => (int)$TemplateId,//录制模板ID，CreateLiveRecordTemplate 返回值。如果不填或者传入错误ID，则默认录制HLS格式、永久存储。
                'Extension' => '',//扩展字段，默认空。
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->CreateRecordTask($req))['TaskId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 删除录制任务
     *
     * @param [type] $TaskId
     *
     * @return void
     * @date       2022-03-09 18:47:14
     */
    public function deleteTask($TaskId)
    {
        try {
            $req = new DeleteRecordTaskRequest();

            $params = [
                'TaskId' => $TaskId,//任务ID，CreateRecordTask返回。
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteRecordTask($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 终止录制任务
     *
     * @param [type] $TaskId
     *
     * @return void
     * @date       2022-03-09 18:47:21
     */
    public function stopTask($TaskId)
    {
        try {
            $req = new StopRecordTaskRequest();

            $params = [
                'TaskId' => $TaskId,//任务ID，CreateRecordTask返回。
            ];

            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->StopRecordTask($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 录制规则列表
     *
     *
     * @return array
     * @date       2022-03-09 18:47:21
     */
    public function rules()
    {
        try {
            $req = new DescribeLiveRecordRulesRequest();

            $params = [
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeLiveRecordRules($req))['Rules'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }



    /**
     * 录制任务列表
     *
     *
     * @return array
     * @date       2022-03-09 18:47:21
     */
    public function list(int $start, int $end, string $token = '')
    {
        try {
            $req = new DescribeRecordTaskRequest();
    
            $params = array(
                "DomainName" => $this->config->getPushDomain(),
                "StartTime" => $start,
                "EndTime" => $end,
                "ScrollToken" => $token
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeRecordTask($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    
}