<?php


namespace BlankPhp\Route;


use BlankPhp\Collection\Collection;

class RouteCollection extends Collection
{

    private $rules = [];

    private function insert($url,$method,$rule){
        $this->item[$url][$method] = $rule;
    }

    private function addRules($rule){
        $this->rules[] = $rule;
    }

    private function parseRules(){
        /** @var RouteRule $rule */
        foreach ($this->rules as $rule){
            $this->insert($rule->getUrl(),$rule->getMethod(),$rule);
        }
        $this->rules = [];
    }

    public function pregUri($uri)
    {
        $this->parseRules();
        foreach ($this->item as $k=>$v){
            if (preg_match("#^$k$#", $uri, $match)) {
                return $v;
            }
        }
    }

    public function __toArray(): array
    {
        $this->parseRules();
        return $this->item;
    }

    public function add(RouteRule $routeRule): RouteRule
    {
        $this->addRules($routeRule);
        return $routeRule;
    }

    public function addGroup(RuleGroup $ruleGroup): RuleGroup
    {
        foreach ($ruleGroup->getAll() as $item){
            $this->addRules($item);
        }
        return $ruleGroup;
    }

}