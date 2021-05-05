<?php

namespace Glare\Support\ViewParsers;

class ImageBlocksViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input, $images)
    {
        $output = preg_replace_callback_array(
            [
                // // <p>[IMAGES..{n}]</p>
                // '~<p>\[ima?ge?s\.\.(\d+)\]<\/p>~i' => function ($match) use ($images) {
                //     $number = $match[1];
                //     $out = '<p class="images is-'.$number.'">';
                //     return $out;
                // },

                // // <p>[/IMAGES]</p>
                // '~<p>\[/ima?ge?s\]<\/p>~i' => function ($match) {
                //     $out = '</p>';
                //     return $out;
                // },

                '~<([a-zA-Z0-9_.-]+)>\[images(\.(\d+))?\]<\/([a-zA-Z0-9_.-]+)>~i' => function ($match) use ($images) {

                    $tagOpen = $match[1];
                    $perLine = $match[3];
                    $tagClose = $match[4];

                    $count = !empty($images) ? count($images) : 0;

                    if (empty($count)) {
                        return '';
                    }

                    if (empty($perLine)) {
                        $perLine = 4;
                    }

                    $out = '';
                    $out .= '<'.$tagOpen.' class="images flex-wrap has-'.$perLine.'">';

                    foreach ($images as $img) {

                        // no image continue
                        if (is_null($img)) {
                            continue;
                        }

                        // size
                        $size = '';

                        // resolve alt
                        $alt = !empty($alt) ? $alt : $img->alt;

                        // resolve title
                        $title = !empty($img->title) ? $img->title : '';

                        // class
                        $class = '';
                        // $class .= !empty($float) ? ' ' . $float : '';
                        // $class .= !empty($width) ? ' image-w-' . $width : '';

                        // img attributes
                        $attr = [];
                        $attr = array_replace($attr, ['alt' => (string) $alt]);
                        $attr = array_replace($attr, !empty($title) ? ['title' => $img->title] : []);
                        $attr = array_replace($attr, ['class' => $class]);

                        $out .= $img($size)->attributes($attr)->lazy();
                    }
                    $out .= '</'.$tagClose.'>';

                    return $out;
                },

                // [IMAGES..{n}]
                '~<([a-zA-Z0-9_.-]+)>\[ima?ge?s\.\.(\d+)\]~i' => function ($match) use ($images) {
                    $tag = $match[1];
                    $number = $match[2];
                    $out = '<'.$tag.' class="images has-'.$number.'">';
                    return $out;
                },

                // [/IMAGES]
                '~\[/ima?ge?s\]<\/([a-zA-Z0-9_.-]+)>~i' => function ($match) {
                    $tag = $match[1];
                    $out = '</'.$tag.'>';
                    return $out;
                },
            ],
            $input
        );

        return $output;
    }

}
