<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/11
 * Time: 13:22
 */
namespace BlankPhp\Contract;

use BlankPhp\Application;
use BlankPhp\Route\Router;

interface Kernel{

    public function __construct(Application $app);
    public function handle($request);

}