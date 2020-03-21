<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/18
 * Time: 14:53
 */

namespace BlankPhp\Event;


abstract class EventAbstract
{
    private $status;
    public static $observes;

    public static function observe(Observer $observer): void
    {
        //根据信号进行指定更新
        self::$observes = $observer;
    }

    /*
     * 解除绑定
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