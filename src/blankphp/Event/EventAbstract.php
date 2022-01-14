<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Event;

abstract class EventAbstract
{
    private $status;
    public static $observes;

    public static function observe(Observer $observer): void
    {
        self::$observes = $observer;
    }

    /**
     * @param $name
     */
    public function deobserve($name): void
    {
        //解除绑定关系
        unset(self::$observes[$name]);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    //将状态推行到每一个观察者
    public function notify(): void
    {
        if (isset($this->observes)) {
            self::$observes->{$this->status}($this);
        }
    }

    public function event($event): void
    {
        $this->setStatus($event);
        $this->notify();
    }
}
