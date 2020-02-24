<?php


namespace Blankphp\Model\Traits;


trait belongsTo
{
    //谁属于谁
    public function belongTo($table,$localColumn,$foreignColumn){
        //表连接这里1：1就采用内连接,并把数据返回到Collection

        //多就查询多条放入数据
    }

}