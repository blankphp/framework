<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\PipeLine;

use BlankPhp\Exception\NotFoundClassException;

class PipeLine
{
    /**
     * @var array
     */
    protected $middleware;

    /**
     * @var mixed
     */
    protected $data;

    public function __construct()
    {
    }

    public function send($data): PipeLine
    {
        $this->data = $data;

        return $this;
    }

    public function getAlice(): callable
    {
        return function ($stack, $pipe, $method = 'handle') {
            return function () use ($stack, $pipe, $method) {
                if (!is_object($pipe)) {
                    $pipe = new $pipe();
                }
                if (!method_exists($pipe, $method)) {
                    throw new NotFoundClassException("Can't found {$method}->{$pipe} ");
                }

                return $pipe->$method($this->data, $stack);
            };
        };
    }

    public function process(): void
    {
    }

    public function wait(): void
    {
    }

    public function through($middleware): PipeLine
    {
        //管道模式运行
        $this->middleware = is_array($middleware) ? $middleware : func_get_args();

        return $this;
    }
}
