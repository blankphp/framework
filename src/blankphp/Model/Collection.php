<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Model;

use BlankPhp\Collection\Collection as BaseCollection;

class Collection extends BaseCollection
{
    //模型查询的数据存储于此
    protected $relation = [];
    //原始数据
    protected $origin = [];
}
