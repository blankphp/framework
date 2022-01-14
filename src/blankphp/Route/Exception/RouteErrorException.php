<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route\Exception;

use BlankPhp\Exception\HttpException;

class RouteErrorException extends HttpException
{
    protected $code = 500;
}
