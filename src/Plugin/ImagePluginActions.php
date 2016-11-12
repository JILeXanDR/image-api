<?php

namespace app\Plugin;

use app\Image;

interface ImagePluginActions
{
    public function resize(Image $image, array $options);

    public function crop(Image $image, array $options);

    public function trumb(Image $image, array $options);
}