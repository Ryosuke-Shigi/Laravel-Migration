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

//mix.js(['resources/js/app.js','resources/js/jquery.js'], 'public/js'); ログインいれたらきえた！でもいけてた　reactでいれたらいけるのか
//react(['resources/js/app.js','resources/js/jquery.js'],'public/js')
mix.react(['resources/js/app.js','resources/js/jquery.js'],'public/js')
    .sass('resources/sass/app.scss', 'public/css');
