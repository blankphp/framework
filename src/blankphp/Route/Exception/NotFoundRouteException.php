<?php


namespace BlankPhp\Route\Exception;


use BlankPhp\Exception\HttpException;

class NotFoundRouteException extends HttpException
{
    protected $code = 404;
}