<?php


namespace Blankphp\Route\Exception;


use Blankphp\Exception\HttpException;

class NotFoundRouteException extends HttpException
{
    protected $code = 404;
}