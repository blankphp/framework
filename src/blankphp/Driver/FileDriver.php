<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver;

class FileDriver extends Driver
{
    public static $key;
    protected static $cacheTime = 0;
    private static $dir;
    protected $config = [];
    protected $data;
    protected $option;

    public function __construct($name = 'default', $option = [])
    {
        $this->config = array_merge($this->config, $option);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getFromFile($file): void
    {
        //加载
        $data = [];
        if (is_file(self::$dir.$file)) {
            $data = require self::$dir.$file;
        }
        $this->data = array_merge($this->data, $data);
    }

    public function canRebuild($file, $descFile): bool
    {
        return filemtime($file) - filemtime(self::$dir.$descFile) < self::$cacheTime;
    }

    public function build($key): string
    {
        return $this->option['tag'].$key;
    }

    /**
     * @param $key
     * @param $value
     * @param null $ttl
     */
    public function set($key, $value, $ttl = null)
    {
        $value = ['value' => $value, 'ttl' => time() + $ttl];

        return \BlankQwq\Helpers\File::put($this->build($key), $value);
    }

    public function remember($key, \Closure $closure, $ttl = null): string
    {
        $this->build($key);
        if ($this->has($key)) {
            return $this->get($key);
        }
        $this->set($key, $closure(), $ttl);

        return $this->data[$key];
    }

    public function has($key)
    {
        if (isset($this->data[$key])) {
            return true;
        }

        return false;
    }

    public function get($key, $default = ''): string
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function forget($key)
    {
        // TODO: Implement forget() method.
    }
}
