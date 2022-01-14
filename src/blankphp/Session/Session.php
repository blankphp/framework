<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Session;

use BlankPhp\Base\Traits\FactoryClientTrait;
use BlankPhp\Contract\Session as SessionContract;
use BlankPhp\Facade\Cookie;
use BlankQwq\Helpers\Arr;
use BlankQwq\Helpers\Str;

class Session implements SessionContract
{
    use FactoryClientTrait;

    /**
     * @var string
     */
    protected static $sessionName = '_session';
    /**
     * @var mixed
     */
    protected $handler;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var bool
     */
    protected $generate = true;
    /**
     * @var int
     */
    protected $expire = 35000;

    /**
     * Session constructor.
     */
    public function __construct()
    {
        $config = config('app.session');
        static::$sessionName = $config['name'];
        $this->expire = $config['expire'];
        $this->handler = $this->createFromFactory($config['driver'], 'session', true);
        session_set_save_handler($this->handler, true);
    }

    /**
     * @return false|string
     */
    public function generate()
    {
        return Str::random(40);
    }

    public function start(): void
    {
        $this->isLegal();
        if ($this->generate) {
            $this->reGenerate();
        }
    }

    private function reGenerate(): void
    {
        $this->setId($this->generate());
        $this->setCookie();
    }

    private function isLegal(): void
    {
        $id = Cookie::get(static::$sessionName);
        if (!empty($id) && ($data = $this->handler->read($id)) !== null) {
            $this->setId($id);
            $this->setData($data);
            $this->generate = false;
        }
    }

    /**
     * @param $key
     * @param string $default
     *
     * @return mixed|string
     */
    public function get($key, $default = '')
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param $key
     * @param string $default
     *
     * @return mixed|string
     */
    public function getFlash($key, $default = '')
    {
        return $this->data['b__current__p'][$key] ?? $default;
    }

    /**
     * @param $id
     */
    private function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param $data
     */
    public function setData($data): void
    {
        $this->data = !empty($this->data) ? Arr::merge($data, $this->data, false) : $data;
    }

    private function setCookie(): void
    {
        Cookie::set(static::$sessionName, $this->id, $this->expire);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value): void
    {
        $this->setData([(string) $key => $value]);
    }

    /**
     * @param $key
     * @param $value
     */
    public function flash($key, $value): void
    {
        $this->push('b__next__p', [$key => $value]);
    }

    private function reFlash(): void
    {
        //重新加入current
        $this->set('b__next__p', $this->forget('b__current__p'));
    }

    private function clearFlash(): void
    {
        //清楚flash
        $this->delete('b__current__p');
        //next变成current
        $next = $this->forget('b__next__p');
        if (!empty($next)) {
            $this->set('b__current__p', $next);
        }
    }

    public function delete($key): void
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    public function forget($key)
    {
        $value = $this->data[$key];
        $this->delete($key);

        return $value;
    }

    public function push($key, $value = []): void
    {
        if (!isset($this->data[$key]) || null === $this->data[$key]) {
            $this->data[$key] = [];
        }
        $this->data[$key] = array_merge($this->data[$key], is_array($value) ? $value : [$value]);
    }

    private function save()
    {
        return $this->handler->write($this->id, $this->data);
    }

    public function end(): void
    {
        $this->clearFlash();
        $this->save();
    }

    public function flush(): void
    {
        $this->data = [];
        $this->handler->destroy($this->id);
        $this->reGenerate();
    }
}
