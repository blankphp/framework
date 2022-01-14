<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
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
