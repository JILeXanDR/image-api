<?php

namespace app\Plugin;

use app\Image;

class ImagePlugin implements ImagePluginActions
{
    /**
     * @param Image $image
     * @param array $options
     * @param int $width New width. Parameter is required
     * @param int $weight New height. Parameter is required
     * @return Image
     * @throws \InvalidArgumentException If size values are incorrect
     */
    public function resize(Image $image, array $options)
    {
        if ((!isset($options['width']) && !is_null($options['width'])) || (!isset($options['height']) && !is_null($options['height']))) {
            throw new \InvalidArgumentException("Options values `width` and `height` are required!");
        }

        if ((!is_null($options['width']) && $options['width'] <= 0) || (!is_null($options['height']) && $options['height'] <= 0)) {
            throw new \InvalidArgumentException("Options `width` and `height` must be of type integer and contains positive numbers!");
        }

        if (empty($options['width'])) {
            $image->image->resizeToHeight($options['height']);
        } elseif (empty($options['height'])) {
            $image->image->resizeToHeight($options['width']);
        } else {
            $image->image->resize($options['width'], $options['height']);
        }

        return $image;
    }

    public function crop(Image $image, array $options)
    {
        // TODO: Implement crop() method.
    }

    public function trumb(Image $image, array $options)
    {
        // TODO: Implement trumb() method.
    }
}