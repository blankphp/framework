<?php


namespace BlankPhp\Provider;


use BlankPhp\Application;

class RegisterProvider
{
    protected $providers = [
    ];

    public function getProviders(): void
    {
        $this->providers = array_merge($this->providers, config('app.providers'));
    }

    public function register(Application $app): void
    {
        $this->boot($app);
        $this->getProviders();
        foreach ($this->providers as $provider) {
            $app->call($provider);
        }
    }

    public function boot(Application $app): void
    {
        foreach (config('app.alice') as $name => $class) {
            $app->alice($name, $class);
        }
    }


}