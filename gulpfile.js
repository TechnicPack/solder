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
    // Get the CSS all good to go
    mix.sass('bootstrap.scss')
       .copy('node_modules/font-awesome/css/font-awesome.min.css', 'public/css/font-awesome.min.css')
       .copy('node_modules/font-awesome/fonts', 'public/fonts')
       .copy('node_modules/bootstrap/assets/fonts', 'public/fonts')
       .copy('node_modules/datatables-bootstrap/css/dataTables.bootstrap.min.css', 'public/css/dataTables.bootstrap.min.css')

    // Get the javascript ready
    mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
       .copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'public/js/bootstrap.min.js')
       .copy('node_modules/datatables/media/js/jquery.dataTables.min.js', 'public/js/jquery.dataTables.min.js')
       .copy('node_modules/datatables-bootstrap/js/dataTables.bootstrap.min.js', 'public/js/dataTables.bootstrap.min.js')
       .scripts([
           'slugify.js'
       ], 'public/js/app.js')

    // Version the things we control
    mix.version(['css/bootstrap.css', 'js/app.js']);
});
