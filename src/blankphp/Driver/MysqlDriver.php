<?php


namespace Blankphp\Driver;



class MysqlDriver extends Driver
{
    protected static $instance;

    private $option = [];

    public function __construct($name = "default", $option = [])
    {
    }

    public function parseValue($value)
    {
        // TODO: Implement parseValue() method.
    }

    public function valueParse($value)
    {
        // TODO: Implement valueParse() method.
    }

    public function delete($key)
    {
        // TODO: Implement delete() method.
    }

    public function forget($key)
    {
        // TODO: Implement forget() method.
    }

    public function set($key, $value, $ttl = null)
    {
        return DB::table($this->option['table'])->create([
            'key' => $key,
            'value' => $value,
            'ttl' => $ttl
        ]);
    }

    public function get($key, $default = "")
    {
        return empty($result = DB::table($this->option['table'])->where('key', $key)->first()) ? $default : $result->value;
    }

    public function remember($array, \Closure $closure, $ttl = 0)
    {
        if ($this->has($array))
            return $this->get($array);
        else {
            $data = $closure();
            $this->set($array, $data, $ttl);
            return $data;
        }
    }

    public function has($key)
    {
        return DB::table($this->option['table'])->where('key', $key)->count() > 0;
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }
}