<?php

namespace Helpers;

class Str
{
    static public function makeClassName($name, $namespace = '')
    {
        return $namespace . ucfirst($name);
    }
}