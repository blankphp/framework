<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Database\Query;

class Raw
{
    public $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function toString()
    {
        return $this->string;
    }
}
