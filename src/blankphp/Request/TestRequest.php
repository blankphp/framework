<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Request;

class TestRequest extends Request
{
    protected static $instance;

    public function __construct()
    {
    }

    public static function create(
        $method,
        $uri,
        $parameters,
        $cookies,
        $files,
        $server,
        $content
    ): TestRequest {
        self::$instance = new self();
        self::$instance->uri = $uri;
        self::$instance->method = $method;
        self::$instance->request = $parameters;
        self::$instance->request['cookie'] = $cookies;
        self::$instance->request['files'] = $files;
        self::$instance->server = $server;

        return self::$instance;
    }
}
