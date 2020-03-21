<?php


namespace BlankPhp\Route\Exception;


use BlankPhp\Exception\HttpException;

class RouteErrorException extends HttpException
{
    protected $code = 500;
}