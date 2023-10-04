<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThumbImageController extends Controller
{
    public function createThumb($width_thumb = 0, $height_thumb = 0, $zoom_crop = '1', $src = '', $image_u, $watermark = null, $path = "thumbs", $preview = false, $args = array(), $quality = 100)
    {
        $src = "upload/" . $src . "/" . $image_u;
        $t = 3600 * 24 * 3;
        // $this->RemoveFilesFromDirInXSeconds("upload", 1);
        $this->RemoveFilesFromDirInXSeconds($path . "/", $t);
        $this->RemoveEmptySubFolders($path . "/");

        $src = str_replace("%20", " ", $src);
        if (!file_exists($src)) die("NO IMAGE $src");

        $image_url = $src;
        $origin_x = 0;
        $origin_y = 0;
        $new_width = $width_thumb;
        $new_height = $height_thumb;

        if ($new_width < 10 && $new_height < 10) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            die("Width and height larger than 10px");
        }
        if ($new_width > 2000 || $new_height > 2000) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            die("Width and height less than 2000px");
        }

        $array = getimagesize($image_url);
        if ($array) list($image_w, $image_h) = $array;
        else die("NO IMAGE $image_url");

        $width = $image_w;
        $height = $image_h;

        if ($new_height && !$new_width) $new_width = $width * ($new_height / $height);
        else if ($new_width && !$new_height) $new_height = $height * ($new_width / $width);

        $image_ext = explode('.', $image_url);
        $image_ext = trim(strtolower(end($image_ext)));
        $image_name = explode('/', $image_url);
        $image_name = trim(strtolower(end($image_name)));
        switch (strtoupper($image_ext)) {
            case 'JPG':
            case 'JPEG':
                $image = imagecreatefromjpeg($image_url);
                $func = 'imagejpeg';
                $mime_type = 'jpeg';
                break;

            case 'PNG':
                $image = imagecreatefrompng($image_url);
                $func = 'imagepng';
                $mime_type = 'png';
                break;

            case 'GIF':
                $image = imagecreatefromgif($image_url);
                $func = 'imagegif';
                $mime_type = 'png';
                break;

            default:
                die("UNKNOWN IMAGE TYPE: $image_url");
        }

        if ($zoom_crop == 3) {
            $final_height = $height * ($new_width / $width);
            if ($final_height > $new_height) $new_width = $width * ($new_height / $height);
            else $new_height = $final_height;
        }

        $canvas = imagecreatetruecolor($new_width, $new_height);
        imagealphablending($canvas, false);
        $color = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefill($canvas, 0, 0, $color);

        if ($zoom_crop == 2) {
            $final_height = $height * ($new_width / $width);
            if ($final_height > $new_height) {
                $origin_x = $new_width / 2;
                $new_width = $width * ($new_height / $height);
                $origin_x = round($origin_x - ($new_width / 2));
            } else {
                $origin_y = $new_height / 2;
                $new_height = $final_height;
                $origin_y = round($origin_y - ($new_height / 2));
            }
        }

        imagesavealpha($canvas, true);

        if ($zoom_crop > 0) {
            $align = '';
            $src_x = $src_y = 0;
            $src_w = $width;
            $src_h = $height;

            $cmp_x = $width / $new_width;
            $cmp_y = $height / $new_height;

            if ($cmp_x > $cmp_y) {
                $src_w = round($width / $cmp_x * $cmp_y);
                $src_x = round(($width - ($width / $cmp_x * $cmp_y)) / 2);
            } else if ($cmp_y > $cmp_x) {
                $src_h = round($height / $cmp_y * $cmp_x);
                $src_y = round(($height - ($height / $cmp_y * $cmp_x)) / 2);
            }

            if ($align) {
                if (strpos($align, 't') !== false) {
                    $src_y = 0;
                }
                if (strpos($align, 'b') !== false) {
                    $src_y = $height - $src_h;
                }
                if (strpos($align, 'l') !== false) {
                    $src_x = 0;
                }
                if (strpos($align, 'r') !== false) {
                    $src_x = $width - $src_w;
                }
            }

            imagecopyresampled($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);
        } else {
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        }


        $upload_dir = '';
        $folder_old = str_replace($image_name, '', $image_url);


        $upload_dir = $path . '/' . $width_thumb . 'x' . $height_thumb . 'x' . $zoom_crop . '/' . $folder_old;


        if (!file_exists($upload_dir)) if (!mkdir($upload_dir, 0777, true)) die('Failed to create folders...');


        if ($upload_dir) {
            if ($func == 'imagejpeg') $func($canvas, $upload_dir . $image_name, 100);
            else $func($canvas, $upload_dir . $image_name, floor($quality * 0.09));
        }

        // header('Content-Type: image/' . $mime_type);
        // if ($func == 'imagejpeg') $func($canvas, NULL, 100);
        // else $func($canvas, NULL, floor($quality * 0.09));

        imagedestroy($canvas);
    }
    /* Remove files from dir in x seconds */
    public function RemoveFilesFromDirInXSeconds($dir = '', $seconds = 3600)
    {
        $files = glob(rtrim($dir, '/') . "/*");
        $now = time();

        if ($files) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    if ($now - filemtime($file) >= $seconds) {
                        unlink($file);
                    }
                } else {
                    $this->RemoveFilesFromDirInXSeconds($file, $seconds);
                }
            }
        }
    }
    /* Remove Sub folder */
    public function RemoveEmptySubFolders($path = '')
    {
        $empty = true;

        foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
            if (is_dir($file)) {
                if (!$this->RemoveEmptySubFolders($file)) $empty = false;
            } else {
                $empty = false;
            }
        }

        if ($empty) {
            if (is_dir($path)) {
                rmdir($path);
            }
        }

        return $empty;
    }
}
