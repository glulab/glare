<?php

namespace Glare\Support\ViewParsers;

class BlocksViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                // <p>[block]</p> => <div class="format-block">
                '~<p>\[block\]<\/p>~i' => function ($match) {
                    $out = '<div class="format-block">';
                    return $out;
                },

                // <p>[/block]</p> => </div>
                '~<p>\[\/block\]<\/p>~i' => function ($match) {
                    $out = '</div>';
                    return $out;
                },

                // <p>[line]</p> => <div class="format-line">
                '~<p>\[line\]<\/p>~i' => function ($match) {
                    $out = '<div class="format-line">';
                    return $out;
                },

                // <p>[/line]</p> => </div>
                '~<p>\[\/line\]<\/p>~i' => function ($match) {
                    $out = '</div>';
                    return $out;
                },


            ],
            $input
        );

        return $output;
    }

}
