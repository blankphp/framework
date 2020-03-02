<?php


namespace Blankphp\Facade;


use Blankphp\Facade;

class Driver extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "driver.manager";
    }

}