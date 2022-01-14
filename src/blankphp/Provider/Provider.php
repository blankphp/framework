<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Provider;

use BlankPhp\Application;
use BlankPhp\Contract\ProviderContract;

class Provider implements ProviderContract
{
    protected $app;
    /**
     * @var array
     */
    protected $command = [];
    /**
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        $this->app = Application::getInstance();
        if (method_exists($this, 'boot')) {
            $this->boot();
        }
        if (method_exists($this, 'register')) {
            $this->register();
        }
    }

    public function boot()
    {
    }
}
