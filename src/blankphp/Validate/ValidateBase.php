<?php


namespace BlankPhp\Validate;


use BlankPhp\Contract\Request;

class ValidateBase
{
    protected $validateRule = [];
    public static $rule = [

    ];

    public function validate(Request $request, array $array, array $message = []): void
    {


    }


    public function add(): void
    {

    }

    public function replace(): void
    {

    }


}