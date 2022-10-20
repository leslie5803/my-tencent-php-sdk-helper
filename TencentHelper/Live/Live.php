<?php

namespace TencentHelper\Live;

use TencentCloud\Common\Exception\TencentCloudSDKException;

use TencentCloud\Live\V20180801\Models\{
    AddDelayLiveStreamRequest,
    DescribeLiveStreamStateRequest
};

/**
 * 云直播
 *
 * @date       2022-03-07 21:22:34
 */
final class Live extends LiveBase
{
    use Url;

    /**
     * 创建流地址
     *
     * @param string $length maxLength=255
     *
     * @return self
     * @date       2022-03-07 21:38:18
     */
    public function createStreamName($length = 10): self
    {
        return $this->setStreamName($this->streamNameCreator($length));
    }


    /**
     * 播放地址
     *
     * @param [type] ...$types
     *
     * @return array
     * @date       2022-03-15 21:14:04
     */
    public function getPlayUrl(...$types): array
    {
        $domain = $this->config->getPlayDomain();
        $str    = $this->playAuth($this->config->getExpire());

        $data['rtmp']         = 'rtmp://' . $domain . '/live/' . $this->streamName . $str;
        $data['flv']          = 'https://' . $domain . '/live/' . $this->streamName . '.flv' . $str;
        $data['m3u8']['sign'] = 'https://' . $domain . '/live/' . $this->streamName . '.m3u8' . $str;
        $data['m3u8'][]       = 'https://' . $domain . '/live/' . $this->streamName . '.m3u8';

        if (empty($types)) {
            return $data;
        }

        $list = [];
        foreach ($types as $type) {
            if (isset($data[$type])) {
                $list[$type] = $data[$type];
            }
        }

        return $list;
    }

    /**
     * 获取推流地址
     *
     * 如果不传key和过期时间，将返回不含防盗链的url
     *
     * @param string $expire 2022-03-08 10:49:46
     * @param string $type cloud/video/voice/fm/network
     * @return string
     * @date       2022-03-08 10:49:46
     */
    public function getPushUrl($expire = '', $type = 'Cloud', $ext = []): string
    {
        $domain = $this->config->getPushDomain();
        $key    = $this->config->getPushKey();

        $time = $this->getExpireTime($expire);

        if ($key) {
            $txTime = strtoupper(base_convert($time, 10, 16));
            $txSecret = md5($key . $this->streamName . $txTime);
            $ext_str  = "?" . http_build_query(array(
                "txSecret" => $txSecret,
                "txTime"   => $txTime,
                "liveType" => $type,
            ));
            
            if(!empty($ext)) {
                $ext_str .= http_build_query($ext);
            }
        }

        return "rtmp://" . $domain . "/live/" . $this->streamName . (isset($ext_str) ? $ext_str : "");
    }

    /**
     * 过期时间获取
     *
     * @param string $time
     *
     * @return int
     * @date       2022-03-08 10:55:03
     */
    private function getExpireTime(string $time = ''): int
    {
        if (empty($time)) {
            $days = $this->config->getKeep() ?: 7;
            return time() + $days * 86400;
        }

        return strtotime($time);
    }

    /**
     * 获取播放鉴权
     *
     * @param string $tx_time Y-m-d H:i:s
     *
     * @return string
     * @date       2022-03-08 10:56:49
     */
    public function playAuth($tx_time = '')
    {
        $time = $this->getExpireTime($tx_time);
        $txTime   = strtoupper(dechex($time));
        $txSecret = md5($this->config->getPlayKey() . $this->streamName . $txTime);

        return '?txSecret=' . $txSecret . '&txTime=' . $txTime;
    }

    public function getTranscodeUrl($template_names = [])
    {
        $domain = $this->config->getPlayDomain();
        $urls   = [];

        foreach ($template_names as $k => $v) {
            $streamName = $this->getStreamName() . '_' . $v;
            $urls[]           = 'https://' . $domain . '/live/' . $streamName . '.m3u8' . $this->playAuth(); //M3U8格式
        }

        return $urls;
    }

    //云直播延迟播放
    public function delay()
    {
        try {
            $req       = new AddDelayLiveStreamRequest();

            $params = [
                'AppName'    => 'live',
                'DomainName' => $this->config->getPushDomain(),
                'StreamName' => $this->getStreamName(), //流名称
                'DelayTime'  => (int)$this->config->getPlayDelay(), //延播时间，单位：秒，上限：600秒。
                //                'ExpireTime'=>'',//延播设置的过期时间。UTC 格式，例如：2018-11-29T19:00:00Z。注意：默认7天后过期，且最长支持7天内生效。
            ];
            $req->fromJsonString(json_encode($params));

            return $this->getJson($this->client->AddDelayLiveStream($req));
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }

    //查询流状态
    public function getLiveState()
    {
        try {
            $req    = new DescribeLiveStreamStateRequest();

            $params = [
                'AppName'    => 'live', //推流路径。
                'DomainName' => $this->config->getPushDomain(), //推流域名
                'StreamName' => $this->getStreamName(), //流名称。
            ];

            $params = json_encode($params);
            $req->fromJsonString($params);

            $resp = $this->client->DescribeLiveStreamState($req);

            return $this->getJson($resp);
        } catch (TencentCloudSDKException $e) {
            throw new \Exception(sprintf('失败原因:%s', $e->__toString()), 1);
        }
    }


}
