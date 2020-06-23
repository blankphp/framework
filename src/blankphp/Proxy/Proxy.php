<?php


namespace BlankPhp\Proxy;


class Proxy
{
    private $name;
    private $bridge;
    private $client;

    public function __construct()
    {

    }

    public function bridge($object): ProxyBridge
    {
        if ($this->bridge){

        }
        $bridge = new ProxyBridge();
        return ;
    }

    //链接的代理
    public function client(){
        $this->client = new ProxyClient();

    }

    /**
     * @param $name
     * @param $arguments
     * 调用真正的方法
     */
    public function __call($name, $arguments)
    {

    }

    private function getMethod(){
        //反射获取方法列表
    }

}