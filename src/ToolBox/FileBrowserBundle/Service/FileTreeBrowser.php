<?php

namespace ToolBox\FileBrowserBundle\Service;

use Symfony\Component\DependencyInjection\Container;

class FileTreeBrowser
{

    private $container;
    private $rootDir;
    private $maxFileSize;
    private $allowedMimeTypes;

    private $fileToIcon = array(
        'image/gif'                     => array('type' => 'image', 'icon' => 'gif.png', 'displayName' => 'GIF'),
        'image/png'                     => array('type' => 'image', 'icon' => 'png.png', 'displayName' => 'PNG'),
        'image/jpeg'                    => array('type' => 'image', 'icon' => 'jpg.png', 'displayName' => 'JPG'),
        'image/pjpeg'                   => array('type' => 'image', 'icon' => 'jpg.png', 'displayName' => 'JPEG'),
        'image/svg+xml'                 => array('type' => 'image', 'icon' => 'svg.png', 'displayName' => 'SVG'),
        'image/tiff'                    => array('type' => 'other', 'icon' => 'tiff.png', 'displayName' => 'TIFF'),
        'image/x-tiff'                  => array('type' => 'other', 'icon' => 'tiff.png', 'displayName' => 'TIFF'),
        'image/tiff'                    => array('type' => 'other', 'icon' => 'tiff.png', 'displayName' => 'TIFF'),
        'image/x-tiff'                  => array('type' => 'other', 'icon' => 'tiff.png', 'displayName' => 'TIFF'),
        'application/octet-stream'      => array('type' => 'other', 'icon' => 'psd.png', 'displayName' => 'PSD'),
        'application/pdf'               => array('type' => 'other', 'icon' => 'pdf.png', 'displayName' => 'PDF'),
        'application/postscript'        => array('type' => 'other', 'icon' => 'ai.png', 'displayName' => 'AI'),
        'application/x-troff-msvideo'   => array('type' => 'other', 'icon' => 'avi.png', 'displayName' => 'AVI'),
        'video/avi'                     => array('type' => 'other', 'icon' => 'avi.png', 'displayName' => 'AVI'),
        'video/msvideo'                 => array('type' => 'other', 'icon' => 'avi.png', 'displayName' => 'AVI'),
        'video/x-msvideo'               => array('type' => 'other', 'icon' => 'avi.png', 'displayName' => 'AVI'),
        'application/msword'            => array('type' => 'other', 'icon' => 'doc.png', 'displayName' => 'DOC'),
        'text/html'                     => array('type' => 'other', 'icon' => 'html.png', 'displayName' => 'HTML'),
        'application/x-javascript'      => array('type' => 'other', 'icon' => 'js.png', 'displayName' => 'JS'),
        'application/javascript'        => array('type' => 'other', 'icon' => 'js.png', 'displayName' => 'JS'),
        'application/ecmascript'        => array('type' => 'other', 'icon' => 'js.png', 'displayName' => 'JS'),
        'text/javascript'               => array('type' => 'other', 'icon' => 'js.png', 'displayName' => 'JS'),
        'text/ecmascript'               => array('type' => 'other', 'icon' => 'js.png', 'displayName' => 'JS'),
        'video/quicktime'               => array('type' => 'other', 'icon' => 'mov.png', 'displayName' => 'MOV'),
        'audio/mpeg3'                   => array('type' => 'other', 'icon' => 'mp3.png', 'displayName' => 'MP3'),
        'audio/x-mpeg-3'                => array('type' => 'other', 'icon' => 'mp3.png', 'displayName' => 'MP3'),
        'video/mpeg'                    => array('type' => 'other', 'icon' => 'mp3.png', 'displayName' => 'MP3'),
        'video/x-mpeg'                  => array('type' => 'other', 'icon' => 'mp3.png', 'displayName' => 'MP3'),
        'text/plain'                    => array('type' => 'other', 'icon' => 'txt.png', 'displayName' => 'TXT'),
        'text/plain'                    => array('type' => 'other', 'icon' => 'txt.png', 'displayName' => 'TXT'),
        'application/excel'             => array('type' => 'other', 'icon' => 'xls.png', 'displayName' => 'XLS'),
        'application/vnd.ms-excel'      => array('type' => 'other', 'icon' => 'xls.png', 'displayName' => 'XLS'),
        'application/x-excel'           => array('type' => 'other', 'icon' => 'xls.png', 'displayName' => 'XLS'),
        'application/x-msexcel'         => array('type' => 'other', 'icon' => 'xls.png', 'displayName' => 'XLS'),
    );


