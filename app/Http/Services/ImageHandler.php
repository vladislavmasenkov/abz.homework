<?php
/**
 * Created by PhpStorm.
 * User: vladyslavmasenkov
 * Date: 7/11/18
 * Time: 9:36 AM
 */

namespace App\Http\Services;

/**
 * Service for image handling (resizing, set watermark, save in format)
 *
 * Class ImageHandler
 * @package App\Http\Services
 */
class ImageHandler
{
    /** @var resource $image */
    private $image;

    /**
     * Image info vars
     *
     * @var int $width
     * @var int $height
     * @var int $type
     */
    private $width;
    private $height;
    private $type;

    /** @var array AVAILABLE_TYPES */
    const AVAILABLE_TYPES = [
        'jpeg' => IMAGETYPE_JPEG,
        'png' => IMAGETYPE_PNG,
        'bmp' => IMAGETYPE_BMP,
        'gif' => IMAGETYPE_GIF
    ];

    /**
     * ImageHandler constructor.
     * @param $path
     * @throws \Exception
     */
    public function __construct($path)
    {
        $this->type = exif_imagetype($path);
        if ($this->type) {
            list($this->width, $this->height) = getimagesize($path);
        }
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $this->image = imagecreatefrompng($path);
                break;
            case IMAGETYPE_BMP:
                $this->image = imagecreatefrombmp($path);
                break;
            case IMAGETYPE_GIF:
                $this->image = imagecreatefrombmp($path);
                break;
            case false:
                throw new \Exception('Unsupported type');
                break;
            default:
                $this->image = imagecreate(500, 500);
        }
    }

    public function getImage()
    {
        return $this->image;
    }

    public function resize($width, $height)
    {
        $template = imagecreatetruecolor(500, 500);
        imagecopyresized($template, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

        $this->image = $template;
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function setTextWatermark($text, $x, $y)
    {
        $black = imagecolorallocate($this->image, 0, 0, 0);
        $red = imagecolorallocate($this->image, 255, 0, 0);
        imagettftext($this->image, 15, 0, $x + 1, $y + 1, $black, public_path('fonts/arial.ttf'), $text);
        imagettftext($this->image, 15, 0, $x, $y, $red, public_path('fonts/arial.ttf'), $text);
        return $this;
    }

    public function save($type = 2)
    {
        if (isset(self::AVAILABLE_TYPES[$type])) {
            $type = self::AVAILABLE_TYPES[$type];
        }
        $imageName = str_random(32) . '_handled';
        $imagePath = public_path('uploads/images/');

        switch ($type) {
            case IMAGETYPE_JPEG:
                $imageName .= '.jpg';
                imagejpeg($this->image, $imagePath . $imageName);
                break;
            case IMAGETYPE_PNG:
                $imageName .= '.png';
                imagepng($this->image, $imagePath . $imageName);
                break;
            case IMAGETYPE_BMP:
                $imageName .= '.bmp';
                imagebmp($this->image, $imagePath . $imageName);
                break;
            case IMAGETYPE_GIF:
                $imageName .= '.gif';
                imagegif($this->image, $imagePath . $imageName);
                break;
            default:
                break;
        }

        if (file_exists($imagePath . $imageName)) {
            return [
                'path' => $imagePath . $imageName,
                'name' => $imageName,
                'url' => url('uploads/images/' . $imageName)
            ];
        }
        return false;
    }
}