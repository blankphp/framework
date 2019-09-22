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

    public function __construct($option)
    {
        $this->option = $option;
    }

    public static function getInstance(Application $app=null)
    {
        if (!empty(self::$instance)) {
            return self::$instance;
        } else {
            return self::$instance = new self($app);
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

    public function get($key, $default)
    {
        return DB::table($this->option['table'])->where('key', $key)->first();
    }

    public function remember($array, \Closure $closure)
    {
        if ($this->has($array))
            return $this->get($array);
        else {
            $data = $closure();
            $this->set($array, $data);
            return $data;
        }
    }

    public function has($key)
    {
        return DB::table($this->option['table'])->where('key', $key)->count();
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }
}