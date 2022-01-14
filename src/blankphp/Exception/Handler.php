<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Exception;

use BlankPhp\Facade\Log;

class Handler
{
    public function handToConsole($e)
    {
    }

    public function handToRender($e): void
    {
        if ($e instanceof Exception) {
            $e->render();
        } else {
            $e = new HttpException($e->getMessage(), 500);
            $e->render();
        }
        Log::error('error_code: '.$e->getCode().' error_message： '.$e->getMessage(), $e->getTrace());
    }
}
