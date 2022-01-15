<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Base\Traits;

trait ContainerBindMake
{
    private $bindThisObj = [];

    public function useThis($instance)
    {
        $this->bindThisObj[] = $instance;
    }

    public function cleanThem()
    {
        foreach ($this->bindThisObj as $item) {
            app()->cleanInstance($item);
        }
    }
}
