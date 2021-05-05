<?php

namespace Glare\Support\ViewParsers;

class ComponentPhotoLinksViewParser
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~<p>\[buttons\]<\/p>~i' => function ($match) {
                    $out = $this->render();
                    return $out;
                },

            ],
            $input
        );

        return $output;
    }

    public function render()
    {
        if (class_exists(\App\View\Components\Site\PhotoLinks::class)) {
            return \App::make(\App\View\Components\Site\PhotoLinks::class, ['items' => $this->model->photo_links])->render();
        }
        return '';
    }

}
