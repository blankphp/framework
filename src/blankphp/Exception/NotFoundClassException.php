<?php


namespace Blankphp\Exception;


class NotFoundClassException extends Exception
{

    public function render()
    {
        return response($this->message)->header($this->code)->send();
    }
}