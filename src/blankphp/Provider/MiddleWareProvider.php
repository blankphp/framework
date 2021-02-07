<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/14
 * Time: 9:04
 */

namespace BlankPhp\Provider;

class MiddleWareProvider extends Provider
{
    protected $namespace = 'App\Middleware';
    protected $middleware = [

    ];
    protected $registerMiddleware;
    protected $groupMiddleware = [];

    public function getMiddleware($group)
    {
        if (isset($this->groupMiddleware[$group]))
            return $this->groupMiddleware[$group];
    }

    public function boot(): void
    {
        $this->app->signal('GroupMiddleware', $this->groupMiddleware);
        $this->app->signal('AliceMiddleware', $this->registerMiddleware);
    }


    public function getAliceMiddleWare($alice): array
    {
        $temp = [];
        foreach ($alice as $item) {
            if (isset($this->registerMiddleware[$item])) {
                $temp[] = $this->registerMiddleware[$item];
            }
        }
        return $temp;
    }

}