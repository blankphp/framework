<?php


namespace Blankphp\Database;

use \Blankphp\Collection\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * 绑定之前的数据操作
     */
    public function binds()
    {
        //存储数据库对象
    }

    /**
     * 取出某一列数据
     */
    public function pluck()
    {
        $args = func_get_args();
        $temp =[];
        $i= 0;
       foreach ($this->item as $item){
           foreach ($args as $arg)
                $temp[$i][$arg]=$item->$arg;
           $i++;
       }
        return $temp;
    }

    /***
     * 关联数据放入
     */
    public function relation()
    {

    }


}