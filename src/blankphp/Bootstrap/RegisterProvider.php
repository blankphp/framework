<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Bootstrap;

use BlankPhp\Application;
use function config;

class RegisterProvider
{
    protected $providers = [
    ];

    public function getProviders(): void
    {
        $this->providers = array_merge($this->providers, config('app.providers', []));
    }

    public function register(Application $app): void
    {
        $this->getProviders();
    }

    public function boot(Application $app): void
    {
        $this->register($app);
        foreach (config('app.alice', []) as $name => $class) {
            $app->alice($name, $class);
        }
    }
}
