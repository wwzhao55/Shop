<?php namespace App\libraries;


class Image {

    var $g_img;
    var $g_w;
    var $g_h;
    var $img;
    var $save_name;
    var $g_type;
    var $pos;
    var $w_img;
    var $transition;
    var $jpeg_quality;
    var $water_margin;
    var $text;
    var $text_size;
    var $text_font;
    var $text_color;
    var $text_angle = 0;
    var $t_x = 0;
    var $t_y = 0;
    var $make = true;

    function Image($g_img) {
        $this->g_img = $g_img;
        $info = getimagesize($this->g_img);
        if (!$info) {
            $this->make = false;
            return false;
        }
        $this->g_type = $info[2];
        if ($this->g_type == 1 && (!function_exists('imagegif') || !function_exists('imagecreatefromgif'))) {
            $this->make = false;
            return false;
        }
        $this->img = $this->createimage($this->g_type, $this->g_img);
        if (!$this->img) {
            $this->make = false;
            return false;
        }
        $this->g_w = $info[0];
        $this->g_h = $info[1];
        $this->pos = 1;
        $this->w_img = '/assets/img/logos/logo.png';
        $this->transition = 65;
        $this->jpeg_quality = 80;
        $this->text = 'BrandHead-MS';
        $this->text_size = 20;
        $this->text_font = '/fonts/fontawesome-webfont.ttf';
        $this->text_color = '#000000';
        $this->water_margin = 10;
    }
    function waterimage2($save_name = ''){
        $this->pos = 9;
        
        $this->w_img = realpath('storage/uploads/')."/logo.png";
        //var_dump(realpath('storage/uploads/')."/logo.png");
        is_file($this->w_img) or $this->make = false;
        if (!$this->make)
            return false;
        $this->save_name = $save_name ? $save_name : $this->g_img;

        $info = getimagesize($this->w_img);
        $w_img = $this->createimage($info[2], $this->w_img);
        $w_w = $info[0];
        $w_h = $info[1];
        $temp_w_im = $this->get_pos('image', $w_w, $w_h);
        $w_im_x = $temp_w_im["dest_x"];
        $w_im_y = $temp_w_im["dest_y"];
        if ($info[2] == 1) {
            imagecopymerge($this->img, $w_img, $w_im_x, $w_im_y, 0, 0, $w_w, $w_h, $this->transition);
        } else {
            $b_img = imagecreatetruecolor($this->g_w, $this->g_h);
            imageCopy($b_img, $this->img, 0, 0, 0, 0, $this->g_w, $this->g_h);
            imageCopy($b_img, $w_img, $w_im_x, $w_im_y, 0, 0, $w_w, $w_h);
            $this->img = $b_img;
        }

        imagedestroy($w_img);
        $save_name = $this->save_name;
        imagejpeg($this->img, $save_name, $this->jpeg_quality);
        return true;
    }
    function waterimage($save_name = '') {
        is_file($this->w_img) or $this->make = false;
        if (!$this->make)
            return false;
        $this->save_name = $save_name ? $save_name : $this->g_img;
        $info = getimagesize($this->w_img);
        $w_img = $this->createimage($info[2], $this->w_img);
        $w_w = $info[0];
        $w_h = $info[1];
        $temp_w_im = $this->get_pos('image', $w_w, $w_h);
        $w_im_x = $temp_w_im["dest_x"];
        $w_im_y = $temp_w_im["dest_y"];
        if ($info[2] == 1) {
            imagecopymerge($this->img, $w_img, $w_im_x, $w_im_y, 0, 0, $w_w, $w_h, $this->transition);
        } else {
            $b_img = imagecreatetruecolor($this->g_w, $this->g_h);
            imageCopy($b_img, $this->img, 0, 0, 0, 0, $this->g_w, $this->g_h);
            imageCopy($b_img, $w_img, $w_im_x, $w_im_y, 0, 0, $w_w, $w_h);
            $this->img = $b_img;
        }
        imagedestroy($w_img);
        $this->save();
        return true;
    }

