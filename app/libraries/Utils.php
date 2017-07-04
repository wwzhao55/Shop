<?php namespace App\libraries;
use Auth,Config;

/**
 * Created by PhpStorm.
 * User: taos
 * Date: 14-9-3
 * Time: 下午10:08
 */
Class Utils {

    public static function htmlRemoveImageTag($string, $return = 'img') {
        if (preg_match_all('/<img(.*?)src="(.*?)"(.*?)>/', $string, $matches, PREG_SET_ORDER)) {
            if ($return == 'img') {
                return $matches[0][2];
            } else if ($return == 'string') {
                $string = preg_replace('/<img(.*?)>/', '', $string);
                return $string;
            }
            else
                return false;
        }
        return false;
    }

    public static function htmlRemove($string, $length = 0, $replace = '...') {
        $removeString = '';

        $string = self::htmlSubStr($string, $length, $replace);

        if (preg_match_all('/<p>(.*?)<\/p>/', $string, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $matchKey => $match) {
                if ($matchKey != 0 or count($matches) == 1)
                    $removeString .= $match[1];
            }
            if (count($matches) > 1)
                return '<h3>' . $matches[0][1] . '</h3><p>' . $removeString . '</p>';
            else
                return '<p>' . $removeString . '</p>';
        }
        return $string;
    }

    public static function htmlSubStr($string, $length = 0, $replace = '...') {
        // 先判断文章是否包含图片 将图片img标签去掉
        if ($removeImageTag = self::htmlRemoveImageTag($string, 'string')) {
            $string = $removeImageTag;
        }

        // 截取指定长度的字符串，支持中文
        if (strlen($string) < $length) {
            $string = substr($string, 0);
        } else {
            $char = ord($string [$length - 1]);
            if ($char >= 224 && $char <= 239) {
                $string = substr($string, 0, $length - 1);
            } else {
                $char = ord($string [$length - 2]);
                if ($char >= 224 && $char <= 239) {
                    $string = substr($string, 0, $length - 2);
                } else {
                    $string = substr($string, 0, $length);
                }
            }
        }

        // 开始标签集合,当前开始标签字符串(a,span,div...),结束标签集合
        $starts = $start_str = $ends = array();
        // 提取截取的字符串中出现的开始标签结合和结束标签集合
        preg_match_all('/<\w+[^>]*>?/', $string, $starts, PREG_OFFSET_CAPTURE);
        preg_match_all('/<\/\w+>/', $string, $ends, PREG_OFFSET_CAPTURE);

        // 初始化<a title="查看与字符串截取有关的文章" href="http://cuelog.com/tag/%e5%ad%97%e7%ac%a6%e4%b8%b2%e6%88%aa%e5%8f%96" target="_blank">字符串截取</a>点
        $cut_pos = 0;
        // 最后追加的字符串
        $last_str = '';

        if (!empty($starts [0])) {
            $starts = array_reverse($starts [0]);
            if (!empty($ends [0])) {
                $ends = $ends [0];
            }

            foreach ($starts as $sk => $s) {
                // 判断开始标签是否包括XHTML语法的闭合标签<img alt="">
                $auto = false;
                if ($auto != false && $auto = strripos($s [0], '/>')) {
                    // 如果有，则将<a title="查看与字符串截取有关的文章" href="http://cuelog.com/tag/%e5%ad%97%e7%ac%a6%e4%b8%b2%e6%88%aa%e5%8f%96" target="_blank">字符串截取</a>点设置为当前标签的起始位置
                    if ($cut_pos < $auto) {
                        $cut_pos = $s [1];
                        $last_str = $s [0];
                        unset($starts [$sk]);
                    }
                } else {
                    // 提取开始标签名：a,div,span...
                    preg_match('/<(\w+).*>?/', $s [0], $start_str);
                    if (!empty($ends)) {
                        foreach ($ends as $ek => $e) {
                            // 提取结束标签名
                            $end_str = trim($e [0], '</>');
                            // 如果开始标签名与结束标签名一致，并且结束标签的索引值比开始标签的索引值大，则该标签是完整的有效.
                            if ($end_str == $start_str [1] && $e [1] > $s [1]) {
                                // 如果字符串截取点还没有确定，给它赋值
                                if ($cut_pos < $e [1]) {
                                    $cut_pos = $e [1];
                                    // 并且将闭合标签作为最后的字符串追加
                                    $last_str = $e [0];
                                }
                                // 将这个正确的标签去掉结束标签，并且滚入下一个开始标签的判断
                                unset($ends [$ek]);
                                break;
                            }
                        }
                    } else {
                        /*
                         * 如果empty($ends)，说明第一个开始标签没有对应的闭合标签 说明这一段截取的内容不完整，只能将字符串截取到第一个开始标签前为止
                         */
                        $last_str = '';
                        $cut_pos = $s [1];
                    }
                }
            }
            // 拼凑剩余的字符串
            $res_str = substr($string, 0, $cut_pos) . $last_str;
            $less_str = substr($string, strlen($res_str));
            $less_pos = strpos($less_str, '<');
            $less_str = $less_pos !== false ? substr($less_str, 0, $less_pos) : $less_str;
            $string = $res_str . $less_str . $replace;
        }

        return $string;
    }

    
    
    public static function array_firstadd($array1,$array2){
        foreach($array1 as $k=>$val){
            $array2[$k]=$val;
        }
        ksort($array2);
        return $array2;
    }
    public static function put_img($filename){
        return "http://".$_SERVER['HTTP_HOST']."/storage/uploads/". $filename;
    }
    
    /**
     * 过滤空值
     */
    public static function array_remove_value($arr,$param=null){
        $result = null;
        if($arr)foreach($arr as $key=>$value){
            if($value!=$param && $value) $result[$key]=$value;
        }
        return $result;
    }

    /*整理错误信息
     *
     */
    public static function array_error_msg($errmsg){
        $errormsg = [];
        if($errmsg){
            foreach($errmsg as $val){
                if(is_array($val)){
                    foreach($val as $key=>$msg){
                       $errormsg[$key] = $msg;
                    }
                }
            }
            return $errormsg;
        }else{
            return $errmsg;
        }
    }
    
    /* 生成验证码图
     * @return str
    **/
    public static function drawingCode() {
        $num = 4;
        $size = 20;
        $width = 0;
        $height = 0;
        !$width && $width = $num * $size * 4 / 5 + 5;
        !$height && $height = $size + 10; 
        // 去掉了 0 1 O l 等
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
        $code = '';
        for ($i = 0; $i < $num; $i++) {
            $code .= $str[mt_rand(0, strlen($str)-1)];
        } 
        // 画图像
        $im = imagecreatetruecolor($width, $height); 
        // 定义要用到的颜色
        $back_color = imagecolorallocate($im, 235, 236, 237);
        $boer_color = imagecolorallocate($im, 118, 151, 199);
        $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120)); 
        // 画背景
        imagefilledrectangle($im, 0, 0, $width, $height, $back_color); 
        // 画边框
        imagerectangle($im, 0, 0, $width-1, $height-1, $boer_color); 
        // 画干扰线
        for($i = 0;$i < 5;$i++) {
            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $font_color);
        } 
        // 画干扰点
        for($i = 0;$i < 50;$i++) {
            $font_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $font_color);
        } 
        // 画验证码
        @imagefttext($im, $size , 0, 5, $size + 3, $text_color, public_path('font/simsun.ttc'), $code);
        
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png;charset=gb2312");
        imagepng($im);
        imagedestroy($im);
        return $code;
    }

    public static function getMenu(){
        $menu = null;
        if(Auth::user()){
            $position = Auth::user()->position;
            $config = Config::get("position_html");
            if($position == 3){
                $menu = '<li class="sidebar-label pt15">小店管理人员</li>';
                $authority = json_decode(Auth::user()->authority);
                if($authority)foreach($authority as $key){
                    $menu .=  $config[$position][$key];
                }
            }else{
              $menu = $config[$position];
            }
        }
        return $menu;
        
    }
    
    public static function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }
    
    public static function getFileByDir($dir){
        /*
        if(is_dir($dir)){
            //Utils::getFileByDir
            $name = ;
            while($name != '.' && $name != '..'){
                if(is_dir(real_path($name))){
                    Utils::getFileByDir(...);
                }else{
                    ...
                }
            }
        }else{
            return false;
        }
        */
    }
    
}
