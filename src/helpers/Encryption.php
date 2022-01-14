<?php

/*
 * This file is part of the /blankphp/framework.
 *
 * (c) 沉迷 <1136589038@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace BlankQwq\Helpers;

class Encryption
{
    public const IV = ''; //16位
    public static $key = 'sadklnxvnzlxjdawsen2l32194093j';

    /**
     * 解密字符串.
     *
     * @param string $data 字符串
     * @param string $key  加密key
     * @param string $iv
     */
    public static function decryp($data, $key = null, $iv = self::IV): string
    {
        return openssl_decrypt(base64_decode($data), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 加密字符串.
     *
     * @param string $data 字符串
     * @param string $key  加密key
     * @param string $iv
     */
    public static function encrypt($data, $key = null, $iv = self::IV): string
    {
        return base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * @param $password
     */
    public static function bcrypt($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param $password
     * @param $hash
     */
    public function check($password, $hash): string
    {
        return password_verify($password, $hash);
    }
}
