<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/12
 * Time: 19:46
 */

namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Application extends Facade
{
    protected static function getFacadeAccessor()
    {
       return 'app';
    }

}