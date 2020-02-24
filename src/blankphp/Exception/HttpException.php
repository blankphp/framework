<?php


namespace Blankphp\Exception;


class HttpException extends Exception
{
    protected $code = 404;

    public function render()
    {
        $this->httpCode = $this->code;
        parent::render();
    }

}