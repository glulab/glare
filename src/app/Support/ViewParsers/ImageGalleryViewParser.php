<?php

namespace Glare\Support\ViewParsers;

class ImageGalleryViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input, $images)
    {
        $output = preg_replace_callback_array(
            [
                '~\[imagegallery\]~i' => function ($match) use ($images) {
                    $class = 'js-gallery';
                    return $this->renderImageGallery($images, $class);
                },

                '~\[imageminiatures\]~i' => function ($match) use ($images) {
                    $class = '';
                    return $this->renderImageGallery($images, $class);
                },
            ],
            $input
        );

        return $output;
    }

    public function renderImageGallery($images, $class = '')
    {
        return \App::make(\Glare\View\Components\ImageGallery::class)->render($images, $class);
    }

}
