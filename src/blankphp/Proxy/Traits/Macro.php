<?php


namespace BlankPhp\Proxy\Traits;


trait Macro
{
    protected static $macro = [];

    /**
     * @param $name
     * @param \Closure $closure
     */
    public static function macro($name, \Closure $closure)
    {

    }

    public function __call($name, $arguments)
    {

    }
}