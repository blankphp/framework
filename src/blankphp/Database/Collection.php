<?php


namespace BlankPhp\Database;

use \BlankPhp\Collection\Collection as BaseCollection;

/**
 * Class Collection
 * @package BlankPhp\Database
 */
class Collection extends BaseCollection
{

    protected $table = null;
    protected $select = null;

    /**
     * @return null
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param null $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @return null
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param null $select
     */
    public function setSelect($select)
    {
        $this->select = $select;
    }

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
        $temp = [];
        $i = 0;
        foreach ($this->item as $item) {
            foreach ($args as $arg)
                $temp[$i][$arg] = $item->$arg;
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