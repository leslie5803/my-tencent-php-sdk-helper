<?php

declare (strict_types = 1);

namespace TencentHelper\Vod;

use TencentCloud\Common\Exception\TencentCloudSDKException;

use TencentCloud\Vod\V20180717\Models\{
    CreateWatermarkTemplateRequest,
    DeleteMediaRequest,
    DeleteWatermarkTemplateRequest,
    DescribeMediaInfosRequest,
    DescribeTaskDetailRequest,
    DescribeWatermarkTemplatesRequest,
    ForbidMediaDistributionRequest,
    ModifyMediaInfoRequest,
    ModifyWatermarkTemplateRequest,
    ProcessMediaByProcedureRequest,
    ProcessMediaRequest,
    SearchMediaRequest,
    SimpleHlsClipRequest
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
    public function getVodInfo(array $fileIds): array
    {
        try {
            if (empty($fileIds)) {
                throw new \InvalidArgumentException('$fileIds不能为空', 1);
            }

            $dmir          = new DescribeMediaInfosRequest();
            $dmir->FileIds = $fileIds;
            $dmir->SubAppId = $this->config->getSubAppId();

            return $this->getJson($this->client->DescribeMediaInfos($dmir));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 删除
     *
     * @param string $fileId
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
    public function delete(string $fileId, string $type = 'OriginalFiles', $definition = 0): string
    {
        try{
            $dr = new DeleteMediaRequest();

            $dr->FileId = $fileId;
            $dr->SubAppId = $this->config->getSubAppId();
            $params = array(
                "FileId" => $fileId,
                "DeleteParts" => array(
                    array(
                        "Type" => $type,
                        "Definition" => $definition
                    )
                )
            );

            if($this->config->getSubAppId()) {
                $params['SubAppId'] = $this->config->getSubAppId();
            }

            $dr->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DeleteMedia($dr))['RequestId'];
        }catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因: %s', $e->__toString()), 1);
        }
    }

    /**
     * 获取任务处理信息
     *
     * @param string $taskid
     *
     * @return void
     * @date       2022-01-26 18:58:44
     */
    public function getTaskInfo(string $taskid)
    {
        try {
            $req    = new DescribeTaskDetailRequest();
            $params = [
                'TaskId' => $taskid,
            ];
            $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeTaskDetail($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 使用任务流处理视频
     *
     * @param string $fileId
     * @param string $procedure
     *
     * @return string
     * @date       2022-01-26 20:53:57
     */
    public function ProcessMediaByProcedure(string $fileId, string $procedure, string $sessionContext = ''): string
    {
        $req = new ProcessMediaByProcedureRequest();

        $params = [
            'FileId'        => $fileId,
            'ProcedureName' => $procedure,
        ];

        $params['SubAppId'] = $this->config->getSubAppId();

        if (!empty($sessionContext)) {
            $params['SessionContext'] = $sessionContext;
        }

        $req->fromJsonString(json_encode($params));

        return $this->getJson($this->client->ProcessMediaByProcedure($req))['TaskId'];
    }

    /**
     * 创建水印模板
     *
     * @param [type] $watermarkurl
     * @param [type] $watermark_position
     * @param [type] $name
     *
     * @return array
     * @date       2022-02-16 21:28:51
     */
    public function createWatermarkTemplate($watermarkurl, $watermark_position, $name)
    {
        try {
            $req = new CreateWatermarkTemplateRequest();

            $params         = $this->watermark($watermarkurl, $watermark_position, $name);
            $params['Type'] = 'image';
            $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->CreateWatermarkTemplate($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 水印
     *
     * @param [type] $watermarkurl
     * @param [type] $watermark_position
     * @param [type] $name
     *
     * @return array
     * @date       2022-02-17 15:09:23
     */
    private function watermark($watermarkurl, $watermark_position, $name)
    {
        $water_image      = str_replace(UPLOAD_URL, UPLOAD_PATH, $watermarkurl);
        $watermarkinfo    = getimagesize($water_image);
        $watermark_width  = $watermarkinfo[0];
        $watermark_height = $watermarkinfo[1];

        $wmwidth  = 80;
        $wmheight = 80;
        $lorbju   = 5;
        switch ($watermark_position) {
            case 1:
                $x = $lorbju;
                $y = $lorbju;
                break;
            case 2:
                $x = $wmwidth / 2;
                $y = $lorbju;
                break;
            case 3:
                $x = $wmwidth - 1;
                $y = $lorbju;
                break;
            case 4:
                $x = $lorbju;
                $y = $wmheight / 2;
                break;
            case 5:
                $x = $wmwidth / 2;
                $y = $wmheight / 2;
                break;
            case 6:
                $x = $wmwidth;
                $y = $wmheight / 2;
                break;
            case 7:
                $x = $lorbju;
                $y = $wmheight - 1;
                break;
            case 8:
                $x = $wmwidth / 2;
                $y = $wmheight - 1;
                break;
            default:
                $x = $wmwidth - 1;
                $y = $wmheight - 1;
        }
        $base64_img = base64_encode(file_get_contents($water_image));

        $params = [
            'Name'             => $name, //水印模板名称，长度限制：64 个字符。
            'CoordinateOrigin' => 'TopLeft', //原点位置，可选值：TopLeft：表示坐标原点位于视频图像左上角，水印原点为图片或文字的左上角；
            'XPos'             => $x . '%', //水印类型，可选值：image：图片水印；text：文字水印。
            'YPos'             => $y . '%', //水印类型，可选值：image：图片水印；text：文字水印。
            'ImageTemplate'    => [
                'ImageContent' => $base64_img, //水印图片 Base64 编码后的字符串。支持 jpeg、png 图片格式。
                'Width'        => $watermark_width . 'px', //水印的宽度。支持 %、px 两种格式
                'Height'       => $watermark_height . 'px', //水印的高度。支持 %、px 两种格式
            ], //图片水印模板，当 Type 为 image，该字段必填。
        ];
        return $params;
    }

    /**
     * 删除水印模板
     *
     * @param [type] $Definition
     *
     * @return array
     * @date       2022-02-17 14:54:12
     */
    public function deleteWatermarkTemplate($Definition)
    {
        try {
            $req = new DeleteWatermarkTemplateRequest();

            $params = [
                'Definition' => (int) $Definition, //水印模板唯一标识。
            ];
            $params['SubAppId'] = $this->config->getSubAppId();
            
            $req->fromJsonString(json_encode($params));

            $this->getJson($this->client->DeleteWatermarkTemplate($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 获取水印列表
     *
     * @return array
     * @date       2022-02-17 15:06:00
     */
    public function getWatermarkTemplateList()
    {
        try {
            $req    = new DescribeWatermarkTemplatesRequest();
            $params = array(

            );
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeWatermarkTemplates($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 编辑模板
     *
     * @param [type] $Definition
     * @param [type] $watermarkurl
     * @param [type] $watermark_position
     * @param [type] $name
     * @return array
     * @date       2022-02-17 16:12:23
     */
    public function modifyWatermarkTemplate($Definition, $watermarkurl, $watermark_position, $name)
    {
        try {
            $req = new ModifyWatermarkTemplateRequest();

            $params = $this->watermark($watermarkurl, $watermark_position, $name);
            $params['Definition'] = (int)$Definition;
            $params['SubAppId'] = $this->config->getSubAppId();
            
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ModifyWatermarkTemplate($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 视频处理
     *
     * @param string $fileId 视频ID
     * @param array $trans 转码
     * @param array $cover 封面图
     * @param integer $aiCheck ai审核
     * @return string 任务ID
     */
    public function process(string $fileId, array $trans = [], array $cover = [], int $watermark = 0, int $aiCheck = 0, string $ctx = '', int $aiAnalysis = 0, int $aiRecognition = 0): string
    {
        try {
            $req = new ProcessMediaRequest();
    
            $params = array(
                "FileId" => $fileId,
            );

            if($trans) {
                foreach($trans as $def) {
                    $trans = array(
                        "Definition" => $def,
                        "WatermarkSet" => [
                            [
                                "Definition" => $watermark
                            ]
                        ]
                    );

                    $params['MediaProcessTask']['TranscodeTaskSet'][] = $trans;
                }
            }

            if($cover) {
                foreach($cover as $def) {
                    $params['MediaProcessTask']['CoverBySnapshotTaskSet'][] = [
                        'Definition' => $def,
                        'PositionType' => 'Time',
                        'PositionValue' => 0
                    ];
                }
            }

            if($ctx) {
                $params['SessionContext'] = $ctx;
            }

            if($aiCheck) {
                $params['AiContentReviewTask'] = [
                    "Definition" => $aiCheck
                ];
            }

            if($aiAnalysis) {
                $params['AiAnalysisTask'] = [
                    "Definition" => $aiAnalysis
                ];
            }

            if($aiRecognition) {
                $params['AiRecognitionTask'] = [
                    "Definition" => $aiRecognition
                ];
            }

            $this->config->getSubAppId() && $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));
        
            return $this->getJson($this->client->ProcessMedia($req))['TaskId'];
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
        
    }

    /**
     * hls裁剪
     *
     * @param string $url
     * @param int $start
     * @param int $end
     * @param int $save
     * @param string $ctxt
     * @param string $process
     *
     * @return array
     * @date 2022-08-24 14:17:27
     */
    public function hlsClip(string $url, int $start = 0, int $end = 0, $save = 0, string $ctxt = '', string $process = ''): array{
        try {
            $req = new SimpleHlsClipRequest();
            
            $params = array(
                "Url" => $url
            );

            if($start) {
                $params['StartTimeOffset'] = $start;
            }

            if($end) {
                $params['EndTimeOffset'] = $end;
            }

            if($save) {
                $params['IsPersistence'] = $save;
                if($process) {
                    $params['Procedure'] = $process;
                    if($ctxt) {
                        $params['SessionContext'] = $ctxt;
                    }
                }

                if($ctxt) {
                    $params['SourceContext'] = $ctxt;
                }

            }


            $this->config->getSubAppId() && $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));
        
            return $this->getJson($this->client->SimpleHlsClip($req));
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 搜索
     *
     * @param array $fileIds
     * @param array $names
     * @param array $classIds
     * @param array $categories
     * @param array $createTime
     * @param array $sort
     * @param int $offset
     * @param int $limit
     *
     * @return array
     * @date 2022-10-09 11:07:48
     */
    public function search(
        array $fileIds = [],
        array $names = [],
        array $classIds = [],
        array $categories = [],
        array $createTime = [],
        array $sort = [],
        int $offset = 0,
        int $limit = 10
    ): array {
        try {
            $req = new SearchMediaRequest();
            
            $params = array(
                "Offset" => $offset,
                "Limit" => $limit,
            );

            !empty($fileIds) && $params['FileIds'] = $fileIds;
            !empty($names) && $params['Names'] = $names;
            !empty($classIds) && $params['ClassIds'] = $classIds;
            !empty($categories) && $params['Categories'] = $categories;
            !empty($createTime) && $params['CreateTime'] = $createTime;
            !empty($sort) && $params['Sort'] = $sort;

            $this->config->getSubAppId() && $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));

            $resp = $this->client->SearchMedia($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因: %s', $e->__toString()), 1);
        }
    }

    /**
     * 修改媒体信息
     *
     * @param [type] $fileId
     * @param string $name
     * @param int $classId
     * @param string $expire
     *
     * @return void
     * @date 2022-10-09 14:28:12
     */
    public function modify($fileId, $name = '', int $classId = -1, string $expire = ''): void {
        try {
            $req = new ModifyMediaInfoRequest();
            
            $params = array(
                "FileId" => $fileId,
            );

            if(!empty($name)) {
                $params['Name'] = $name;
            }

            if($classId > -1) {
                $params['ClassId'] = $classId;
            }

            if(!empty($expire)) {
                $params['ExpireTime'] = $expire;
            }

            $this->config->getSubAppId() && $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));

            $resp = $this->client->ModifyMediaInfo($req);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因: %s', $e->__toString()), 1);
        }
    }

    /**
     * 禁播
     *
     * @param array $fileIds
     * @param string $operation
     *
     * @return array
     * @date 2022-10-09 17:04:46
     */
    public function disable(array $fileIds, $operation = 'forbid'): array {
        try {
            $req = new ForbidMediaDistributionRequest();

            
            $params = array(
                "FileIds" => $fileIds,
                "Operation" => $operation,
            );
            $this->config->getSubAppId() && $params['SubAppId'] = $this->config->getSubAppId();

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ForbidMediaDistribution($req))['NotExistFileIdSet'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因: %s', $e->__toString()), 1);
        }
    }
}
