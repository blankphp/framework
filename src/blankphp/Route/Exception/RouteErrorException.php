<?php


namespace Blankphp\Route\Exception;


use Blankphp\Exception\HttpException;

class RouteErrorException extends HttpException
{
    protected $code = 500;
}