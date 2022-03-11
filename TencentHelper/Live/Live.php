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
    /**
     * 创建流地址
     *
     * @param string $prefix
     * @param string $suffix
     *
     * @return array
     * @date       2022-03-07 21:38:18
     */
    public function createStream(string $prefix = '', string $suffix = ''): array
    {
        $this->streamName = $prefix . date('YmdHis') . $suffix;

        $streaminfo['streamKey'] = $this->streamName;

        //播放地址
        $streaminfo['playurl'] = $this->getPlayUrl()['play_url'];

        //推流地址
        $streaminfo['publishurl'] = $this->getPushUrl();

        return $streaminfo;
    }

    /**
     * 播放地址
     *
     *
     * @return array
     * @date       2022-03-07 21:40:20
     */
    public function getPlayUrl(): array
    {
        $domain = $this->config->getPlayDomain();
        $str    = $this->playAuth($this->config->getExpire());

        $data['play_url1'] = 'rtmp://' . $domain . '/live/' . $this->streamName . $str;
        $data['play_url2'] = 'https://' . $domain . '/live/' . $this->streamName . '.flv' . $str;
        $data['play_url3'] = 'https://' . $domain . '/live/' . $this->streamName . '.m3u8' . $str;
        $data['play_url']  = 'https://' . $domain . '/live/' . $this->streamName . '.m3u8';

        return $data;
    }

    /**
     * 获取推流地址
     *
     * 如果不传key和过期时间，将返回不含防盗链的url
     *
     *
     * @return string
     * @date       2022-03-08 10:49:46
     */
    public function getPushUrl(): string
    {
        $domain = $this->config->getPushDomain();
        $key    = $this->config->getPushKey();

        $time = $this->getExpireTime($this->config->getKeep());

        if ($key && $time) {
            $txTime = strtoupper(base_convert($time, 10, 16));
            //txSecret = MD5( KEY + streamName + txTime )
            $txSecret = md5($key . $this->streamName . $txTime);
            $ext_str  = "?" . http_build_query(array(
                "txSecret" => $txSecret,
                "txTime"   => $txTime,
                "liveType" => 'Cloud',
            ));
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
     * @param string $tx_time
     *
     * @return void
     * @date       2022-03-08 10:56:49
     */
    public function playAuth($tx_time = '')
    {
        $time = $this->getExpireTime($tx_time);

        $txTime   = strtoupper(dechex($time));
        $txSecret = md5($this->config->getPlayKey() . $this->streamName . $txTime);

        return '?txSecret=' . $txSecret . '&txTime=' . $txTime;
    }

}
