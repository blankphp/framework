<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Route\Traits;

use ReflectionFunctionAbstract;

trait ResolveSomeDepends
{
    /**
     * @throws \ReflectionException
     */
    public function resolveClassMethodDependencies($parameters, $instance, $method): array
    {
        //解决类方法的依赖-->反射解决
        if (!empty($parameters)) {
            return $this->resolveMethodDependencies(
                $parameters,
                'Closure' !== $instance ?
                new \ReflectionMethod($instance, $method) : new \ReflectionFunction($method)
            );
        }

        return $this->resolveMethodDependencies(
            $parameters,
            'Closure' !== $instance ?
                new \ReflectionMethod($instance, $method) : new \ReflectionFunction($method)
        );
    }

    public function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector): array
    {
        $instanceCount = 0;
        $values = array_values($parameters);
        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = $parameter->getType();
            if (!is_null($instance)) {
                array_splice(
                    $parameters,
                    $key,
                    $instanceCount,
                    [app($instance->getName(), [$values[$instanceCount]])]
                );
            } elseif (!isset($values[$key - $instanceCount]) &&
                $parameter->isDefaultValueAvailable()) {
                array_splice($parameters, $key, $instanceCount, [$parameter->getDefaultValue()]);
                ++$instanceCount;
            }
        }

        return $parameters;
    }
}
