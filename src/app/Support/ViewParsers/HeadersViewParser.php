<?php

namespace Glare\Support\ViewParsers;

class HeadersViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~\[h(\d+)\]~i' => function ($match) {
                    $out = '<h'.$match[1].'>';
                    return $out;
                },

                '~\[\/h(\d+)\]~i' => function ($match) {
                    $out = '</h'.$match[1].'>';
                    return $out;
                },
            ],
            $input
        );

        return $output;
    }

}
