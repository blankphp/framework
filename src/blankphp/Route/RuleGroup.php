<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route;

class RuleGroup
{
    private $group = '';
    private $prefix = '';
    private $namespace = '';
    /** @var Route */
    private $route;

    public function __construct($parameter, $data, $route)
    {
        $this->route = $route;
    }

    public function group($name)
    {
        $this->group = $name;

        return $this;
    }

    public function prefix($name)
    {
        $this->prefix = $name;

        return $this;
    }

    public function namespace($name)
    {
        $this->namespace = $name;

        return $this;
    }

    public function file($file)
    {
        $this->route->setAttributes($this->getAll());
        $this->makeRequireFile($file)();
        $this->route->popAttributes();
    }

    private function makeRequireFile($fileName): \Closure
    {
        return function () use ($fileName) {
            require $fileName;
        };
    }

    public function getAll()
    {
        return [
            'namespace' => $this->namespace,
            'group' => $this->group,
            'prefix' => $this->prefix,
        ];
    }
}
