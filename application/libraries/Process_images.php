<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Process_images {
    // Adam Khoury PHP Image Function Library 1.0
    // ----------------------- RESIZE FUNCTION -----------------------
    // Function for resizing any jpg, gif, or png image files
    function ak_img_resize($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
               $w = $h * $scale_ratio;
        } else {
               $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
        $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
        $img = imagecreatefrompng($target);
        } else { 
        $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        if ($ext == "gif"){ 
            imagegif($tci, $newcopy);
        } else if($ext =="png"){ 
            imagepng($tci, $newcopy);
        } else { 
            imagejpeg($tci, $newcopy, 84);
        }
    }
    // -------------- THUMBNAIL (CROP) FUNCTION ---------------
    // Function for creating a true thumbnail cropping from any jpg, gif, or png image files
    function ak_img_thumb($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $src_x = ($w_orig / 2) - ($w / 2);
        $src_y = ($h_orig / 2) - ($h / 2);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif"){
            $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
            $img = imagecreatefrompng($target);
        } else { 
            $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
        if ($ext == "gif"){ 
            imagegif($tci, $newcopy);
        } else if($ext =="png"){ 
            imagepng($tci, $newcopy);
        } else { 
            imagejpeg($tci, $newcopy, 84);
        }
    }
    function ak_img_thumb2($target, $newcopy, $w, $h, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $src_x = ($w_orig / 2) - ($w / 2);
        $src_y = ($h_orig / 2) - ($h / 2);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif"){
            $img = imagecreatefromgif($target);
        } else if($ext =="png"){
            $img = imagecreatefrompng($target);
        } else { 
            $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, 0, $src_y, $w, $h, $w_orig, $h);
        //imagecopyresampled($tci, $img, 0, 0, $src_x, 0, $w_orig, $h, $w, $h);
        if ($ext == "gif"){ 
            imagegif($tci, $newcopy);
        } else if($ext =="png"){ 
            imagepng($tci, $newcopy);
        } else { 
            imagejpeg($tci, $newcopy, 84);
        }
    }
    // ----------------------- IMAGE CONVERT FUNCTION -----------------------
    // Function for converting GIFs and PNGs to JPG upon upload
    function ak_img_convert_to_jpg($target, $newcopy, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif"){ 
            $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
            $img = imagecreatefrompng($target);
        }
        $tci = imagecreatetruecolor($w_orig, $h_orig);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 84);
    }
    // ----------------------- IMAGE WATERMARK FUNCTION -----------------------
    // Function for applying a PNG watermark file to a file after you convert the upload to JPG
    function ak_img_watermark($target, $wtrmrk_file, $newcopy, $ext, $dst = 2) { 
        $watermark = imagecreatefrompng($wtrmrk_file); 
        imagealphablending($watermark, false); 
        imagesavealpha($watermark, true); 
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif"){ 
            $img = imagecreatefromgif($target);
        } else if($ext =="png"){ 
            $img = imagecreatefromPNG($target);
        } else { 
            $img = imagecreatefromjpeg($target);
        }
        $img_w = imagesx($img); 
        $img_h = imagesy($img); 
        $wtrmrk_w = imagesx($watermark); 
        $wtrmrk_h = imagesy($watermark);

        // Trung tâm
        if($dst == 0) {
            $dst_x = ($img_w / 2) - ($wtrmrk_w / 2);
            $dst_y = ($img_h / 2) - ($wtrmrk_h / 2);
        }
        // Góc phải - dưới
        elseif($dst == 1) {
            $dst_x = ($img_w - 10) - ($wtrmrk_w);
            $dst_y = ($img_h - 10) - ($wtrmrk_h);
        }
        // Góc phải - trên
        elseif($dst == 2) {
            $dst_x = ($img_w - 10) - ($wtrmrk_w);
            $dst_y = 10;
        }
        // Góc trái - trên
        elseif($dst == 3) {
            $dst_x = 10;
            $dst_y = 10;
        }
        // Góc trái - dưới
        elseif($dst == 4) {
            $dst_x = 10;
            $dst_y = ($img_h - 10) - ($wtrmrk_h);
        }
        // Mặc định phải - trên
        else {
            $dst_x = ($img_w - 10) - ($wtrmrk_w);
            $dst_y = 10;
        }
        if ($ext == "gif"){
            header("Content-Type: image/gif");
            // Convert gif to a true color image
            $tmp = imagecreatetruecolor(imagesx($img), imagesy($img));
            $bg = imagecolorallocate($tmp, 255, 255, 255);
            imagefill($tmp, 0, 0, $bg);
            imagecopy($tmp, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            $img = $tmp;

            imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h); 
            imagegif($img, $newcopy, 100);
        } else if($ext == "png"){
            header("Content-Type: image/png");
            imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
            imagesavealpha($img, true);
            imagepng($img, $newcopy, 9);
        } else {
            header("Content-Type: image/jpeg");
            imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h); 
            imagejpeg($img, $newcopy, 100);
        }
        imagedestroy($img); 
        imagedestroy($watermark); 
    }
    function crop_image($target, $newcopy, $w, $h, $ext) {
        list($w_i, $h_i) = getimagesize($target);
        $w_o = $w_i;
        $h_o = $h * $w_o / $w;
        if ($h_i < $h_o) {
            $h_o = $h_i;
            $w_o = $w * $h_o / $h;
        }
        $x_o = $w_i - $w_o;
        $y_o = $h_i - $h_o;

        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif"){
            $img = imagecreatefromgif($target);
        } else if($ext =="png"){
            $img = imagecreatefrompng($target);
        } else { 
            $img = imagecreatefromjpeg($target);
        }
        if ($x_o + $w_o > $w_i) $w_o = $w_i - $x_o; 
        if ($y_o + $h_o > $h_i) $h_o = $h_i - $y_o; 
        $img_o = imagecreatetruecolor($w_o, $h_o); 
        imagecopy($img_o, $img, 0, 0, $x_o/2, $y_o/2, $w_o, $h_o);

        if ($ext == "gif"){ 
            imagegif($img_o, $newcopy);
        } else if($ext =="png"){ 
            imagepng($img_o, $newcopy);
        } else { 
            imagejpeg($img_o, $newcopy, 84);
        }           
    }
}