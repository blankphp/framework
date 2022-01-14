<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Exception;

class HttpException extends Exception
{
    protected $code = 404;

    public function render()
    {
        $this->httpCode = $this->code;
        parent::render();
    }
}
