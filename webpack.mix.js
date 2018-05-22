let mix = require('laravel-mix');

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

mix.sass('resources/assets/sass/app.sass', 'public/css')
    .sass('resources/assets/sass/animate.sass', 'public/css')
    .js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/stats.js', 'public/js');
