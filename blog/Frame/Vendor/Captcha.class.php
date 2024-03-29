<?php

    ############################
    #   验证码工具类
    ############################

    namespace Frame\Vendor;


    // 定义最终的验证码类
    final class Captcha {
        
        // 私有的成员属性
        private $codelen;       // 验证码长度
        private $code;          // 验证码字符串
        private $width;         // 图片的宽度
        private $height;        // 图片的高度
        private $fontsize;      // 字体大小
        private $fontfile;      // 字体文件
        private $img;           // 图像资源（画布）


        // 公共的构造方法
        public function __construct($codelen=4, $width=85, $height=22, $fontsize=18) {
            $this->codelen = $codelen;
            $this->width = $width;
            $this->height = $height;
            $this->fontsize = $fontsize;
            // 字体文件路径（必须是绝对路径）
            $this->fontfile = ROOT_PATH."Public".DS."Admin".DS."Images".DS."msyh.ttf";

            $this->createCode();    // 生成随机的验证码字符串
            $this->createImg();     // 创建画布
            $this->createBg();      // 画布背景
            $this->createFont();    // 写入文本
            $this->outPut();        // 输出图像
        }


        // 生成随机的验证码字符串
        private function createCode() {
            $arr1 = array_merge(range('a','z'), range(0,9), range('A','Z'));    // 范围数组
            shuffle($arr1);     // 打乱数组
            $arr2 = array_rand($arr1, $this->codelen);   // 随机的长度的数组下标
            $str = '';
            foreach($arr2 as $index) {
                $str .= $arr1[$index];
            }
            // 赋值生成最终的随机字符串
            $this->code = $str;
        }


        // 创建画布
        private function createImg() {
            $this->img = imagecreatetruecolor($this->width, $this->height);
        }


        // 绘制画布背景
        private function createBg() {
            // 分配颜色
            $color1 = imagecolorallocate($this->img, mt_rand(200,250), mt_rand(200,250), mt_rand(200,255));
            // 绘制带背景的矩形
            imagefilledrectangle($this->img, 0,0, $this->width, $this->height, $color1);
            // 绘制像素点
            for($i=1; $i<=200; $i++) {
                $color2 = imagecolorallocate($this->img, mt_rand(0,250), mt_rand(0,250), mt_rand(50,255));
                imagesetpixel($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), $color2);
            }
            // 绘制线段
            for($i=1; $i<10; $i++) {
                $color3 = imagecolorallocate($this->img, mt_rand(0,250), mt_rand(0,250), mt_rand(50,255));
                imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color3);
            }
        }
        

        // 写入文本到图像上
        private function createFont() {
            $color4 = imagecolorallocate($this->img, mt_rand(0,250), mt_rand(0,250), mt_rand(50,255));
            imagettftext($this->img, $this->fontsize, 0,10,20, $color4, $this->fontfile, $this->code);
        }


        // 输出图像
        private function outPut() {
            header("content-type:image/png");
            imagepng($this->img);       // 生成图像
            imagedestroy($this->img);   // 销毁图像
        }


        // 公共的获取验证码字符串的方法
        public function getCode() {
            return strtolower($this->code);
        }


    }



?>