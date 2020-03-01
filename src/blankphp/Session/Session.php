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
use Blankphp\Facade\Cookie;
use Helpers\Str;

class Session implements SessionContract
{
    protected static $sessionName = '_session';

    protected $handler;
    //session_id
    protected $id;
    //数据
    protected $data = [];
    //是否生成
    protected $generate = true;
    //过期时间
    protected $expire = 35000;

    public function __construct(Application $app)
    {
        $config = config('app.session');
        static::$sessionName = $config['name'];
        $this->expire = $config['expire'];
        $className = Str::makeClassName($config['handler']);
        $this->handler = new $className;
        session_set_save_handler($this->handler, true);
    }

    public function generate()
    {
        return Str::random(40);
    }

    public function start()
    {
        $this->isLegal();
        if ($this->generate) {
            $this->reGenerate();
        }
    }

    public function reGenerate()
    {
        $this->setId($this->generate());
        $this->setCookie();
    }

    public function isLegal()
    {
        $id = Cookie::get(static::$sessionName);
        if (!empty($id) && !empty($data = $this->handler->read($id))) {
            $this->setId($id);
            $this->setData($data);
            $this->generate = false;
        }
    }

    public function get($key, $default = "")
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function getFlash($key, $default = "")
    {
        return isset($this->data["b__current__p"][$key]) ? $this->data["b__current__p"][$key] : $default;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setData($data)
    {
        if (!empty($this->data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }
    }

    public function setCookie()
    {
        Cookie::set(static::$sessionName, $this->id, $this->expire);
    }

    public function set($key, $value)
    {
        $this->setData([$key => $value]);
    }

    //下一次请求有效
    public function flash($key, $value)
    {
        $this->push("b__next__p", [$key => $value]);
    }


    public function reFlash()
    {
        //重新加入current
        $this->set("b__next__p", $this->forget("b__current__p"));
    }

    public function clearFlash()
    {
        //清楚flash
        $this->delete("b__current__p");
        //next变成current
        $next = $this->forget("b__next__p");
        if (!empty($next)) {
            $this->set("b__current__p", $next);
        }
    }

    public function delete($key)
    {
        $this->data[$key] = null;
        unset($this->data[$key]);
    }


    public function forget($key)
    {
        $value = $this->data[$key];
        $this->delete($key);
        return $value;
    }

    public function push($key, $value = [])
    {
        if (!isset($this->data[$key]) || is_null($this->data[$key])) {
            $this->data[$key] = [];
        }
        $this->data[$key] = array_merge($this->data[$key], is_array($value) ? $value : [$value]);

    }

    private function save()
    {
        $this->handler->write($this->id, $this->data);
    }

    public function end()
    {
        $this->clearFlash();
        $this->save();
    }

    //清理内容
    public function flush()
    {
        $this->data = [];
        //清理内容
        $this->handler->destroy($this->id);
        //重新生成
        $this->reGenerate();
    }


}