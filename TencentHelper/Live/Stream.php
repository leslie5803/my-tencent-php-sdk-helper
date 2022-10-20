<?php

namespace TencentHelper\Live;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Live\V20180801\Models\{
    CreateLivePullStreamTaskRequest,
    DeleteLivePullStreamTaskRequest,
    DescribeLiveForbidStreamListRequest,
    DescribeLivePullStreamTasksRequest,
    DescribeLiveStreamStateRequest,
    DropLiveStreamRequest,
    ForbidLiveStreamRequest,
    ModifyLivePullStreamTaskRequest,
    ResumeLiveStreamRequest
};

/**
 * 直播流
 *
 * @date       2022-03-17 19:51:32
 */
final class Stream extends LiveBase
{

    /**
     * 禁推直播流
     *
     * @param [type] $StreamName
     * @param string $Reason
     *
     * @return void
     * @date       2022-03-16 21:13:41
     */
    public function forbid($Reason = '', $AppName = 'live')
    {
        try {
            $req    = new ForbidLiveStreamRequest();
            $params = [
                'AppName'    => $AppName,
                'DomainName' => $this->config->getPushDomain(),
                'StreamName' => $this->getStreamName(), //流名称
                // 'ResumeTime' => gmdate(DATE_ATOM, strtotime('+' . $day . ' day')), //恢复流的时间。UTC 格式，例如：2018-11-29T19:00:00Z。 1. 默认禁播7天，且最长支持禁播90天。
                'Reason'     => $Reason,
            ];

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ForbidLiveStream($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 恢复直播推流
     *
     * @param [type] $StreamName
     *
     * @return array
     * @date       2022-03-16 21:13:47
     */
    public function resume($AppName = 'live')
    {
        try {
            $req    = new ResumeLiveStreamRequest();
            $params = [
                'AppName'    => $AppName,
                'DomainName' => $this->config->getPushDomain(),
                'StreamName' => $this->getStreamName(),
            ];
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ResumeLiveStream($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 禁推流列表
     *
     * @param int $page
     * @param int $pageSize
     * @param string $streamName
     *
     * @return array
     * @date       2022-03-16 21:33:35
     */
    function list(int $page = 1, int $pageSize = 100)
    {
        try {
            $req    = new DescribeLiveForbidStreamListRequest();
            $params = [
                'PageNum'  => $page,
                'PageSize' => $pageSize,
            ];
            if ($this->streamName) {
                $params['StreamName'] = $this->getStreamName();
            }

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeLiveForbidStreamList($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 断流
     *
     * @param string $appName
     *
     * @return string
     * @date       2022-03-17 19:56:25
     */
    public function disconnect($appName = 'live')
    {
        try {
            $req = new DropLiveStreamRequest();

            $params = array(
                "StreamName" => $this->getStreamName(),
                "DomainName" => $this->config->getPushDomain(),
                "AppName"    => $appName,
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DropLiveStream($req))['RequestId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 查询流状态
     *
     * @param string $appName
     *
     * @return string
     * @date       2022-03-17 19:56:25
     */
    public function query($appName = 'live')
    {
        try {
            $req = new DescribeLiveStreamStateRequest();

            $params = array(
                "AppName"    => $appName,
                "DomainName" => $this->config->getPushDomain(),
                "StreamName" => $this->getStreamName(),
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeLiveStreamState($req)($req))['RequestId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 创建拉流转推流
     *
     * @param string $src
     * @param string $start
     * @param string $end
     * @param string $operator
     * @param string $url
     * @param string $backup
     * @param string $comment
     *
     * @return string
     * @date 2022-08-04 11:16:51
     */
    public function createPullLive($src, $domain, $app, $stream, $start, $end, $operator, $push = '', $backup = '', $comment = ''): string
    {
        try {
            $req = new CreateLivePullStreamTaskRequest();

            $params = array(
                "SourceType" => "PullLivePushLive",
                "SourceUrls" => array($src),
                'DomainName' => $domain,
                "AppName"    => $app,
                'StreamName' => $stream,
                'PushArgs'   => $push,
                "StartTime"  => $start,
                "EndTime"    => $end,
                "Operator"   => $operator,
            );
            if ($backup) {
                $params['BackupSourceType'] = 'PullLivePushLive';
                $params['BackupSourceUrl']  = $backup;
            }
            if ($comment) {
                $params['Comment'] = $comment;
            }
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->CreateLivePullStreamTask($req);

            return $this->getJson($resp)['TaskId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 查询拉流任务
     *
     * @param string $taskId
     * @param int $page
     * @param int $pcount
     *
     * @return array
     * @date 2022-08-05 11:42:54
     */
    public function queryPull($taskId = '', $page = 1, $pcount = 20)
    {
        try {
            $req = new DescribeLivePullStreamTasksRequest();

            $params = array(
                "TaskId"   => $taskId,
                "PageNum"  => $page,
                "PageSize" => $pcount,
            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->DescribeLivePullStreamTasks($req);

            return $this->getJson($resp);
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 更新拉流
     *
     * @param int $taskId
     * @param string $operator
     * @param array $data
     *
     * @return bool
     * @date 2022-08-05 14:08:42
     */
    public function updatePullLive(int $taskId, string $operator, array $data): bool
    {
        try {
            $req = new ModifyLivePullStreamTaskRequest();

            $params = array(
                "TaskId"   => (string) $taskId,
                "Operator" => $operator,
            );
            if ($data['src']) {
                $params['SourceUrls'] = [$data['src']];
            }
            if ($data['start']) {
                $params['StartTime'] = $data['start'];
            }
            if ($data['end']) {
                $params['EndTime'] = $data['end'];
            }
            if ($data['backup']) {
                $params['BackupSourceUrl'] = $data['backup'];
            }
            $req->fromJsonString(json_encode($params));

            return boolval($this->getJson($this->client->ModifyLivePullStreamTask($req))['RequestId']);
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 删除拉流任务
     *
     * @param int $taskId
     * @param string $operator
     *
     * @return string
     * @date 2022-08-05 16:13:14
     */
    public function delPullLive(int $taskId, string $operator): string{
        try {
            $req = new DeleteLivePullStreamTaskRequest();
            
            $params = array(
                "TaskId" => "$taskId",
                "Operator" => $operator
            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteLivePullStreamTask($req))['RequestId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}
