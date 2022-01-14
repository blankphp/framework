<?php

declare(strict_types=1);

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Config;

class Config implements \Countable
{
    public $config;
    protected $current;

    /**
     * @param $config
     * @return $this
     */
    public function setConfig($config): Config
    {
        $this->config = $config;

        return $this;
    }

    public function get($descNames, $default = '')
    {
        $config = $this->config;
        //parse
        if (!is_array($descNames)) {
            $descNames = explode('.', $descNames);
            $descNames = array_filter($descNames);
        }
        foreach ($descNames as $descName) {
            if (isset($config[$descName])) {
                $config = $config[$descName];
            } else {
                return $default;
            }
        }

        return $config;
    }

    public function set($key, $value)
    {
        //获取driver

        //利用driver保存并刷新对应文件
    }

    public function all()
    {
        return $this->config;
    }

    public function count():int
    {
        return count($this->config);
    }


}
