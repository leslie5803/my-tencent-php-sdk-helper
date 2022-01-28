<?php

declare (strict_types = 1);

namespace TencentHelper\Vod;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Vod\V20180717\Models\{
    DeleteMediaRequest,
    DescribeMediaInfosRequest,
    DescribeTaskDetailRequest,
    ProcessMediaByProcedureRequest,
};
use TencentHelper\AbstractVod;

/**
 * 云点播
 *
 * @date       2022-01-06 21:25:34
 */
class Vod extends AbstractVod
{
    /**
     * 根据file_id获取视频信息
     *
     * @param array $fieldIds
     * @param string $subAppId
     *
     * @return array 数据可能为空
     *
     * Array
     * (
     *       [MediaInfoSet] => Array
     *       (
     *       )
     *       [NotExistFileIdSet] => Array
     *       (
     *           [0] => 5285890789178770799
     *       )
     *       [RequestId] => 212750c8-1bd6-4294-b5ce-f1dbbff9b335
     *   )
     *
     * @date       2022-01-26 16:11:15
     */
    public function getInfo(array $fileIds, string $subAppId = ''): array
    {
        try {
            if (empty($fileIds)) {
                throw new \InvalidArgumentException('$fileIds不能为空', 1);
            }

            $dmir          = new DescribeMediaInfosRequest();
            $dmir->FileIds = $fileIds;
            if ($subAppId) {
                $dmir->SubAppId = $subAppId;
            }
            $resp = $this->client->DescribeMediaInfos($dmir);

            return $this->getJson($resp);
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }

    /**
     * 删除
     *
     * @param string $fileId
     * @param string $SubAppId
     *
     * ```return
     * Array
     * (
     *    [RequestId] => adbead3e-60ff-4d00-9c6d-5c1f79978440
     * )
     * ```
     *
     * @return string
     * @date       2022-01-26 18:54:06
     */
    public function delete(string $fileId, string $SubAppId = ''): string
    {
        $dr = new DeleteMediaRequest();

        $dr->FileId = $fileId;
        if ($SubAppId) {
            $dr->SubAppId = $SubAppId;
        }

        return $this->getJson($this->client->DeleteMedia($dr))['RequestId'];
    }

    /**
     * 获取任务处理信息
     *
     * @param string $taskid
     * @param string $SubAppId
     *
     * @return void
     * @date       2022-01-26 18:58:44
     */
    public function getTaskInfo(string $taskid, $SubAppId = '')
    {
        try {
            $req    = new DescribeTaskDetailRequest();
            $params = [
                'TaskId' => $taskid,
            ];
            if ($SubAppId) {
                $params['SubAppId'] = $SubAppId;
            }
            $params = json_encode($params);
            $req->fromJsonString($params);

            return $this->getJson($this->client->DescribeTaskDetail($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }

    /**
     * 使用任务流处理视频
     *
     * @param string $fileId
     * @param string $procedure
     * @param string $SubAppId
     *
     * @return string
     * @date       2022-01-26 20:53:57
     */
    public function ProcessMediaByProcedure(string $fileId, string $procedure, $SubAppId='', string $sessionContext = ''): string
    {
        $req = new ProcessMediaByProcedureRequest();

        $params = [
            'FileId' => $fileId,
            'ProcedureName' => $procedure
        ];

        if (!empty($SubAppId)) {
            $params['SubAppId'] = $SubAppId;
        }

        if(!empty($sessionContext)) {
            $params['SessionContext'] = $sessionContext;
        }

        $req->fromJsonString(json_encode($params));
        
        return $this->getJson($this->client->ProcessMediaByProcedure($req))['TaskId'];
    }
}
