<?php

namespace Glare\View\Components;

class Gallery
{
    public function __construct()
    {

    }

    public function render($images, $class = '', $size = '', $miniature = 'miniature', $thumb = 'thumb')
    {
        $o = [];
        if (count($images) > 0) :
            $o[] = '<ul class="gallery '.$class.' is-'.($size ? $size : 'original').'" id="'.uniqid('gallery-').'" data-count="'.count($images).'">';
            foreach ($images as $img) :

                // check if there is thumb
                if (!is_file($img->getPath($thumb)) || $img->hasGeneratedConversion($thumb) === false) {
                    $o[] = '<li class="gallery-item" data-src="'.$img->getUrl($size).'">';
                } else {
                    $o[] = '<li class="gallery-item" data-src="'.$img->getUrl($size).'" data-thumb="'.$img->getUrl($thumb).'">';
                }

                $o[] = '<div class="gallery-item-image">';

                // attributes array
                $attributes = [];
                $attributes['class']= 'gallery-item-img rounded mb-2';
                $attributes['alt'] = $img->title ?: $img->alt;
                if (!empty($img->title)) {
                    $attributes['title'] = $img->title;
                }

                // attributes string
                $attributesString = '';
                foreach ($attributes as $key => $value) {
                    if (!empty($attributesString)) {
                        $attributesString .= ' ';
                    }
                    $attributesString .= $key.'="'.$value.'"';
                }

                // check if there is miniature
                if (is_file($img->getPath($miniature)) && $img->hasGeneratedConversion($miniature) === true) {
                    $o[] = '<img src="'. $img->getUrl($miniature) .'" '.$attributesString.'>';
                } else {
                    $o[] = $img($size)->attributes($attributes)->lazy();
                }

                if (!empty($img->title)) :
                    $o[] = '<div class="gallery-item-title">' . $img->title . '</div>';
                endif;
                $o[] = '</div>';
                $o[] = '</li>';
            endforeach;
            $o[] = '</ul>';
        endif;
        return implode('', $o);
    }
}
