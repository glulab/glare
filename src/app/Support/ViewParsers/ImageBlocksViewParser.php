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
