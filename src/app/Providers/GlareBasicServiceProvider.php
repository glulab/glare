<?php

namespace Glare\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class GlareBasicServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
        // \Glare\Support\SupportServiceProvider::class,
        // \Glare\Litstack\LitstackServiceProvider::class,
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        // 'Glare' => \Facades\Glare\Glare::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->doMergeConfig();
        $this->alias();
        $this->providers();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->doLoadTranslations();
        $this->doLoadRoutes();
        $this->addViewLocationsAndNamespaces();
        $this->doLoadViews();
        $this->doLoadCommands();
        $this->doLoadMigrations();

        $this->addObservers();
        $this->addListeners();
        $this->registerMiddlewares();

        $this->doPublishes();
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function alias()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }

    /**
     * Register providers.
     *
     * @return void
     */
    protected function providers()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * [doMmergeConfig description]
     *
     * @return [type] [description]
     */
    public function doMergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/glare.php', 'glare');
        // $this->mergeConfigFrom(__DIR__.'/../../config/site.php', 'site');
    }

    /**
     * [doLoadTranslations description]
     *
     * @return [type] [description]
     */
    public function doLoadTranslations()
    {
        // // $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'glare');
        // foreach ((array) config('site.resources.lang') as $path => $namespace) {
        //     $this->loadTranslationsFrom(base_path($path), $namespace);
        // }
        // // loadJsonTranslationsFrom works only from package directory
        // foreach ((array) config('site.resources.json-lang') as $path) {
        //     $this->loadJsonTranslationsFrom(base_path($path));
        // }
    }

    /**
     * [doLoadRoutes description]
     *
     * @return [type] [description]
     */
    public function doLoadRoutes()
    {
        // $files = config('site.routes');

        // foreach ($files as $filePath => $middlewares) {
        //     $routeFilePath = base_path($filePath);
        //     if (is_file($routeFilePath)) {
        //         \Illuminate\Support\Facades\Route::prefix(\Locator::langPrefix())->middleware($middlewares)->group($routeFilePath);
        //     }
        // }

        // $this->namespace = null;
        // if (is_file(base_path('routes/web-base.php'))) {
        //     \Illuminate\Support\Facades\Route::middleware('web')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/web-base.php'));
        // }

        // \Illuminate\Support\Facades\Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

        // \Illuminate\Support\Facades\Route::prefix(\Locator::langPrefix())->middleware('web')->group(function () use ($files) {

        //     foreach ($files as $filePath) {
        //         $routeFilePath = base_path($filePath);
        //         if (is_file($routeFilePath)) {
        //             require_once $routeFilePath;
        //         }
        //     }

        // });

        // if (is_file(base_path('routes/web-context.php'))) {
        //     $this->loadRoutesFrom(base_path('routes/web-context.php'));
        // }
        // $this->loadRoutesFrom(base_path('glare/routes/web-site.php'));
        // if (is_file(base_path('routes/web-theme.php'))) {
        //     $this->loadRoutesFrom(base_path('routes/web-theme.php'));
        // }
    }

    /**
     * [addViewLocationsAndNamespaces description]
     *
     * * @return [type] [description]
     */
    public function addViewLocationsAndNamespaces()
    {
        // foreach ((array) config('site.resources.view-paths') as $path) {
        //     View::getFinder()->addLocation(base_path($path));
        // }
    }

    /**
     * [loadViews description]
     *
     * @return [type] [description]
     */
    public function doLoadViews()
    {
        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'glare');
        foreach ((array) config('site.resources.views') as $path => $namespace) {
            $this->loadViewsFrom(base_path($path), $namespace);
        }
    }

    /**
     * [doLoadCommands description]
     *
     * @return [type] [description]
     */
    public function doLoadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Glare\Console\Commands\GlareInstallCommand::class,
                \Glare\Console\Commands\GlareSwitchCommand::class,
            ]);
        }

        // $this->commands((array) config('site.commands'));
    }

    /**
     * [doLoadMigrations description]
     *
     * @return [type] [description]
     */
    public function doLoadMigrations()
    {
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * [addObservers description]
     */
    public function addObservers()
    {
        // foreach ((array) config('site.models-with-position') as $modelClass) {
        //     $modelClass::observe(\Glare\Observers\PositionSetterObserver::class);
        // }

        // if (class_exists(\App\Models\Page::class)) {
        //     \App\Models\Page::observe(\Glare\Observers\PageObserver::class);
        // }
    }

    /**
     * [addListeners description]
     */
    public function addListeners()
    {
        // \Illuminate\Support\Facades\Event::listen(\Glare\Events\PageHasBeenSaved::class, [\Glare\Listeners\CreateSitemap::class, 'handle']);
    }

    /**
     * [registerMiddlewares description]
     *
     * @return [type] [description]
     */
    public function registerMiddlewares()
    {
        //
    }

    /**
     * [doPublishes description]
     *
     * @return [type] [description]
     */
    public function doPublishes()
    {

        $this->publishes([__DIR__ . '/../../config/glare.php' => config_path('glare.php')], 'glare:config');

        $this->publishes([__DIR__ . '/../../publish/.htaccess' => base_path('.htaccess')], 'glare:base');
        $this->publishes([__DIR__ . '/../../publish/.user.ini' => base_path('.user.ini')], 'glare:base');
        $this->publishes([__DIR__ . '/../../publish/webpack.mix.js' => base_path('webpack.mix.js')], 'glare:base');

        // $this->publishes([__DIR__ . '/../../publish/dev.artisan.txt' => base_path('dev.artisan.txt')], 'glare:base');
        // $this->publishes([__DIR__ . '/../../publish/dev.env.local.txt' => base_path('dev.env.local.txt')], 'glare:base');
        // $this->publishes([__DIR__ . '/../../publish/dev.env.origin.txt' => base_path('dev.env.origin.txt')], 'glare:base');
        // $this->publishes([__DIR__ . '/../../publish/dev.env.txt' => base_path('dev.env.txt')], 'glare:base');

        // $this->publishes([__DIR__ . '/../../publish/app/Http/Controllers' => base_path('app/Http/Controllers')], ['glare:site', 'glare:site:controllers']);
        // $this->publishes([__DIR__ . '/../../publish/app/Models' => base_path('app/Models')], ['glare:site', 'glare:site:models']);
        // $this->publishes([__DIR__ . '/../../publish/app/View/Components' => base_path('app/View/Components')], ['glare:site', 'glare:site:view-components']);
        // $this->publishes([__DIR__ . '/../../publish/resources/views/components' => base_path('resources/views/components')], ['glare:site', 'glare:site:view-components']);
        // $this->publishes([__DIR__ . '/../../publish/resources/lang' => base_path('resources/lang')], ['glare:site', 'glare:site:lang']);
        // $this->publishes([__DIR__ . '/../../publish/resources/views' => base_path('resources/views')], ['glare:site', 'glare:site:views']);
        // $this->publishes([__DIR__ . '/../../publish/resources/images' => base_path('resources/images')], ['glare:site', 'glare:site:images']);
        // $this->publishes([__DIR__ . '/../../publish/routes' => base_path('routes')], ['glare:site', 'glare:site:routes']);
        // $this->publishes([__DIR__ . '/../../publish/config' => base_path('config')], ['glare:site', 'glare:site:config']);

        // publishes translations from vendor/laravel-lang/lang
        // $this->publishes([base_path('vendor/laravel-lang/lang/src') => resource_path('lang')], 'glare:laravel-lang:all-langs');
        // $this->publishes([base_path('vendor/laravel-lang/lang/json') => resource_path('lang')],'glare:laravel-lang:all-langs');

        // $this->publishes([base_path('vendor/laravel-lang/lang/src/pl') => resource_path('lang/pl')], 'glare:laravel-lang:pl');
        // $this->publishes([base_path('vendor/laravel-lang/lang/json/pl.json') => resource_path('lang/pl.json')],'glare:laravel-lang:pl');
    }
}
