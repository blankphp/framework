<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 15:33
 */

namespace BlankPhp\Cookie;


use BlankPhp\Contract\CookieContract;
use BlankPhp\Request\Facade\Request;


class Cookie implements CookieContract
{
    protected $expires = 0;
    protected $path = null;
    protected $domain = null;
    protected $secure = false;
    protected $httponly = false;
    protected $cookie = [];
    protected $queue = [];

    public function __construct()
    {
        $this->getCookie();
    }

    public function getCookie()
    {
        $this->cookie = $_COOKIE;
        unset($_COOKIE);
    }

    public function setOption($option = [])
    {
        foreach ($option as $k => $v) {
            if (isset($this->{$k})) {
                $this->{$k} = $v;
            } else {
                continue;
            }
        }
    }


    public function set($key, $value, $expires = null, $option = null)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        if (!is_null($option)) {
            array_shift($option);
            return setcookie($key, $value, time() + $expires, ...array_values($option)
            );
        }
        return setcookie($key, $value, time() + $expires, $this->path, $this->domain, $this->secure
            , $this->httponly
        );
    }

    public function get($name = "", $default = "")
    {
        if (empty($name)) {
            return $this->cookie;
        }
        if (!empty($this->cookie)) {
            return substr($this->cookie[$name], 0, 1) == '{' ?
                json_decode($this->cookie[$name]) :
                $this->cookie[$name];
        }
        return $default;
    }

    //添加一个队列操作,从队列中加入想要的Cookie
    public function putQueue()
    {

    }

    public function popQueue()
    {

    }

}