<?php  if (!defined('ROOT')) exit('No direct script access allowed');

/**
 * Kaili Image Class
 *
 * Provides various functions to edit images.
 *
 * @package		Kaili
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Luigi Marco Simonetti
 */
 
 class Image
 {
    private $_config;
    
    function __construct()
    {
        $this->_config = Loader::get_instance()->load('config');
    }   
    /**
     * Crop an image
     * @param string source image path
     * @param int x coordinate of crop source point
     * @param int y coordinate of crop source point
     * @param int width of cropped area in pixels
     * @param int height of cropped area in pixels
     * @param string path of destination image. If it is null, changes will saved on source image
     */ 
    function crop($src, $x_point, $y_point, $w_crop, $h_crop, $dest = null)
    {
        list($img_width, $img_height, $img_type) = getimagesize($src);
        
        // load source image
        switch($img_type){
            case 1: $img = imagecreatefromgif($src); break;
            case 2: $img = imagecreatefromjpeg($src); break;
            case 3: $img = imagecreatefrompng($src);
        }
        
        // create new image and resize it
        $res = imagecreatetruecolor($w_crop, $h_crop);
        imagecopy($res, $img, 0, 0, $x_point, $y_point, $img_width, $img_height);
        
        // if destination is null, save changes in the source image
        if($dest == null) $dest = $src;
        
        // save changes
        switch($img_type){
            case 1: imagegif($res, $dest, 90); break;
            case 2: imagejpeg($res, $dest, 90); break;
            case 3: imagepng($res, $dest, 90);
        }
        
        imagedestroy($img);
        imagedestroy($res); 
    }
    
    /**
     * Resize an image
     * @param string path of source image
     * @param int width of resized area, in pixels
     * @param int height of resized area, in pixels
     * @param string path of destination image. If it is null, changes will saved on source image
     */
    function resize($src, $res_width, $res_height, $dest = null)
    {
        list($img_width, $img_height, $img_type) = getimagesize($src);
        
        // load source image
        switch($img_type){
            case 1: $img = imagecreatefromgif($src); break;
            case 2: $img = imagecreatefromjpeg($src); break;
            case 3: $img = imagecreatefrompng($src);
        }
        
        // calculate height or width
        if($res_width == null && $res_height == null){
            throw new Exception('One parameter betwen width and height is needed.');
        }
        else if($res_height == null){
            $res_height = ($res_width * $img_height) / $img_width;
        }
        else if($res_width == null){
            $res_width = ($res_height * $img_width) / $img_height;
        }
        
        // create new image and resize it
        $res = imagecreatetruecolor($res_width, $res_height);
        imagecopyresampled($res, $img, 0, 0, 0, 0, $res_width, $res_height, $img_width, $img_height);
        
        // if destination is null, save changes in the source image
        if($dest == null) $dest = $src;
        
        // save changes
        switch($img_type){
            case 1: imagegif($res, $dest, 90); break;
            case 2: imagejpeg($res, $dest, 90); break;
            case 3: imagepng($res, $dest, 90);
        }
        
        imagedestroy($img);
        imagedestroy($res); 
    }
    
    /**
     * Rotate an image
     */
    function rotate($src, $angle, $dest = null)
    {
        list($img_width, $img_height, $img_type) = getimagesize($src);
        
        // load source image
        switch($img_type){
            case 1: $img = imagecreatefromgif($src); break;
            case 2: $img = imagecreatefromjpeg($src); break;
            case 3: $img = imagecreatefrompng($src);
        }
        
        // rotate image of input angle
        $rot = imagerotate($img, $angle, 0);
        
        // if destination is null, save changes in the source image
        if($dest == null) $dest = $src;
        
        // save source image with the watermark
        switch($img_type){
            case 1: imagegif($rot, $dest, 90); break;
            case 2: imagejpeg($rot, $dest, 90); break;
            case 3: imagepng($rot, $dest, 90);
        }
        
        imagedestroy($img);
        imagedestroy($rot);
    }
    
    /**
     * Create a thumbnail for an image
     */
    function thumbnail($src, $thu_width, $thu_height, $dest = null)
    {
        // if destination is null, save thumbnail in thumbnails directory
        if($dest == null){
            $img_info = pathinfo($src);
            $dest = $this->_config->item('thumbs_directory').DS.$img_info['basename'];
        }
        $this->resize($src, $thu_width, $thu_height, $dest);
    }
    
    /**
     * Add a wotermark to an image
     */
    function watermark($src, $w_src, $x_margin, $y_margin, $dest = null)
    {
        list($img_width, $img_height, $img_type) = getimagesize($src);
        list($wat_width, $wat_height, $wat_type) = getimagesize($w_src);
        
        // load source image
        switch($img_type){
            case 1: $img = imagecreatefromgif($src); break;
            case 2: $img = imagecreatefromjpeg($src); break;
            case 3: $img = imagecreatefrompng($src);
        }
        // load watermark
        switch($wat_type){
            case 1: $wat = imagecreatefromgif($w_src); break;
            case 2: $wat = imagecreatefromjpeg($w_src); break;
            case 3: $wat = imagecreatefrompng($w_src);
        }
        
        // copy watermark on the source image
        imagecopy($img, $wat, $x_margin, $y_margin, 0, 0, $wat_width, $wat_height);
        
        // if destination is null, save changes in the source image
        if($dest == null) $dest = $src;
        
        // save source image with the watermark
        switch($img_type){
            case 1: imagegif($img, $dest, 90); break;
            case 2: imagejpeg($img, $dest, 90); break;
            case 3: imagepng($img, $dest, 90);
        }
        
        imagedestroy($img);
        imagedestroy($wat); 
    }
 }
 
/* End of file Image.php */
/* Location: ./system/library/Image.php */
