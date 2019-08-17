<?php


namespace Blankphp\Collection;


class Collection implements \ArrayAccess, \Iterator,\Countable
{
    //数据的存储
    protected $item = [];
    //初始化函数
    public function __construct()
    {

    }
    //去重


    //找不同


    //找相同
    public function item($obj){
        return $this->item[] = empty($obj)?null:$obj;
    }
    //
    //转换为数组输出
    public function toArray(){
        return array_map(function (){

        },$this->item);
    }

    //统计$this->>item
    public function count()
    {
        return count($this->item);
    }

    public function offsetExists($offset)
    {

    }

    public function offsetGet($offset)
    {

    }


    public function offsetSet($offset, $value)
    {

    }

    public function offsetUnset($offset)
    {

    }


    public function current()
    {

    }


    public function next()
    {

    }


    public function key()
    {

    }

    public function valid()
    {

    }

    public function rewind()
    {

    }
    //存储基础的
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //调用db对象来进行增删改查?
    }
}