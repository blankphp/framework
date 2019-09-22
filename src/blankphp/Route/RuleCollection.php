<?php


namespace Blankphp\Route;


use Blankphp\Collection\Collection;

class RuleCollection extends Collection
{

    public function toArray()
    {
        $temp = [];

        foreach ($this->item as $rule) {
            //规则
            if (is_array($rule->method)){
                foreach ($rule->method as $method){
                    $temp[$rule->rule][$method][] = [
                        'action' => $rule->action,
                        'middleware' => [
                            'alice'=>$rule->middleware,
                            'group' => $rule->middlewareGroup,
                        ],
                    ];
                }
            }else{
                $temp[$rule->rule][$rule->method][] = [
                    'action' => $rule->action,
                    'middleware' => [
                        'alice'=>$rule->middleware,
                        'group' => $rule->middlewareGroup,
                    ],
                ];
            }
        }
        $this->item = $temp;
    }

}