    function watertext($save_name = '') {
        global $DT;
        is_file($this->text_font) or $this->make = false;
        if (!$this->make)
            return false;
        $this->text = convert($this->text, DT_CHARSET, 'UTF-8');
        $this->save_name = $save_name ? $save_name : $this->g_img;
        $temp_text = $this->get_pos('text');
        $text_x = $temp_text['dest_x'];
        $text_y = $temp_text['dest_y'];
        if (preg_match("/([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i", $this->text_color, $color)) {
            $red = hexdec($color[1]);
            $green = hexdec($color[2]);
            $blue = hexdec($color[3]);
            $text_color = imagecolorallocate($this->img, $red, $green, $blue);
        } else {
            $text_color = imagecolorallocate($this->img, 255, 255, 255);
        }
        imagettftext($this->img, $this->text_size, $this->text_angle, $text_x, $text_y, $text_color, $this->text_font, $this->text);
        $this->save();
        return true;
    }

    function thumb($w = 0, $h = 0, $t = 0, $save_name = '') {
        if (!$this->make)
            return false;
        if (($w == $this->g_w && $h == $this->g_h) || ($w > $this->g_w && $h > $this->g_h)) {

            if ($save_name && $this->g_img != $save_name)
                move_uploaded_file($this->g_img, $save_name);
            return true;
        }
        $this->save_name = $save_name ? $save_name : $this->g_img;
        if ($t ? $this->resize($w, $h) : $this->cut($w, $h))
            $this->save();
        return true;
    }

    function save() {
        switch ($this->g_type) {
            case '1': imagegif($this->img, $this->save_name);
                break;
            case '3': imagepng($this->img, $this->save_name);
                break;
            default : imagejpeg($this->img, $this->save_name, $this->jpeg_quality);
                break;
        }
    }

