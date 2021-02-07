<?php


namespace BlankPhp\Collection;


class Collection implements \ArrayAccess, \Iterator, \Countable
{
    //数据的存储
    protected $item = [];
    private $keys = [];
    private $max = 0;
    private $position = 0;

    public function __construct()
    {

    }

    public function item($obj)
    {
        return $this->item[] = empty($obj) ? null : $obj;
    }

    public function mere($value)
    {
        return $this->item = array_merge($this->item, $value);
    }

    //
    //转换为数组输出
    public function toArray()
    {
        return array_map(static function () {

        }, $this->item);
    }

    //统计$this->>item
    public function count():int
    {
        return count($this->item);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->item[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->item[$offset];
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->item[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
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

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->item[$this->keys[$this->position]]) && $this->position < $this->max;
    }

    //存储基础的
    public function __call($name, $arguments)
    {

    }

    /**
     * @return array
     */
    private function __toArray()
    {
        $data = [];
        foreach ($this->item as $item) {
            $data[] = $item;
        }
        return $data;
    }
}