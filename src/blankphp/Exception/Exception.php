<?php


namespace Blankphp\Exception;


use Blankphp\Response\Response;
use Throwable;

abstract class Exception extends \Exception
{
    protected $message;
    protected $code;
    protected $httpCode = 500;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function bootstrap()
    {

    }

    public function httpCode()
    {
        return $this->httpCode;
    }

    public function render()
    {
        //返回模板

        //判断是否是json
        $response = new Response(view(__DIR__ . "/stub/error.php", ["message" => $this->getMessage(), "file" => $this->getFile(), 'line' => $this->getLine()], false));
        $response->header($this->httpCode());
        $response->prepare()->send();
    }

    public function handle()
    {
        //处理
    }

}