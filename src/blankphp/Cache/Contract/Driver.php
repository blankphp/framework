<?php


namespace Blankphp\Cache\Contract;


use Blankphp\Application;

interface Driver
{

    public static function getInstance(Application $app=null);

    public function set($key, $value, $ttl=null );

    public function get($key, $default='');

    public function remember($array,\Closure $closure);

    public function has($key);

    public function flush();
}