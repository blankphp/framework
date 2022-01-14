<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Database\Grammar;

//语法生成器
use BlankPhp\Database\Query\Builder;

abstract class Grammar
{
    abstract public function generateSelect(Builder $sql, $parameter = []);

    abstract public function generateUpdate(Builder $sql, $parameter = []);

    abstract public function generateDelete(Builder $sql, $parameter = []);

    abstract public function generateAlter(Builder $sql, $parameter = []);

    abstract public function generateInsert(Builder $sql, $parameter = []);
}
