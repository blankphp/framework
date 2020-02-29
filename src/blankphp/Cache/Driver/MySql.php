<?php


namespace Blankphp\Cache\Driver;


use Blankphp\Application;
use Blankphp\Cache\Contract\Driver;
use Blankphp\Database\Facade\DB;
use Blankphp\Exception\Exception;

class MySql implements Driver
{
    protected static $instance;

    private $option = [
        'table' => 'b_cache',
    ];

    public function __construct($config)
    {
        $this->option = $config;
    }

    public static function getInstance(array $config)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($config);
        }
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