<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankPhp\Database\Grammar;

use BlankPhp\Database\Query\Builder;

class MysqlGrammar extends Grammar
{
    public static $index = ['default'];

    public function generateSelect(Builder $sql, $parameter = [])
    {
        //依旧拼接
        //拼装语句
        $sqlString = '';
        //终极大拼装= = 没有过滤很致命
        $sqlString .= 'select '.implode(',', $sql->select).' from '.$sql->table;
        if (!is_null($sql->wheres)) {
            $sqlString .= ' where '.implode(' ', $sql->wheres);
        }
        if (!is_null($sql->groupBy)) {
            $sqlString .= ' group by '.implode(' ', $sql->groupBy);
        }
        if (!is_null($sql->orderBy)) {
            $sqlString .= ' order by '.implode(' ', $sql->orderBy);
        }
        if (!is_null($sql->limit)) {
            $sqlString .= ' limit '.$sql->limit;
        }

        return $sqlString;
    }

    public function generateUpdate(Builder $sql, $parameter = [])
    {
        //拼装语句
        $sqlString = '';
        //终极大拼装
        $sqlString .= 'update '.$sql->table;
        if (!is_null($sql->values)) {
            $sqlString .= ' set '.implode(', ', $sql->values);
        }

        if (!is_null($sql->wheres)) {
            $sqlString .= ' where '.implode(' ', $sql->wheres);
        }

        return $sqlString;
    }

    public function generateDelete(Builder $sql, $parameter = [])
    {
        //拼装语句
        $sqlString = '';
        //终极大拼装
        $sqlString .= 'delete from '.$sql->table;
        if (!is_null($sql->wheres)) {
            $sqlString .= ' where '.implode(' ', $sql->wheres);
        }
        if (!is_null($sql->orderBy)) {
            $sqlString .= 'order by '.implode(' ', $sql->orderBy);
        }

        return $sqlString;
    }

    public function generateAlter(Builder $sql, $parameter = [])
    {
    }

    public function generateInsert(Builder $sql, $parameter = [])
    {
        //拼装语句
        $sqlString = '';
        //终极大拼装
        if (!is_numeric(($sql->values[0][0]))) {
            $sqlString .= 'insert '.$sql->table.'('.implode(',', $sql->values[0]).')'.' values(:'.implode(',:', $sql->values[0]).')';
        } else {
            $sqlString .= 'insert '.$sql->table.' values'.'('.implode(',', array_fill(1, count($sql->values[0]), '?')).')';
        }

        return $sqlString;
    }

    public function generateCreate(Builder $sql, $parameter = [])
    {
        $sqlString = '';
        //终极大拼装
        $sqlString .= 'create '.$sql->createType.'  '.$sql->table.'('.implode(',', $sql->columns).')';

        return $sqlString;
    }
}
