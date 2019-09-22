<?php


namespace Blankphp\Collection;


class Collection implements \ArrayAccess, \Iterator, \Countable
{
    //数据的存储
    protected $item = [];

    //初始化函数
    public function __construct()
    {

    }

    /*
     * 去重
     * 找不同
     * 找相同
     * */
    public function item($obj)
    {
        return $this->item[] = empty($obj) ? null : $obj;
    }

    public function merg($value){
        $this->item = array_merge($this->item,$value);
    }

    //
    //转换为数组输出
    public function toArray()
    {
        return array_map(function () {

        }, $this->item);
    }

    //统计$this->>item
    public function count()
    {
        return count($this->item);
    }

    public function offsetExists($offset)
    {
        return isset($this->item[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->item[$offset];
    }


    public function offsetSet($offset, $value)
    {
        $this->item[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->item[$offset]);
    }

    //当前
    public function current()
    {
        return current($this->item);
    }

    public function end()
    {
        return $this->item[$this->count()-1];
    }


    public function next()
    {
        return next($this->item);
    }


    public function key()
    {
        return key($this->item);
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