<?php

namespace Glare\Support\ViewParsers;

class PagesViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        $output = preg_replace_callback_array(
            [
                '~\[page[#.](\d+)\]~i' => function ($match) {
                    try {
                        $page = \App\Models\Page::find($match[1]); // TODO: Move geting url logic to app
                        $url = $page->url ?? '#';
                    } catch (\Exception $e) {
                        $url = '#';
                    }
                    return $url;
                },
            ],
            $input
        );

        return $output;
    }

}