    function cut($w = 0, $h = 0) {
        if (!$w || !$h)
            return false;
        $_w = intval($h * $this->g_w / $this->g_h);
        $_h = intval($w * $this->g_h / $this->g_w);
        $im = imagecreatetruecolor($w, $h);
        if ($w >= $this->g_w || $h >= $this->g_h) {
            imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
            $x = $w >= $this->g_w ? -intval(($this->g_w - $w) / 2) : 0;
            $y = $h >= $this->g_h ? -intval(($this->g_h - $h) / 2) : 0;
            imagecopy($im, $this->img, $x, $y, 0, 0, $this->g_w, $this->g_h);
        } else if ($_w >= $w) {
            $tb = imagecreatetruecolor($_w, $h);
            $x = intval(($_w - $w) / 2);
            imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $_w, $h, $this->g_w, $this->g_h);
            imagecopy($im, $tb, 0, 0, $x, 0, $w, $h);
        } else if ($_w < $w) {
            $tb = imagecreatetruecolor($w, $_h);
            $y = intval(($_h - $h) / 2);
            imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $w, $_h, $this->g_w, $this->g_h);
            imagecopy($im, $tb, 0, 0, 0, $y, $w, $h);
        } else {
            return false;
        }
        $this->img = $im;
        unset($im);
        if (isset($tb))
            unset($tb);
        return true;
    }

    function resize($w = 640, $h = 600) {
        if (!$w || !$h)
            return false;
        $_w = intval($h * $this->g_w / $this->g_h);
        $_h = intval($w * $this->g_h / $this->g_w);
        $im = imagecreatetruecolor($w, $h);
        imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));
        if ($this->g_w < $w) {
            if ($this->g_h <= $h) {
                $x = intval(($w - $this->g_w) / 2);
                $y = intval(($h - $this->g_h) / 2);
                imagecopy($im, $this->img, $x, $y, 0, 0, $this->g_w, $this->g_h);
            } else if ($this->g_h > $h) {
                $tb = imagecreatetruecolor($_w, $h);
                imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $_w, $h, $this->g_w, $this->g_h);
                $x = intval(($w - $_w) / 2);
                imagecopy($im, $tb, $x, 0, 0, 0, $_w, $h);
            }
        } else if ($this->g_w > $w) {
            if ($this->g_h <= $h) {
                $tb = imagecreatetruecolor($w, $_h);
                imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $w, $_h, $this->g_w, $this->g_h);
                $y = intval(($h - $_h) / 2);
                imagecopy($im, $tb, 0, $y, 0, 0, $w, $_h);
            } else if ($this->g_h > $h) {
                if ($_w >= $w) {
                    $tb = imagecreatetruecolor($w, $_h);
                    imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $w, $_h, $this->g_w, $this->g_h);
                    $y = intval(($h - $_h) / 2);
                    imagecopy($im, $tb, 0, $y, 0, 0, $w, $_h);
                } else {
                    $tb = imagecreatetruecolor($_w, $h);
                    imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $_w, $h, $this->g_w, $this->g_h);
                    $x = intval(($w - $_w) / 2);
                    imagecopy($im, $tb, $x, 0, 0, 0, $_w, $h);
                }
            }
        } else if ($this->g_w == $w) {
            if ($this->g_h <= $h) {
                $y = intval(($h - $this->g_h) / 2);
                imagecopy($im, $this->img, 0, $y, 0, 0, $this->g_w, $this->g_h);
            } else if ($this->g_h > $h) {
                $tb = imagecreatetruecolor($_w, $h);
                imagecopyresampled($tb, $this->img, 0, 0, 0, 0, $_w, $h, $this->g_w, $this->g_h);
                $x = intval(($w - $_w) / 2);
                imagecopy($im, $tb, $x, 0, 0, 0, $_w, $h);
            }
        } else {
            return false;
        }
        $this->img = $im;
        unset($im);
        if (isset($tb))
            unset($tb);
        return true;
    }

    function createimage($type, $img_name) {
        if ($type == 1) {
            return imagecreatefromgif($img_name);
        } else if ($type == 2) {
            return imagecreatefromjpeg($img_name);
        } else if ($type == 3) {
            return imagecreatefrompng($img_name);
        }
        return false;
    }

    function get_pos($type, $p_w = 0, $p_h = 0) {
        if ($type == 'text') {
            $line = count(explode("\n", $this->text));
            $temp = imagettfbbox($this->text_size, $this->text_angle, $this->text_font, $this->text);
            $p_w = $temp[2] - $temp[6];
            $p_h = $line * ($temp[3] - $temp[7]);
            unset($temp);
        }
        if (($this->g_w < $p_w) || ($this->g_h < $p_h))
            return false;
        switch ($this->pos) {
            case 1:
                $p_x = $this->water_margin;
                $p_y = ($type == 'image' ? $this->water_margin : $p_h) + $this->water_margin;
                break;
            case 2:
                $p_x = ($this->g_w - $p_w) / 2;
                $p_y = ($type == 'image' ? 0 : $p_h) + $this->water_margin;
                break;
            case 3:
                $p_x = $this->g_w - $p_w - $this->water_margin;
                $p_y = ($type == 'image' ? 0 : $p_h) + $this->water_margin;
                break;
            case 4:
                $p_x = $this->water_margin;
                $p_y = ($this->g_h - $p_h) / 2;
                break;
            case 5:
                $p_x = ($this->g_w - $p_w) / 2;
                $p_y = ($this->g_h - $p_h) / 2;
                break;
            case 6:
                $p_x = $this->g_w - $p_w - $this->water_margin;
                $p_y = ($this->g_h - $p_h) / 2;
                break;
            case 7:
                $p_x = $this->water_margin;
                $p_y = $this->g_h - $p_h - $this->water_margin;
                break;
            case 8:
                $p_x = ($this->g_w - $p_w) / 2;
                $p_y = $this->g_h - $p_h - $this->water_margin;
                break;
            case 9:
                $p_x = $this->g_w - $p_w - $this->water_margin;
                $p_y = $this->g_h - $p_h - $this->water_margin;
                break;
            default:
                $p_x = rand($this->water_margin, ($this->g_w - $p_w - $this->water_margin));
                $p_y = rand($this->water_margin, ($this->g_h - $p_h - $this->water_margin));
                break;
        }
        return array('dest_x' => $p_x, 'dest_y' => $p_y);
    }

}

?>
