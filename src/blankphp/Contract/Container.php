<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Contract;

interface Container
{
    /**
     * 判断容器中是否含有某个元素.
     *
     * @param $abstract
     *
     * @return mixed
     */
    public function has($abstract);

    /**
     * 吧实例监听到容器中.
     *
     * @param $abstract
     * @param $instance
     *
     * @return mixed
     */
    public function bind($abstract, $instance);

    /**
     * @param $abstract
     * @param $instance
     * @param $share
     *
     * @return mixed
     */
    public function instance($abstract, $instance);

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function make(string $abstract, $parameters = []);

    /**
     * 通过反射创建某个类.
     *
     * @param $concrete
     *
     * @return mixed
     */
    public function build($concrete);

    /**
     * 调用某个类中的方法.
     *
     * @param $instance
     * @param $method
     * @param array $construct
     *
     * @return mixed
     */
    public function call($instance, $method, array $param = []);

    /**
     * @param $name
     * @param $class
     *
     * @return mixed
     *               创建别名
     */
    public function alice($name, $class);
}
