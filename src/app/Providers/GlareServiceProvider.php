<?php

namespace Glare\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class GlareServiceProvider extends ServiceProvider
{
    /**
     * Service providers.
     *
     * @var array
     */
    protected $providers = [
        \Glare\Support\SupportServiceProvider::class,
        \Glare\Litstack\LitstackServiceProvider::class,
    ];

    /**
     * Aliases.
     *
     * @var array
     */
    protected $aliases = [
        'Glare' => \Facades\Glare\Glare::class,
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
        $this->registerMiddlewares();

        $this->addViewLocationsAndNamespaces();

        $this->doPublishes();
        $this->doLoadCommands();
        $this->doLoadRoutes();
        $this->doLoadTranslations();
        $this->doLoadViews();

        $this->addListeners();
        $this->addObservers();
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
     * [registerMiddlewares description]
     *
     * @return [type] [description]
     */
    public function registerMiddlewares()
    {
        //
    }

    /**
     * [addViewLocationsAndNamespaces description]
     *
     * * @return [type] [description]
     */
    public function addViewLocationsAndNamespaces()
    {
        View::getFinder()->addLocation(resource_path('views-default'));
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
     * [doPublishes description]
     *
     * @return [type] [description]
     */
    public function doPublishes()
    {

        $this->publishes([__DIR__ . '/../../config/glare.php' => config_path('glare.php')], 'glare:config');

        // $this->publishes([__DIR__ . '/../../publish/.htaccess' => base_path('.htaccess')], 'glare:base');
        // $this->publishes([__DIR__ . '/../../publish/.user.ini' => base_path('.user.ini')], 'glare:base');
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
        $this->publishes([base_path('vendor/laravel-lang/lang/src') => resource_path('lang')], 'glare:laravel-lang:all-langs');
        $this->publishes([base_path('vendor/laravel-lang/lang/json') => resource_path('lang')],'glare:laravel-lang:all-langs');

        $this->publishes([base_path('vendor/laravel-lang/lang/src/pl') => resource_path('lang/pl')], 'glare:laravel-lang:pl');
        $this->publishes([base_path('vendor/laravel-lang/lang/json/pl.json') => resource_path('lang/pl.json')],'glare:laravel-lang:pl');
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
                \Glare\Console\Commands\GlareCleanupCommand::class,
                \Glare\Console\Commands\GlareSchedulerTestCommand::class,
            ]);
        }

        $this->commands([
            \Glare\Console\Commands\GlareSitemapCommand::class,
        ]);
    }

    /**
     * [doLoadRoutes description]
     *
     * @return [type] [description]
     */
    public function doLoadRoutes()
    {
        // $this->namespace = null;
        // if (is_file(base_path('routes/web-base.php'))) {
        //     \Illuminate\Support\Facades\Route::middleware('web')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/web-base.php'));
        // }
        // $this->loadRoutesFrom(base_path('routes/web-base.php'));
    }

    /**
     * [doLoadTranslations description]
     *
     * @return [type] [description]
     */
    public function doLoadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'glare');
        $this->loadJsonTranslationsFrom(resource_path('lang-default'));
    }

    /**
     * [loadViews description]
     *
     * @return [type] [description]
     */
    public function doLoadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'glare');
    }

    /**
     * [addListeners description]
     */
    public function addListeners()
    {
        \Illuminate\Support\Facades\Event::listen(\Glare\Events\PageHasBeenSaved::class, [\Glare\Listeners\CreateSitemap::class, 'handle']);
    }

    /**
     * [addObservers description]
     */
    public function addObservers()
    {
        foreach ((array) config('site.models-with-position') as $modelClass) {
            $modelClass::observe(\Glare\Observers\PositionSetterObserver::class);
        }

        if (class_exists(\App\Models\Page::class)) {
            \App\Models\Page::observe(\Glare\Observers\PageObserver::class);
        }
    }
}
