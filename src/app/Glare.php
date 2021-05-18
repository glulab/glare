<?php

namespace Glare;

use Illuminate\Support\Facades\Route;

class Glare
{
    // const SERVICE_SERVICENAME = 'servicename';

    public static $services = [];

    public static function addService($service)
    {
        if (!in_array($service, static::$services))
        {
            array_push(static::$services, $service);
        }
    }

    public static function hasService($service)
    {
        return in_array($service, static::$services);
    }

    /**
     * [routes description]
     *
     * @return [type] [description]
     */
    public function routes($suffixes = [])
    {
        \Illuminate\Support\Facades\Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

        \Illuminate\Support\Facades\Route::prefix(\Locator::langPrefix())->group(function () use ($suffixes) {

            foreach ($suffixes as $suffix) {
                $routeFilePath = base_path('routes/web-'.$suffix.'.php');
                require_once $routeFilePath;
            }

        });
    }

    // public function cookieRoutes()
    // {
    //     Route::prefix('site')->group(function () {

    //         Route::post('info-bottom-cookie-accept', [\Glare\Http\Controllers\InfoBottomCookieController::class, 'accept'])->name('site.info-bottom-cookie-accept');
    //         Route::post('info-modal-cookie-accept', [\Glare\Http\Controllers\InfoModalCookieController::class, 'accept'])->name('site.info-modal-cookie-accept');

    //     });
    // }

    // public function contactFormRoutes()
    // {
    //     Route::prefix('site')->group(function () {

    //         Route::post('contact-form', [\Glare\Http\Controllers\ContactFormController::class, 'send'])->name('site.contact-form');

    //     });
    // }
}
