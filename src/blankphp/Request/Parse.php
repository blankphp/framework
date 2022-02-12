<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Request;

use BlankPhp\Request\Secure\SlashString;
use BlankQwq\Helpers\Arr;

/**
 * @method parseFiles($files)
 * @method parseCookie($cookies)
 * @method parseArr($get)
 * @method input()
 */
class Parse
{
    private $infoDic = [
        'HTTP_USER_AGENT' => 'userAgent', 'REMOTE_ADDR' => '', 'HTTP_ACCEPT_LANGUAGE' => 'lang',
    ];

    private $secure = [
        'parseArr' => SlashString::class,
        'parseCookie' => SlashString::class,
    ];

    /**
     * @param $header
     * @param $server
     * @param $get
     * @param $post
     * @param $cookies
     * @param $files
     */
    public function factory($header, $server, $get, $post, $cookies, $files): array
    {
        $res = [];
        $flag = 0;
        if (is_null($header) && is_null($server)) {
            $this->parseHeaderByServer($res, $_SERVER);
            $flag = 1;
        } else {
            $this->parseHeaderByServer($res, $header);
        }
        if (is_null($server)) {
            $this->parseServer($res, $_SERVER);
        } else {
            $this->parseServer($res, $server);
        }

        if (is_null($post)) {
            $res['post'] = $this->parseArr($_POST);
        } else {
            $res['post'] = $this->parseArr($post);
        }

        $res['post'] = Arr::merge($res['post'], $this->input());

        if (is_null($get)) {
            $res['get'] = $this->parseArr($_GET);
        } else {
            $res['get'] = $this->parseArr($get);
        }

        if (is_null($cookies)) {
            $res['cookie'] = $this->parseCookie($_COOKIE);
        } else {
            $res['cookie'] = $this->parseCookie($cookies);
        }
        if (is_null($files)) {
            $res['files'] = $this->parseFiles($_FILES);
        } else {
            $res['files'] = $this->parseFiles($files);
        }

        return $res;
    }

    private function _parseFiles($files)
    {
        return $files;
    }

    private function _parseCookie($cookie)
    {
        return $cookie;
    }

    private function _parseArr($arr)
    {
        return $arr;
    }

    private function parseHeaderByServer(&$res, $server)
    {
        $this->parseHeader($res, $server);
    }

    private function parseHeader(&$res, $server)
    {
        $res['header'] = $server;
    }

    private function parseServer(&$res, $data)
    {
        $res['uri'] = $this->getUri($data);
        $res['method'] = $this->getMethod($data);

        foreach ($this->infoDic as $k => $v) {
            $res[$v] = $data[$k] ?? null;
        }
    }

    private function getUri($server)
    {
        $url = $server['REQUEST_URI'];
        // 清除?之后的内容,计算？出现的位置position(定位)
        $position = strpos($url, '?');
        //是否截取其中的代码
        $url = false === $position ? $url : substr($url, 0, $position);
        $url = ltrim($url, '/');
        $urlArray = explode('/', $url);
        $urlArray = array_filter($urlArray);
        //获取路径
        $file = explode('/', str_replace(DS, '/', PUBLIC_PATH.'index.php'));
        $urlArray = array_diff($urlArray, $file);
        //去除两边的东西
        if ($urlArray) {
            $uri = '/'.implode('/', $urlArray);
        } else {
            $uri = '/';
        }

        return $uri;
    }

    private function getMethod($server)
    {
        return $server['REQUEST_METHOD'];
    }

    private function _input()
    {
        $res = [];
        $input = file_get_contents('php://input');
        if (strstr($input, '{')) {
            $res = json_decode($input, true);
        } else {
            parse_str($input, $res);
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $method = '_'.$name;
        if (method_exists($this, $method)) {
            $secure = null;
            if (isset($this->secure[$name])) {
                $secureData = $this->secure[$name];
                $secure = new $secureData();
                $arguments = $secure->runBefore(...$arguments);
            }
            $res = $this->{'_'.$name}(...$arguments);
            if (!empty($secure)) {
                $secure->runAfter(...$arguments);
            }

            return $res;
        }
    }
}
