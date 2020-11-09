<?php

namespace Glare\Support\Locator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class Locator
{
    public function setLang($locale)
    {
        App::setLocale($locale);
    }

    public function setLangOrDefault($lang)
    {
        if (in_array($lang, config('site.langs') ?? [])) {
            static::setLang($lang);
            return $lang;
        }

        static::setLang(config('site.lang'));
        return config('site.lang');
    }

    public function langPrefix()
    {
        $langSegment = Request::segment(1);

        $lang = static::setLangOrDefault($langSegment);

        return $lang === config('site.lang') ? null : $lang;
    }

    public function addLangPrefix($url, $lang)
    {
        if ($lang !== config('site.lang')) {
            return $lang . '/' . $url;
        } else {
            return $url;
        }
    }

    /**
     * url
     *
     * @param [type]  $url  url to localize
     * @param [type]  $lang lang to add prefix, if lang is null current prefix is added, if lang is false default prefix is added
     * @param boolean $full parse to url generator
     *
     * @return [type]  [description]
     */
    public function url($url, $lang = null, $full = true, $parameters = [], $secure = null)
    {
        // dump($url);
        $currentLang = App::getLocale();

        if (is_null($lang)) {
            $lang = $currentLang;
        }

        if ($lang === false) {
            $lang = config('site.lang');
        }

        // trim
        $urlTrimmed = trim($url);

        // remove app domain from url
        $urlNoDomain = Str::replaceFirst(config('app.url'), '', $urlTrimmed);

        // if withOUT app domain url is still valid it means it is external url
        if (filter_var($urlNoDomain, FILTER_VALIDATE_URL) !== false) {
            return $urlTrimmed;
        }

        // remove current locale from url
        $urlNoLeadingLang = Str::replaceFirst($currentLang . '/', '', $urlNoDomain);

        // remove first /
        $urlNoLeadingSlash = Str::replaceFirst('/', '', $urlNoLeadingLang);

        // add lang prefix
        $urlPrefixed = $this->addLangPrefix($urlNoLeadingSlash, $lang);

        // ad first
        $urlPrefixed =  '/' . $urlPrefixed;

        if ($full === true) {
            $urlPrefixed =  URL::to($urlPrefixed, $parameters, $secure);
        }

        if ($full === true && $urlNoLeadingSlash === '' && $currentLang !== config('site.lang')) {
            $urlPrefixed = $urlPrefixed . '/';
        }

        return $urlPrefixed;
    }
}
