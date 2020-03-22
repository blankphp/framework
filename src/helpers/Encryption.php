<?php


namespace BlankQwq\Helpers;


class Encryption
{
    public const IV = '';//16位
    public static $key = 'sadklnxvnzlxjdawsen2l32194093j';



    /**
     * 解密字符串
     * @param string $data 字符串
     * @param string $key 加密key
     * @param string $iv
     * @return string
     */
    public static function decryp($data, $key = null, $iv = self::IV): string
    {
        return openssl_decrypt(base64_decode($data), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 加密字符串
     * @param string $data 字符串
     * @param string $key 加密key
     * @param string $iv
     * @return string
     */
    public
    static function encrypt($data, $key = null, $iv = self::IV): string
    {
        return base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * @param $password
     * @return string
     */
    public static function bcrypt($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param $password
     * @param $hash
     * @return string
     */
    public function check($password, $hash): string
    {
        return password_verify($password, $hash);
    }

}