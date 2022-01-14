<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Cookie;

use BlankPhp\Contract\CookieContract;

class Cookie implements CookieContract
{
    protected $expires = 0;
    protected $path = null;
    protected $domain = null;
    protected $secure = false;
    protected $httpOnly = false;
    protected $cookie = [];
    protected $queue = [];

    public function __construct()
    {
        $this->getCookie();
    }

    private function getCookie(): void
    {
        $this->cookie = $_COOKIE;
        unset($_COOKIE);
    }

    /**
     * @param array $option
     */
    public function setOption($option = []): void
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
        if (null !== $option) {
            array_shift($option);

            return setcookie(
                $key,
                $value,
                time() + $expires,
                ...array_values($option)
            );
        }

        return setcookie(
            $key,
            $value,
            time() + $expires,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }

    public function get($name = '', $default = '')
    {
        if (empty($name)) {
            return $this->cookie;
        }
        if (!empty($this->cookie)) {
            if ((0 === strpos($this->cookie[$name], '{'))) {
                return json_decode($this->cookie[$name], true);
            }

            return $this->cookie[$name];
        }

        return $default;
    }

    public function flush()
    {
        foreach ($this->queue as $k => $v) {
            $this->set($k, $v);
        }
    }

    public function putQueue($value): void
    {
        $this->queue[] = $value;
    }

    public function popQueue()
    {
        return array_pop($this->queue);
    }
}
