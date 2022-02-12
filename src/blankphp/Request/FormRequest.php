<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Request;

class FormRequest
{
    private $request;

    public function __construct(\BlankPhp\Contract\Request $request)
    {
        $this->request = $request;
    }

    public function __call(string $name, array $arguments)
    {
        // call request
        // TODO: Implement __call() method.
        return $this->request->{$name}(...$arguments);
    }
}
