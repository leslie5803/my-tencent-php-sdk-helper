<?php

declare(strict_types=1);

namespace TencentHelper\Cme;

use TencentCloud\Cme\V20191029\Models\CreateProjectRequest;
use TencentCloud\Cme\V20191029\Models\DescribePlatformsRequest;
use TencentCloud\Cme\V20191029\Models\DescribeTaskDetailRequest;
use TencentCloud\Cme\V20191029\Models\Entity;
use TencentCloud\Cme\V20191029\Models\ExportVideoEditProjectRequest;
use TencentCloud\Cme\V20191029\Models\ImportMediaToProjectRequest;
use TencentCloud\Cme\V20191029\Models\VODExportInfo;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentHelper\AbstractCme;

/**
 * 智能创作
 *
 * @date 2023-02-28 11:07:24
 */
class Cme extends AbstractCme {

    /**
     * 创建项目
     *
     * @link https://cloud.tencent.com/document/product/1156/65100#CreateProject
     * @param  string $name
     * @date 2023-02-28 11:10:10
     */
    public function create(string $name, int $userid, string $aspect = '16:9', string $platform = '') {
        try {
            $req = new CreateProjectRequest();
            
            $params['Name'] = $name;
            $params['Category'] = 'VIDEO_EDIT';
            $params['AspectRatio'] = $aspect;
            $params['Platform'] = $platform;
            $params['Mode'] = 'Default';

            $owner = new Entity();
            $owner->setId((string)$userid);
            $owner->setType('PERSON');

            $params['Owner'] = $owner;

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->CreateProject($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 导入媒体
     *
     * @param   $id
     * @param   $fileId
     * @param   $platform
     * @date 2023-02-28 11:48:35
     */
    public function importMedia($id, $fileId, $platform) {
        try {
            $req = new ImportMediaToProjectRequest();

            $params['Platform'] = $platform;
            $params['ProjectId'] = $id;
            $params['SourceType'] = 'VOD';
            $params['VodFileId'] = $fileId;
            $params['PreProcessDefinition'] = 10;

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ImportMediaToProject($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 导出媒体
     *
     * @param         $id
     * @param         $userid
     * @param  string $name
     * @param  string $platform
     * @date 2023-02-28 15:01:26
     */
    public function exportMedia($id, $userid, $name = '', $platform = '') {
        try {
            $req = new ExportVideoEditProjectRequest();

            $params['Platform'] = $platform;
            $params['Definition'] = 10;
            $params['ProjectId'] = $id;
            $params['ExportDestination'] = 'VOD';

            $info = new VODExportInfo();
            if($name) {
                $info->setName($name);
            }else {
                $info->setName(md5(uniqid('', true) . microtime(true)));
            }

            $params['VODExportInfo'] = $info;
            $params['Operator'] = (string)$userid;

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->ExportVideoEditProject($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 获取任务结果
     *
     * @param         $userid
     * @param         $taskId
     * @param  string $platform
     *
     * @return array
     * @date 2023-02-28 15:05:41
     */
    public function getTask($userid, $taskId, $platform = ''):array {
        try{
            $req = new DescribeTaskDetailRequest();

            $params['Platform'] = $platform;
            $params['TaskId'] = $taskId;
            $params['Operator'] = (string)$userid;

            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->DescribeTaskDetail($req));
        }catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 查询平台列表
     *
     * @date 2023-03-01 17:14:33
     */
    public function queryPlatform() {
        try {
            $req = new DescribePlatformsRequest();

            return $this->getJson($this->client->DescribePlatforms($req));
        }catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}