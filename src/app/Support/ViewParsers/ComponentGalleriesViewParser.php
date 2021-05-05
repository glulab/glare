<?php

namespace Glare\Support\ViewParsers;

class ComponentGalleriesViewParser
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
                '~<p>\[gallery\]<\/p>~i' => function ($match) {
                    $out = $this->renderGalleries('js-gallery');
                    return $out;
                },

                '~<p>\[galleries\]<\/p>~i' => function ($match) {
                    $out = $this->renderGalleries('js-gallery');
                    return $out;
                },

                '~<p>\[gallery\.(\d+)\]<\/p>~i' => function ($match) {
                    $id = $match[1];
                    $out = $this->renderGallery($id, 'js-gallery');
                    return $out;
                },

                '~<p>\[galleryphotos\.(\d+)\]<\/p>~i' => function ($match) {
                    $id = $match[1];
                    $out = $this->renderGalleryPhotos($id, 'js-gallery');
                    return $out;
                },
            ],
            $input
        );

        return $output;
    }

    public function renderGalleries($class = null, $attrs = null, $size = null, $miniature = 'miniature', $thumb = 'thumb')
    {
        if (class_exists(\App\View\Components\Site\Galleries::class)) {
            return \App::make(
                \App\View\Components\Site\Galleries::class,
                [
                    'feed' => $this->model->galleries ?? $this->model,
                    'class' => $class,
                    'attrs' => $attrs,
                    'size' => $size,
                    'miniature' => $miniature,
                    'thumb' => $thumb,
                ]
            )->render();
        }

        return '';
    }

    public function renderGallery($id, $class = null, $attrs = null, $size = null, $miniature = 'miniature', $thumb = 'thumb')
    {
        if (class_exists(\App\View\Components\Site\Gallery::class)) {
            return \App::make(
                \App\View\Components\Site\Gallery::class,
                [
                    'gallery' => $id,
                    'class' => $class,
                    'attrs' => $attrs,
                    'size' => $size,
                    'miniature' => $miniature,
                    'thumb' => $thumb,
                ]
            )->render();
        }

        return '';
    }

    public function renderGalleryPhotos($id, $class = null, $attrs = null, $size = null, $miniature = 'miniature', $thumb = 'thumb')
    {
        if (class_exists(\App\View\Components\Site\Photos::class)) {
            return \App::make(
                \App\View\Components\Site\Photos::class,
                [
                    'images' => $id,
                    'class' => $class,
                    'attrs' => $attrs,
                    'size' => $size,
                    'miniature' => $miniature,
                    'thumb' => $thumb,
                ]
            )->render();
        }

        return '';
    }
}
