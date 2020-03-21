<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 21:33
 */

namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Route extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'route';
    }
}