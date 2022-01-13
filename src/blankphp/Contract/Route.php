<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 21:33
 */

namespace BlankPhp\Contract;


use BlankPhp\Application;
use BlankPhp\Route\RouteCollection;

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