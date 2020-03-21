<?php


namespace BlankPhp\Facade;


use BlankPhp\Facade;

class Driver extends Facade
{
    protected static function getFacadeAccessor():string
    {
        return "driver.factory";
    }

}