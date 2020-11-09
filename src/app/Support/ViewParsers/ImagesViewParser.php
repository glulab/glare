<?php

namespace Glare\Support\ViewParsers;

class ImagesViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input, $images)
    {
        $output = preg_replace_callback_array(
            [
                /**
                 * [IMAGE] OR [IMG]
                 * [IMAGE.{n}(..L)(..1/2)(..ALT:Tekst tagu alt)]
                 */
                '~\[ima?ge?\.(\d+)(\.\.([a-zA-Z]+))?(\.\.\d+\/(\d+))?(\.\.alt:([a-zA-Z0-9-_. ]+))?\]~i' => function ($match) use ($images) {

                    // dump($match);

                    // $size of conversion in media-library
                    $size = ''; // /*$match[1] ??*/ 'main';

                    // get number of an image
                    $number = isset($match[1]) ? $match[1] - 1 : 0;

                    // check if float
                    $float = '';
                    if (isset($match[3])) {
                        $matchFloat = strtolower($match[3]);
                        switch ($matchFloat) {
                            case 'l':
                                $float = 'float-left mr-2';
                                break;
                            case 'r':
                                $float = 'float-right ml-2';
                                break;
                            case 'c':
                                $float = 'mx-auto d-block';
                                break;

                            default:
                                $float = '';
                                break;
                        }
                    }

                    // check if width
                    $width = '';
                    if (isset($match[5])) {
                        $width = $match[5];
                    }

                    // check if alternative alt is specified
                    $alt = '';
                    if (isset($match[7])) {
                        $alt = $match[7];
                    }

                    // get image from images array
                    $img = \Arr::get($images, $number);

                    // no image return
                    if (is_null($img)) {
                        return '';
                    }

                    // resolve alt
                    $alt = !empty($alt) ? $alt : $img->alt;

                    // resolve title
                    $title = !empty($img->title) ? $img->title : '';

                    // class
                    $class = 'rounded mb-2';
                    $class .= !empty($float) ? ' ' . $float : '';
                    $class .= !empty($width) ? ' image-w-' . $width : '';

                    // img attributes
                    $attr = [];
                    $attr = array_replace($attr, ['alt' => (string) $alt]);
                    $attr = array_replace($attr, !empty($title) ? ['title' => $img->title] : []);
                    $attr = array_replace($attr, ['class' => $class]);

                    $out = $img($size)->attributes($attr)->lazy(); // $out = '<img class="rounded mb-2 image-w-'.$width.'" src="'. $img->getUrl($size) .'" alt="'.$img->alt.'">';

                    return $out;
                },

                // simple one DEPRECATED?
                // '~\[ima?ge?\.(\d+)\]~i' => function ($match) use ($images) {
                //     $size = '';
                //     $number = isset($match[1]) ? $match[1] - 1 : 0;
                //     $img = \Arr::get($images, $number);
                //     if (is_null($img)) {
                //         return '';
                //     }
                //     $out = $img($size)->attributes(['class' => 'rounded mb-2', 'alt' => $img->alt])->lazy(); // $out = '<img class="rounded mb-2" src="'. $img->getUrl($size) .'" alt="'.$img->alt.'">';
                //     return $out;
                // },
            ],
            $input
        );

        return $output;
    }

}
