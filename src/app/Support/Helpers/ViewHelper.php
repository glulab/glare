<?php

namespace Glare\Support\Helpers;

class ViewHelper {

    public function format($str, $nl2br = true)
    {
        $str = str_replace(' ,', ',', $str);
        $str = str_replace(' .', '.', $str);
        $str = str_replace(' !', '!', $str);
        $str = str_replace(['( ', ' )'], ['(', ')'], $str);
        $str = str_replace(
            [
                ' a ', ' i ', ' o ', ' u ', ' w ', ' z ',
            ],
            [
                ' a&nbsp;', ' i&nbsp;', ' o&nbsp;', ' u&nbsp;', ' w&nbsp;', ' z&nbsp;',
            ],
            $str
        );

        if ($nl2br) {
            $str = nl2br($str);
        }

        return $str;
    }

    public function parse($text)
    {
        $text = \App::make(\Glare\Support\ViewParsers\ColumnsWithNamesViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\ColumnsNumberedViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\ColumnsBootstrapViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\BlocksViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\TagsViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\HeadersViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\SettingsViewParser::class)->parse($text);
        $text = \App::make(\Glare\Support\ViewParsers\PagesViewParser::class)->parse($text);
        return $text;
    }

    public function parseImages($text, $images = null)
    {
        $text = \App::make(\Glare\Support\ViewParsers\GalleryViewParser::class)->parse($text, $images);
        $text = \App::make(\Glare\Support\ViewParsers\ImageBlocksViewParser::class)->parse($text, $images);
        $text = \App::make(\Glare\Support\ViewParsers\ImagesViewParser::class)->parse($text, $images);
        return $text;
    }

    public function formatPage($text, $images, $nl2br = false)
    {
        $text = $this->parse($text);
        $text = $this->parseImages($text, $images);

        return $this->format($text, $nl2br);
    }

    public function splitToLines($object, $inputField = 'text', $outputField = 'lines', $splitter = '|')
    {
        if (empty($object)) {
            return $object;    
        }
        
        if (!isset($object->$inputField)) {
            $object->lines = [];
        } else {
            $lines = explode($splitter, $object->$inputField);
            foreach($lines as $k => $line) {
                $lines[$k] = trim($line);
            }
            $object->lines = $lines;
        }

        return $object;
    }
}
