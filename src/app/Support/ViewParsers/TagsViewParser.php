<?php

namespace Glare\Support\ViewParsers;

class TagsViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                // <p>[hr]</p> => <hr>
                '~<p>\[hr\]<\/p>~i' => function ($match) {
                    $out = '<hr>';
                    return $out;
                },
            ],
            $input
        );

        return $output;
    }

}
