<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10
 * Time: 21:33
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