const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps(false, 'inline-source-map');

mix.copyDirectory('resources/images', 'public/images');

mix.webpackConfig({
    resolve: {
        modules: [
            path.join('D:', 'www', 'dev', 'npm-modules'),
            path.resolve(__dirname, 'node_modules'),
            'node_modules',
        ]
    }
});
