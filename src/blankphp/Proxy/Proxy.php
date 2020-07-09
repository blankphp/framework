<?php


namespace BlankPhp\Proxy;


use BlankPhp\Proxy\Traits\GetAllMethod;

class Proxy
{
    use GetAllMethod;
    public $name;
    protected $methods = [];

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
}