    public function __construct(Container $container)
    {

        $this->container = $container;
        $this->rootDir = $this->container->get('kernel')->getRootDir() . '/../web';

    }

    public function setConfig($config){

        $this->maxFileSize = $config['max_file_size'];
        $this->allowedMimeTypes = $config['file_types'];

    }

    public function getFileTree($path = '/img')
    {

        $path = urldecode($path);

        $finalFileTree = "";

        if (file_exists($this->rootDir . $path)) {

            $files = scandir($this->rootDir . $path);

            natcasesort($files);

            if (count($files) > 2) { /* The 2 accounts for . and .. */
                $finalFileTree .= "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($this->rootDir . $path . '/' . $file) && $file != '.' && $file != '..' && is_dir($this->rootDir . $path . '/' . $file)) {

                        $finalFileTree .= "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . $path . '/' . $file . "/\">" . $file . "</a></li>";

                    }
                }

                $finalFileTree .= "</ul>";

            }

        }

        return $finalFileTree;

    }

    public function getDirContent($path = '/img')
    {
        $returnJson = "";

        if (isset($path)) {

            $delimiter = '';
            $i = 0;
            $dir_content_array = scandir($this->rootDir . $path);


            $returnJson .= '{"success": "true", "dir_content": {';
            foreach ($dir_content_array as $dir) {

                if (!is_dir($this->rootDir . $path . '/' . $dir) && $dir !== '.' && $dir !== '..') {

                    $filetype = mime_content_type($this->rootDir . '/' . $path . '/' . $dir);
                    $icon = 'unknown.png';
                    $type = $this->fileToIcon[$filetype]['type'];
                    if (isset($this->fileToIcon[$filetype])) {
                        $icon = $this->fileToIcon[$filetype]['icon'];
                    }

                    $returnJson .= $delimiter . '"' . $i . '": {';
                    $returnJson .= '"icon": "' . $icon . '",';
                    $returnJson .= '"type": "' . $type . '",';
                    $returnJson .= '"path": "' . $path . '/' . $dir . '",';
                    $returnJson .= '"name": "' . $dir . '"';
                    $returnJson .= '}';

                    $delimiter = ', ';
                    $i++;

                }

            }
            $returnJson .= '}}';

        } else {

            $returnJson .= '{"success": "false", "message": "Directory is not specified!"}';

        }

        return $returnJson;

    }

    public function uploadFile($image, $dir){

        $dir = $this->rootDir.$dir;

        if(isset($image)){

            $allowed_extentions = $this->allowedMimeTypes;
            $max_file_size = $this->maxFileSize;

            $resize = false;
            $resized = false;

            $thumbnail = false;
            $thumbnail_created = false;

            $margin_x = 0; // Horizontal
            $margin_y = 0; // Vertical

            $check = getimagesize($image->getRealPath());

            $image_extention = $image->getMimeType();

            $new_path = $dir.'/'.$image->getClientOriginalName();
            $new_thumb_path = $dir.'/thumb';
            $new_thumb_image_path = $dir.'/thumb'.'/'.$image->getClientOriginalName();



            if ($image->getClientSize() < $max_file_size) {

                if(in_array($image_extention, $allowed_extentions)) {

                    list($ini_w, $ini_h) = getimagesize($image->getRealPath());
                    $new_w = 1920;
                    $new_h = 1080;

                    $thumb_ini_w = $ini_w;
                    $thumb_ini_h = $ini_h;

                    if($ini_w > $new_w) { $new_h = $ini_h * $new_w / $ini_w; $thumb_ini_h = $new_h; $resize = true; }
                    if($ini_h > $new_h) { $new_w = $ini_w * $new_h / $ini_h; $thumb_ini_w = $new_w; $resize = true; }

                    $thumb_ini_ratio = $thumb_ini_w / $thumb_ini_h;

                    $thumb_crop_w = (isset($_POST['thumb_width'])) ? intval($_POST['thumb_width']) : '';
                    $thumb_crop_h = (isset($_POST['thumb_height'])) ? intval($_POST['thumb_height']) : '';
                    $thumb_crop_ratio = $thumb_crop_w / $thumb_crop_h;

                    $thumb_res_w = 0;
                    $thumb_res_h = 0;

                    if((!empty($thumb_crop_w) && $thumb_crop_w > 0) && (!empty($thumb_crop_h) && $thumb_crop_h > 0)){

                        $thumbnail = true;

                        if($thumb_crop_ratio > $thumb_ini_ratio) {

                            $thumb_res_w = $thumb_crop_w;
                            $thumb_res_h = $thumb_ini_h * $thumb_res_w / $thumb_ini_w;

                            $margin_y = ($thumb_res_h - $thumb_crop_h) / 2;

                        }

                        if($thumb_crop_ratio < $thumb_ini_ratio) {

                            $thumb_res_h = $thumb_crop_h;
                            $thumb_res_w = $thumb_ini_w * $thumb_res_h / $thumb_ini_h;

                            $margin_x = ($thumb_res_w - $thumb_crop_w) / 2;

                        }

                        if($thumb_crop_ratio == $thumb_ini_ratio){

                            $thumb_res_w = $thumb_crop_w;
                            $thumb_res_h = $thumb_crop_h;

                            $margin_x = 0;
                            $margin_y = 0;

                        }

                    }

                    if(!file_exists($new_path)){

                        if(preg_match('/thumb(\/|)$/', $dir)){
                            $thumbnail = false;
                        }

                        if (move_uploaded_file($image->getRealPath(), $new_path)) {

                            if($resize) {

                                if($this->resizeImage($new_path, $new_path, $new_w, $new_h, $ini_w, $ini_h)){

                                    $resized = true;

                                }

                            }

                            if($thumbnail){

                                if($this->createThumbnail(
                                    $new_path,
                                    $new_thumb_image_path,
                                    $thumb_res_w,
                                    $thumb_res_h,
                                    $thumb_ini_w,
                                    $thumb_ini_h,
                                    $thumb_crop_w,
                                    $thumb_crop_h,
                                    $margin_x,
                                    $margin_y)){

                                    $thumbnail_created = true;

                                }

                            }

                            if($resized || $thumbnail_created){

                                $message_resize = "";
                                $message_thumbnail = "";

                                if($resized) { $message_resize = "Image is resized."; }
                                if($thumbnail_created) { $message_thumbnail = "Thumbnail is created."; }

                                $response = array("success" => true, "message" => "Image is successfully uploaded! ".$message_resize.$message_thumbnail);
                                return $response;

                            } else {

                                $response = array("success" => true, "message" => "Image is successfully uploaded!");
                                return $response;

                            }

                        } else {

                            $response = array("success" => false, "message" => "Image is not uploaded! ".$new_path);
                            return $response;

                        }

                    } else {

                        $response = array("success" => false, "message" => "File with this name already exists!");
                        return $response;

                    }

                } else {

                    $allowedText = "";
                    $delimiter = "";

                    foreach($this->allowedMimeTypes as $type){
                        $allowedText .= $delimiter.$this->fileToIcon[$type]['displayName'];
                        $delimiter = ", ";
                    }

                    $response = array("success" => false, "message" => "Only ".$allowedText." files allowed");
                    return $response;

                }

            } else {

                $response = array("success" => false, "message" => "Too large");
                return $response;

            }

        } else {

            $response = array("success" => false, "message" => "File Not found");
            return $response;

        }

    }

    public function createThumbnail($image_path, $new_path, $new_w, $new_h, $ini_w, $ini_h, $crop_w, $crop_h, $x, $y) {

        $thumb_path = dirname($new_path);
        if(!file_exists($thumb_path)){ mkdir($thumb_path); }

        if($this->resizeImage($image_path, $new_path, $new_w, $new_h, $ini_w, $ini_h)) {

            if($this->resizeImage($new_path, $new_path, $crop_w, $crop_h, $crop_w, $crop_h, $x, $y)) {

                return true;

            } else {

                return false;

            }

        } else {

            return false;

        }

    }

    public function resizeImage($image_path, $new_path, $new_w, $new_h, $ini_w, $ini_h, $x = null, $y = null){

        $image_path = $image_path;
        $new_path = $new_path;
        $image_file_type = pathinfo($image_path, PATHINFO_EXTENSION);
        $image_done = false;

        switch($image_file_type){
            case 'jpeg':
                $img_r = @imagecreatefromjpeg($image_path);
                break;
            case 'jpg':
                $img_r = @imagecreatefromjpeg($image_path);
                break;
            case 'JPG':
                $img_r = @imagecreatefromjpeg($image_path);
                break;
            case 'gif':
                $img_r = @imagecreatefromstring(file_get_contents($image_path));
                break;
            case 'png':
                $img_r = @imagecreatefromstring(file_get_contents($image_path));
                break;
            default:
                $img_r = false;
                break;
        }

        $dst_r = ImageCreateTrueColor($new_w, $new_h);

        if($image_file_type == 'png' || $image_file_type == 'PNG'){
            imagealphablending($dst_r, false);
            imagesavealpha($dst_r, true);
            $transparent = imagecolorallocatealpha($dst_r, 255, 255, 255, 127);
            imagefilledrectangle($dst_r, 0, 0, $new_w, $new_h, $transparent);
        }

        imagecopyresampled($dst_r ,$img_r ,0 ,0 ,$x ,$y ,$new_w ,$new_h ,$ini_w ,$ini_h);

        switch($image_file_type){
            case 'jpeg':
                $image_done = imagejpeg($dst_r, $new_path);
                break;
            case 'jpg':
                $image_done = imagejpeg($dst_r, $new_path);
                break;
            case 'JPG':
                $image_done = imagejpeg($dst_r, $new_path);
                break;
            case 'gif':
                $image_done = imagegif($dst_r, $new_path);
                break;
            case 'png':
                $image_done = imagepng($dst_r, $new_path);
                break;
            case 'PNG':
                $image_done = imagepng($dst_r, $new_path);
                break;
            default:
                $img_r = false;
                break;
        }

        return $image_done;

    }

    public function createDir($path){

        if(!file_exists($this->rootDir.$path)){

            if(mkdir($this->rootDir.$path)){

                $response = array(
                    'success' => true,
                    'message' => 'Directory successfully created!'
                );

            } else {

                $response = array(
                    'success' => false,
                    'message' => 'Directory is not created, please try again!'
                );

            }

        } else {

            $response = array(
                'success' => false,
                'message' => 'Directory with this name already exists!'
            );

        }

        return $response;

    }

    public function deleteFile($file){

        $response = array();
        $file_path = $this->rootDir.$file;

        if(file_exists($file_path)){

            $file_dir = dirname($file_path);
            $file_name = basename($file_path);
            $thumb_dir = $file_dir.'/thumb/';

            if(file_exists($thumb_dir.$file_name)){ unlink($thumb_dir.$file_name); }

            if(unlink($file_path)) {

                $response = array(
                    'success' => true,
                    'message' => 'File(s) is/are successfully deleted!'
                );

            } else {

                $response = array(
                    'success' => false,
                    'message' => 'File is not deleted, please try again!'
                );

            }

        } else {

            $response = array(
                'success' => false,
                'message' => 'File with this name doesn\'t exist!'
            );

        }

        return $response;

    }

    public function deleteTree($dir) {

        $files = array_diff(scandir($dir), array('.','..'));
        $new_dir = preg_replace('/\/$/', '', $dir);

        foreach ($files as $file) {

            if(is_dir($new_dir.'/'.$file)){

                $this->deleteTree($new_dir.'/'.$file);

            } else {

                unlink($new_dir.'/'.$file);

            }

        }

        if(rmdir($new_dir)) {

            return true;

        } else {

            return false;

        }

    }

}