<?php

namespace TencentHelper\Ivld;

use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Ivld\V20210903\Models\CreateTaskRequest;
use TencentCloud\Ivld\V20210903\Models\DescribeTaskDetailRequest;
use TencentCloud\Ivld\V20210903\Models\DescribeTasksRequest;
use TencentHelper\AbstractIvld;

/**
 * 标签
 *
 * @date 2022-10-20 09:38:49
 */
class LabelTask extends AbstractIvld {

    /**
     * 创建任务
     *
     * @param [type] $id
     * @param [type] $info
     * @param string $name
     * @param string $label
     * @param string $callback
     *
     * @return string
     * @date 2022-09-06 17:38:59
     */
    public function create($id, $info, $name = '', $label = '', $callback = ''): string{
        try {
            $req = new CreateTaskRequest();
            
            $params = array(
                "MediaId" => $id,
                "MediaPreknownInfo" => $info
            );

            if($name) {
                $params['TaskName'] = $name;
            }

            if($label) {
                $params['Label'] = $label;
            }

            if($callback) {
                $params['CallbackURL'] = $callback;
            }

            $req->fromJsonString(json_encode($params));
            
            $resp = $this->client->CreateTask($req);

            return $this->getJson($resp)['TaskId'];
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 结果
     *
     * @param [type] $id
     *
     * @return void
     * @date 2022-10-20 09:39:25
     */
    public function info($id){
        try {
            $req = new DescribeTaskDetailRequest();
            
            $params = array(
                "TaskId" => $id,

            );
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->DescribeTaskDetail($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    /**
     * 批量结果查看
     *
     * @param array $names
     *
     * @return void
     * @date 2022-10-20 09:39:32
     */
    public function batchInfo($names = []){
        try {
            $req = new DescribeTasksRequest();
            
            $params = array(
                'SortBy' => [
                    'Descend' => true
                ],
                'PageNumber' => 1,
                'PageSize' => 50
            );
            if(!empty($names)) {
                $params['TaskFilter'] = array(
                    "TaskNameSet" => $names,
                    "MediaNameSet" => $names,
                );
            }
            
            $req->fromJsonString(json_encode($params));

            $resp = $this->client->DescribeTasks($req);

            return $this->getJson($resp);
        }
        catch(TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }
}