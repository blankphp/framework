<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Exception;

use Throwable;

class ParameterLoopException extends Exception
{
    public function __construct($message, $value, $code = 0, Throwable $previous = null)
    {
        $message .= ' ';
        foreach ($value as $k => $item) {
            $message .= '['.$k.'] ';
        }
        $message .= ' ....';
        parent::__construct($message, $code, $previous);
    }
}
