<?php


namespace BlankPhp\Bootstrap;


use BlankPhp\Application;
use function config;

class RegisterProvider
{
    protected $providers = [
    ];

    public function getProviders(): void
    {
        $this->providers = array_merge($this->providers, config('app.providers',[]));
    }

    public function register(Application $app): void
    {
        $this->getProviders();

    }

    public function boot(Application $app): void
    {
        $this->register($app);
        foreach (config('app.alice',[]) as $name => $class) {
            $app->alice($name, $class);
        }
    }


}