<?php


namespace BlankQwq\Helpers;

use BlankPhp\Response\Response;

/**
 * Class GD
 * @package BlankQwq\Helpers
 * 图形库
 */
class GD
{
    /**
     * 构建验证码
     * @param int $stringLen
     * @param string $fileName
     * @param int $pointNum
     * @param int $linNum
     * @param int $height
     * @param int $width
     * @return false|string
     * @throws \Exception
     */
    public static function captcha($stringLen = 4, $pointNum = 50, $linNum = 4, $fileName = null, $height = 40, $width = 120)
    {
        $img = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($img, random_int(200, 255), random_int(200, 255), random_int(200, 255));
        //填充颜色
        imagefill($img, 0, 0, $color);
        //噪点
        for ($m = 0; $m <= $pointNum; $m++) {
            $piccolo = imagecolorallocate($img, random_int(0, 255), random_int(0, 255), random_int(0, 255));//点的颜色
            imagesetpixel($img, random_int(0, $width - 1), random_int(0, $height - 1), $piccolo);// 水平地画一串像素点
        }

        for ($i = 0; $i <= $linNum; $i++) {
            $lincoln = imagecolorallocate($img, random_int(0, 255), random_int(0, 255), random_int(0, 255));//线的颜色
            imageline($img, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lincoln);//画一条线段
        }
        $string = Str::random($stringLen, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz');
        $colorString = imagecolorallocate($img, random_int(10, 100), random_int(10, 100), random_int(10, 100));//文本
        imagestring($img, 5, random_int(0, $width - 36), random_int(0, $height - 15), $string, $colorString);

//输出图片到浏览器
        header(Response::$header['image']);
        imagejpeg($img, $fileName);
//销毁，释放资源
        imagedestroy($img);
        return $string;
    }

    public
    static function image()
    {

    }

    public
    static function watermark()
    {

    }


}