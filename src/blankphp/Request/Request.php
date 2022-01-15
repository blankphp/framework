<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Request;

use BlankPhp\Base\Traits\ContainerBindMake;
use BlankPhp\Contract\Request as RequestContract;
use BlankQwq\Helpers\Arr;

class Request implements RequestContract
{
    use ContainerBindMake;

    public $uri;
    public $method;
    public $input = [];
    public $user_ip;
    public $server_ip;
    public $http;
    public $https;
    public $port;
    private $server;
    private $header;
    private $get;
    private $post;
    private $files;
    public $cookie = [];
    public $language;
    public $userIp;
    public $userAgent;
    public static $instance;

    public static function capture($header = null, $server = null, $get = null, $post = null, $cookies = null, $files = null): Request
    {
        $obj = new self();
        /** @var Parse $parse */
        $parse = app('request.parse');
        $res = $parse->factory($header, $server, $get, $post, $cookies, $files);
        foreach ($res as $k => $v) {
            $method = 'set'.ucfirst($k);
            if (method_exists($obj, $method)) {
                $obj->{$method}($v);
            } else {
                $obj->{$k} = $v;
            }
        }

        return self::$instance = $obj;
    }

    private function setServer($server)
    {
        $this->server = $server;
    }

    private function setGet($get)
    {
        $this->get = $get;
    }

    private function setPost($post)
    {
        $this->post = $post;
    }

    private function setFiles($files)
    {
        $this->files = $files;
    }

    private function setCookie($cookie)
    {
        $this->cookie = $cookie;
    }

    private function __construct()
    {
        // 其他方法
    }

    public function input($name = '', $default = null)
    {
        return $this->getByName($name, $this->get, $default);
    }

    private function getByName($name, $arr, $default)
    {
        return Arr::get($arr, $name, $default);
    }

    public function get($name = '', $default = null)
    {
        return $this->getByName($name, $this->post, $default);
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function file($name = '')
    {
        return $this->files[$name] ?: null;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function userIp()
    {
        return $this->user_ip;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getServicePort()
    {
        return $this->port;
    }

    public function __toArray(): array
    {
        return [
            'uri' => $this->uri,
            'method' => $this->method,
            'input' => $this->input,
            'user_ip' => $this->user_ip,
            'server_ip' => $this->server_ip,
            'http' => $this->http,
            'https' => $this->https,
            'userAgent' => $this->userAgent,
            'userIp' => $this->userIp,
            'language' => $this->language,
        ];
    }
}
