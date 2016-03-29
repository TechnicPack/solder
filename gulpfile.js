var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('bootstrap.scss', 'resources/assets/css')
       .copy('node_modules/font-awesome/css/font-awesome.css', 'resources/assets/css/font-awesome.css')
       .copy('node_modules/jquery/dist/jquery.js', 'resources/assets/js/jquery.js')
       .copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.js', 'resources/assets/js/bootstrap.js')
       .copy('node_modules/datatables/media/js/jquery.dataTables.js', 'resources/assets/js/jquery.dataTables.js')
       .copy('node_modules/datatables-bootstrap/js/dataTables.bootstrap.js', 'resources/assets/js/dataTables.bootstrap.js')
       .copy('node_modules/datatables-bootstrap/css/dataTables.bootstrap.css', 'resources/assets/css/dataTables.bootstrap.css')
       .styles([
           'font-awesome.css',
           'bootstrap.css',
           'dataTables.bootstrap.css'
       ], 'public/css/app.css')
       .scripts([
           'jquery.js',
           'bootstrap.js',
           'jquery.dataTables.js',
           'dataTables.bootstrap.js',
           'solder.js'
       ], 'public/js/app.js')
       .version(['css/app.css', 'js/app.js']);
});
