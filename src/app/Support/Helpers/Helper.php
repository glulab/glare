<?php

namespace Glare\Support\Helpers;

class Helper {

    public function getFromCollection($collection, $key, $keyField, $valueField)
    {
        if (count($collection) === 0) {
            return null;
        }
        foreach ($collection as $k => $v) :
            if ($v->$keyField == $key) :
                return $v->$valueField;
            endif;
        endforeach;
    }

    public function removeSuffix($str, $suffix = '.html')
    {
        if (!is_string($str)) {
            return $str;
        }

        return str_replace($suffix, '', $str);
    }

    public function attributesToString($attrs = [])
    {
        if (empty($attrs)) {
            return '';
        }

        return str_replace('=', '="', http_build_query($attrs ?? [], null, '" ')).'"';
    }
}
