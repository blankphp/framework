<?php
declare(strict_types=1);

namespace BlankPhp\Collection;


class Collection implements \ArrayAccess, \Iterator, \Countable
{
    //数据的存储
    protected $item = [];
    private $position = 0;
    private $keys = [];
    private $max = 0;

    //初始化函数
    public function __construct()
    {

    }

    public function item($obj)
    {
        return $this->item[] = empty($obj) ? null : $obj;
    }

    public function merg($value)
    {
        $this->item = array_merge($this->item, $value);
    }

    //
    //转换为数组输出
    public function toArray()
    {
        return $this->__toArray();
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


    public function rewind()
    {
        if (empty($this->keys)) {
            $this->keys = array_keys($this->item);
            $this->max = count($this->keys);
        }
        $this->position = 0;
    }

    public function current()
    {
        return $this->item[$this->keys[$this->position]];
    }

    public function key()
    {
        return $this->keys[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->item[$this->keys[$this->position]]) && $this->position < $this->max;
    }

    //存储基础的
    public function __call($name, $arguments)
    {

    }

    public function __toArray()
    {
        $data = [];
        foreach ($this->item as $item) {
            $data[] = $item;
        }
        return $data;
    }
}