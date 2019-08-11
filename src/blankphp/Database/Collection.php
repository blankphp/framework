<?php


namespace Blankphp\Database;
use \Blankphp\Collection\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * 绑定之前的数据操作
     */
    public function binds(){

    }

    /**
     * 取出某一列数据
     */
    public function pluck(){
        $args = func_num_args();
        //从item中获取数据
    }

    /***
     * 关联数据放入
     */
    public function relation(){

    }


}