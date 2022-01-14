<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Model\Traits;

trait belongsToMany
{
    public function belongToMany($table, $localColumn, $foreignColumn)
    {
        //查出全部id然后in查询
        //表连接这里1：1就采用内连接,并把数据返回到Collection
        $this->database->join($table, $localColumn, $foreignColumn);
        //多就查询多条放入数据
    }
}
