<?php

namespace BlankPhp\Exception;

use Throwable;

class ParameterLoopException extends Exception
{
    public function __construct($message,$value, $code = 0, Throwable $previous = null)
    {
        $message.=' ';
        foreach ($value as $k=>$item){
            $message .= '['.$k.'] ';
        }
        $message .= ' ....';
        parent::__construct($message, $code, $previous);
    }
}