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

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    // .copy('resources/assets/images', 'public/images')
    .copy('node_modules/font-awesome/fonts', 'public/fonts')
    .copy('node_modules/font-awesome/fonts', 'public/build/fonts')
    .copy('node_modules/bootstrap-sass/assets/fonts', 'public/fonts')
    .copy('node_modules/bootstrap-sass/assets/fonts', 'public/build/fonts');

