<?php

namespace Glare\Support\ViewParsers;

class GalleryViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input, $images)
    {
        $output = preg_replace_callback_array(
            [
                '~\[gallery\]~i' => function ($match) use ($images) {
                    $class = 'js-gallery';
                    return $this->renderGallery($images, $class);
                },

                '~\[images\]~i' => function ($match) use ($images) {
                    $class = '';
                    return $this->renderGallery($images, $class);
                },
            ],
            $input
        );

        return $output;
    }

    public function renderGallery($images, $class = '')
    {
        return \App::make(\Glare\View\Components\Gallery::class)->render($images, $class);
    }

}
