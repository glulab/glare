<?php

namespace Glare\Support\ViewParsers;

class ColumnsNumberedViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~<p>\[col\.0\]<\/p>~i' => function ($match) {
                    $out = '<div class="row"><div class="col-12">';
                    return $out;
                },

                '~<p>\[col\.1\]<\/p>~i' => function ($match) {
                    $out = '<div class="row"><div class="col-12 col-lg-6 col-xl-8">';
                    return $out;
                },

                '~<p>\[col\.2\]<\/p>~i' => function ($match) {
                    $out = '</div><div class="col-12 col-lg-6 col-xl-4">';
                    return $out;
                },

                '~<p>\[col\.end\]<\/p>~i' => function ($match) {
                    $out = '</div></div>';
                    return $out;
                },

                '~<p>\[\/col\]<\/p>~i' => function ($match) {
                    $out = '</div></div>';
                    return $out;
                },
            ],
            $input
        );

        return $output;
    }

}
