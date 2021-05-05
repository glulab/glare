<?php

namespace Glare\View\Components;

class ImageGallery
{
    public function __construct()
    {

    }

    public function render($images, $class = '', $size = '', $miniature = 'miniature', $thumb = 'thumb')
    {
        if (class_exists(\App\View\Components\Site\Photos::class)) {
            return \App::make(
                \App\View\Components\Site\Photos::class,
                [
                    'images' => $images,
                    'class' => $class,
                    'size' => $size,
                    'miniature' => $miniature,
                    'thumb' => $thumb,
                ]
            )->render();
        }

        return '';
    }
}
