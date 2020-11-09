<?php

namespace Glare\Support\ViewParsers;

class ColumnsWithNamesViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~<p>\[col\.left\.center\]<\/p>~i' => function ($match) {
                    $out = '<div class="row"><div class="col-12 col-lg-6 col-xl-8 align-self-center">';
                    return $out;
                },

                '~<p>\[col\.right\.center\]<\/p>~i' => function ($match) {
                    $out = '</div><div class="col-12 col-lg-6 col-xl-4 align-self-center">';
                    return $out;
                },

                '~<p>\[col\.left\]<\/p>~i' => function ($match) {
                    $out = '<div class="row"><div class="col-12 col-lg-6 col-xl-8">';
                    return $out;
                },

                '~<p>\[col\.right\]<\/p>~i' => function ($match) {
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
