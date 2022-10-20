<?php

namespace TencentHelper\Live;

trait Url {

    public function getPlayUrl()
    {

    }

    public function getPushUrl()
    {
        
    }

    /**
     * 生成流名称
     *
     * @param int $length
     *
     * @return string
     * @date       2022-03-16 16:35:20
     */
    public function streamNameCreator(int $length)
    {
        $i = floor($length / 64);
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-';
        $name = '-';
        while($i--) {
            $name .= str_shuffle($str);
        }
        
        return date('YmdHis') . $name . substr(str_shuffle($str), 0, ($length % 64));
    }
}