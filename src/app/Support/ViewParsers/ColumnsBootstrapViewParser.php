<?php

namespace Glare\Support\ViewParsers;

class ColumnsBootstrapViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~<p>\[col\.start\.(\d+)\]<\/p>~i' => function ($match) {
                    $out = '<div class="row"><div class="col-12 col-lg-'.$match[1].'">';
                    return $out;
                },

                '~<p>\[col\.next\.(\d+)\]<\/p>~i' => function ($match) {
                    $out = '</div><div class="col-12 col-lg-'.$match[1].'">';
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
