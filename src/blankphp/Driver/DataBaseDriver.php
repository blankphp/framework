<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Driver;

use BlankPhp\Database\Database;
use BlankPhp\Database\Exception\DataBaseException;
use BlankPhp\Database\Query\Builder;
use BlankPhp\Exception\Exception;
use BlankPhp\Facade\Application;

class DataBaseDriver extends Driver
{
    protected static $instance;

    private $option = [];

    protected $db;

    public function __construct($name = 'default', $option = [])
    {
        //创建Database
        $this->option = empty($option) ? $this->option : $option;
        $app = Application::getInstance();
        try {
            //初始化连接
            $this->db = $app->make(Database::class, [$app->build(Builder::class), $this->option]);
            $app->instance('database.handler.'.$name, $this->db);
        } catch (Exception $exception) {
            throw new DataBaseException($exception->getMessage());
        }
        //后续操作
    }

    public function clearExpireData()
    {
    }

    public function delete($key)
    {
        return $this->db->table($this->option['table'])->where('key', $key)->delete();
    }

    public function forget($key)
    {
        $value = $this->get($key);
        $this->delete($key);

        return $value;
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->db->table($this->option['table'])->create([
            'key' => $key,
            'value' => $value,
            'ttl' => $ttl,
        ]);
    }

    public function get($key, $default = '')
    {
        return empty($result = $this->db->table($this->option['table'])->where('key', $key)->first()) ? $default : $result->value;
    }

    public function remember($array, \Closure $closure, $ttl = 0)
    {
        if ($this->has($array)) {
            return $this->get($array);
        }
        $data = $closure();
        $this->set($array, $data, $ttl);

        return $data;
    }

    public function has($key)
    {
        return $this->db->table($this->option['table'])->where('key', $key)->count() > 0;
    }

    //清空全部
    public function flush()
    {
        return $this->db->table($this->option['table'])->flush();
    }
}
