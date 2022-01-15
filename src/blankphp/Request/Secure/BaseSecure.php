<?php

namespace BlankPhp\Request\Secure;

abstract class BaseSecure
{
    abstract public function runBefore($data);

    abstract public function runAfter($data);
}
