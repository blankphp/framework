<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Model\Traits;

trait belongsTo
{
    //谁属于谁
    public function belongTo($table, $localColumn, $foreignColumn)
    {
        //表连接这里1：1就采用内连接,并把数据返回到Collection

        //多就查询多条放入数据
    }
}
