<?php

namespace Helpers;

class File
{
    //写入文件
    static public function put($fileName, $data)
    {
        //写入文件
        $dir = dirname($fileName);
        //判断目录是否存在
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        //写入文件
        return file_put_contents($fileName, $data);
    }

    static public function append($fileName, $data)
    {
        //写入文件
        $dir = dirname($fileName);
        //判断目录是否存在
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        //写入文件
        return file_put_contents($fileName, $data, FILE_APPEND);
    }

    //读取文件
    static public function get($name, $type = "text")
    {

    }

    static public function delete($fileName)
    {
        //删除
        return unlink($fileName);
    }

}