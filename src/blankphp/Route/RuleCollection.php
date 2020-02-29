<?php


namespace Blankphp\Route;


use Blankphp\Collection\Collection;

class RuleCollection extends Collection
{

    public function add($obj, $uri, $methods)
    {
        foreach ($methods as $method){
            $this->item[$uri][$method] = $obj;
        }
    }

//


}