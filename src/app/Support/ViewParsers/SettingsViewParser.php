<?php

namespace Glare\Support\ViewParsers;

class SettingsViewParser
{
    public function __construct()
    {
        //
    }

    public function parse($input)
    {
        /**
         * [!setting_name]
         * [!+context_setting_name]
         * [!:site_setting_name]
         *
         * @var [type]
         */
        $output = preg_replace_callback_array(
            [
                '~\[!([a-zA-Z0-9:+_.-]+)\]~i' => function ($match) {
                    $match = $match[1] ?? '';
                    $matchFirstChar = substr($match, 0, 1);
                    switch ($matchFirstChar) {
                        case ':':
                            $match = ltrim($match, ':');
                            $s = \LitSettings::site($match);
                            break;

                        case '+':
                            $match = ltrim($match, '+');
                            $s = \LitSettings::context($match);
                            break;

                        default:
                            $s = \LitSettings::settings($match);
                            break;
                    }

                    return $s;
                },
            ],
            $input
        );

        return $output;
    }

}
