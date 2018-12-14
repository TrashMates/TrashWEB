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
    .sass('resources/assets/sass/loading.sass', 'public/css')

    .js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/stats.js', 'public/js')
	.js('resources/assets/js/tools/games.js', 'public/js/tools')
	.js('resources/assets/js/tools/scans.js', 'public/js/tools')²

    .styles(['resources/assets/sass/bootstrap.css'], 'public/css/bootstrap.css')
    .babel(['resources/assets/js/loading.js'], 'public/js/loading.js')
    .babel(['resources/assets/js/stream.js'], 'public/js/stream.js')

    .copyDirectory('resources/assets/images', 'public/images')
    .copyDirectory('resources/assets/js/libs', 'public/js/libs')
    .copyDirectory('resources/assets/mobile', 'public/mobile');