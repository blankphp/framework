<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Response\Traits;

trait Type
{
    public function setType($value): void
    {
        $this->setHeaderStack($value, 'type');
    }

    public function json($value = null)
    {
        $this->setType(self::$header['json']);
        $this->setContent($value);

        return $this;
    }

    public function file($value = null): void
    {
    }

    public function image($value = null)
    {
        $this->setType(self::$header['image']);
        $this->setContent($value);

        return $this;
    }
}
