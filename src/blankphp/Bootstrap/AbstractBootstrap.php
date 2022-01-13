<?php

namespace BlankPhp\Bootstrap;

use BlankPhp\Application;

abstract class AbstractBootstrap
{
    public abstract function boot(Application $app);

}