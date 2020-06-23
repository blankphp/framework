<?php


namespace BlankPhp\Proxy;


use BlankPhp\Proxy\Traits\GetAllMethod;

class ProxyClient
{
    use GetAllMethod;

    private $method;
    private $origin;
    private $proxy;

    public function __construct($originClass, $proxyClass)
    {
        $this->origin = $originClass;
        $this->proxy = $proxyClass;
    }

    /**
     * 关闭链接
     */
    public function close(): void
    {

    }

    public function __call($name, $arguments)
    {
        if (!in_array($name, $this->method, true)) {
            return $this->origin->{$name}(...$arguments);
        }

        return $this->proxy->{$name}(...$arguments);
    }
}