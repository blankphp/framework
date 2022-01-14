<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Contract;

interface Request
{
    public function get($name, $default = '');

    public function getUri();

    public function getMethod();

    public function file($name = '');

    public function getUserAgent();

    public function userIp();

    public function getLanguage();

    public function stripSlashesDeep($value);
}
