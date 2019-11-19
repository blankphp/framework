<?php


namespace Blankphp\Database\Query;


class Raw
{
    public $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function toString(){
        return $this->string;
    }
}