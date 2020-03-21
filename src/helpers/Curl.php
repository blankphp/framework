<?php

namespace BlankQwq\Helpers;

class Curl
{

    public static $header = [];
    private static $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36';

    private static function init()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  //成功返回结果，不自动输出
        curl_setopt($curl, CURLOPT_HEADER, 0); //包头包含在输出中
        curl_setopt($curl, CURLOPT_USERAGENT, self::$userAgent); // 伪造一个 HTTP_USER_AGENT 信息，解决为将对象引用设置到对象的实例问题
        return $curl;
    }

    public static function get($url, $https = false, $data = [], $option = [])
    {
        if (!empty($data)) {
            $url = rtrim($url, '?') . '?' . self::parseGetData($data);
        }
        $curl = self::init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);
        $result = curl_exec($curl);
        return empty($error) ? $result : $info;
    }

    private static function parseGetData($data): string
    {
        if (is_array($data)) {
            $temp = '';
            foreach ($data as $k => $v) {
                $temp .= '=' . $v;
            }
            $data = rtrim($temp, '&');
        }
        return $data;
    }

    public static function post($url, $https = false, $data = [], $option = [])
    {
        $curl = self::init();

        curl_setopt($curl, CURLOPT_POST, 1); // 此请求为 post 请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // 传递 post 参数

    }

    public static function request()
    {

    }


    public static function getFile()
    {

    }
}