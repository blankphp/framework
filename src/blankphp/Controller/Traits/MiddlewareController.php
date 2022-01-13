<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/15
 * Time: 14:48
 */

namespace BlankPhp\Controller\Traits;


trait MiddlewareController
{
    protected $middleware = [];

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function middleware(): void
    {
        $middleware = func_get_args();
        if (is_array($middleware)) {
            foreach ($middleware as $m) {
                $this->middleware[] = $m;
            }
        } else {
            $this->middleware[] = $middleware;
        }
    }
}