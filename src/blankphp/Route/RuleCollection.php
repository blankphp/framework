<?php


namespace BlankPhp\Route;


use BlankPhp\Collection\Collection;

class RuleCollection extends Collection
{

    public function add($obj, $uri, $methods)
    {
        foreach ($methods as $method) {
            $this->item[$uri][$method] = $obj;
        }
    }

    public function toArray()
    {
        $temp = [];
        foreach ($this->item as $k => $v) {
            foreach ($v as $method => $route) {
                $temp[$k][$method] = $route->toArray();
            }
        }
        return $temp;
    }


}