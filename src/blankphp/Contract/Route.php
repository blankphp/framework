<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Contract;

interface Route
{
    public function __construct();

    public function get($uri, $action);

    public function delete($uri, $action);

    public function put($uri, $action);

    public function post($uri, $action);

    public function any($uri, $action);

    public function addRoute($methods, $uri, $action);

    public function file($file);

    public function prefix($prefix);

    public function group($group);

    public function setNamespace($namespace);

    public function match($request);

    public function findRoute($request);

    public function getController($controller);

    public function runController($controller, $method, $parameters = []);

    public function run($request);

    public function putCache();

    public function cache();

    public function parseVar();

    public function parseModel();
}
