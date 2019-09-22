<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14
 * Time: 17:23
 */

namespace Blankphp\Contract;


interface Container
{
    /**
     * 判断容器中是否含有某个元素
     * @param $abstract
     * @return mixed
     */
    public function has($abstract);

    /**
     * 吧实例监听到容器中
     * @param $abstract
     * @param $instance
     * @return mixed
     */
    public function bind($abstract, $instance);

    /**
     * 注册
     * @param $abstract
     * @param $instance
     * @return mixed
     */
    public function signal($abstract, $instance);

    /**
     * @param $abstract
     * @param $instance
     * @return mixed
     */
    public function instance($abstract, $instance);

    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make($abstract, $parameters = []);

    /**
     * 通过反射创建某个类
     * @param $concrete
     * @return mixed
     */
    public function build($concrete);

    /**
     * 调用某个类中的方法
     * @param $instance
     * @param null $method
     * @param array $param
     * @return mixed
     */
    public function call($instance, $method=null,array $param=[]);

    /**
     * @param $name
     * @param $class
     * @return mixed
     * 创建别名
     */
    public function alice($name,$class);

}