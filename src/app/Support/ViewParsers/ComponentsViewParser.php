<?php

namespace Glare\Support\ViewParsers;

class ComponentsViewParser
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
                // <p>[block]</p> => <div class="format-block">
                '~<p>\[buttons\]<\/p>~i' => function ($match) {
                    $out = $this->renderPhotoLinks();
                    return $out;
                },

            ],
            $input
        );

        return $output;
    }

    public function renderPhotoLinks()
    {
        return \App::make(\App\View\Components\Site\PhotoLinks::class, ['items' => $this->model->photo_links])->render();
    }

}
