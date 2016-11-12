<?php

namespace app;

use app\Plugin\SimpleImage;

class Image
{
    public $image;

    public function __construct(string $url)
    {
        $this->image = new SimpleImage();
        $this->image->load($url);
    }

    public function output($image_type = IMAGETYPE_JPEG)
    {
        $this->image->output($image_type);
    }
}