<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/17
 * Time: 15:32
 */

namespace Blankphp\Session;


use Blankphp\Application;
use Blankphp\Contract\Session as SessionContract;
use Blankphp\Cookie\Facade\Cookie;

class Session implements SessionContract
{
    protected static $sessionName = 'BlankPhp';
    protected $nameSpace = 'Blankphp\Session\Driver\\';
    protected $handler = null;
    public function __construct(Application $app)
    {
        $config = config('app.session');
        if (isset($config['expire'])){
            session_cache_expire($config['expire']);
        }
        if (isset($config['path'])){
            session_save_path($config['path']);
        }
        if (isset($config['name'])){
            session_name($config['name']);
            self::$sessionName=$config['name'];
        }else{
            session_name(self::$sessionName);
        }
        if (isset($config['driver'])){
            if (class_exists($config['driver'])){
                $this->handler = new $config['driver'];
            }else{
                $className = $this->nameSpace.ucfirst($config['driver']).'SessionHandler';
                $this->handler = new $className;
            }
            session_set_save_handler($this->handler,true);
            $app->instance('session.handler',$this->handler);
        }
        $app->instance('session',$this);
    }

    public function start(){
            session_start();
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function destroy()
    {
        $_SESSION = [];
        $paramers = session_get_cookie_params();
        Cookie::set(self::$sessionName, '', time() - 1,$paramers);
        session_destroy();
    }

    public function get($name)
    {
        return isset($_SESSION[$name])?$_SESSION[$name]:[];
    }

}