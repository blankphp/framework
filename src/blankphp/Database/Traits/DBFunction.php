<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Database\Traits;

/***
 * Trait DBFunction
 * @package BlankPhp\Database\Traits
 * 为数据操作提供响应的计算
 */
trait DBFunction
{
    public function count($field = '*')
    {
        $this->sql->select = ["count($field)"];

        return (int) array_pop($this->commit()->fetch());
    }

    public function avg($field = [])
    {
        foreach ($field as $item) {
            $this->sql->select[] = ["avg($item)"];
        }

        return (int) array_pop($this->commit()->fetch());
    }

    public function sum()
    {
    }

    //工厂方法
    public function dbFunc($type, $fields)
    {
    }
}
