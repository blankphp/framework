<?php


namespace Blankphp\Exception;


class HttpException extends Exception
{
    protected $code=404;

    public function render()
    {
        var_dump($this->code);
        return response($this->message)->header($this->code)->send();
    }

}