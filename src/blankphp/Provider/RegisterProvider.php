<?php


namespace BlankPhp\Provider;


use BlankPhp\Application;

class RegisterProvider
{
    protected $providers = [
    ];

    public function getProviders()
    {
        $this->providers = array_merge($this->providers, config('app.providers'));
    }

    public function register(Application $app)
    {
        $this->boot($app);
        $this->getProviders();
        foreach ($this->providers as $provider) {
            $app->call($provider);
        }
    }

    public function boot(Application $app)
    {
        foreach (config('app.alice') as $name => $class) {
            $app->alice($name, $class);
        }
    }


}