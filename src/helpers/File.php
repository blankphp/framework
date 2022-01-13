<?php

namespace BlankQwq\Helpers;

class File
{
    //写入文件
    public static function put($fileName, $data)
    {
        //写入文件
        $dir = dirname($fileName);
        //判断目录是否存在
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }
        //写入文件
        return file_put_contents($fileName, $data);
    }

    public static function append($fileName, $data)
    {
        //写入文件
        $dir = dirname($fileName);
        //判断目录是否存在
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }
        //写入文件
        return file_put_contents($fileName, $data, FILE_APPEND);
    }

    //读取文件
    public static function get($name, $type = 'text')
    {

    }

    public static function delete($fileName)
    {
        //删除
        return unlink($fileName);
    }

    public static function putCache(\BlankPhp\Route\RouteCollection $routes, string $string)
    {
    }

}