const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/assets/js')
   .sass('resources/sass/app.sass', 'public/assets/css')
   .copyDirectory('resources/css/libraries', 'public/assets/css/libraries')
   .copyDirectory('resources/js/libraries', 'public/assets/js/libraries');
