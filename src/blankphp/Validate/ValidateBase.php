<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Validate;

use BlankPhp\Contract\Request;

class ValidateBase
{
    protected $validateRule = [];
    public static $rule = [
    ];

    public function validate(Request $request, array $array, array $message = []): void
    {
    }

    public function add(): void
    {
    }

    public function replace(): void
    {
    }
}
