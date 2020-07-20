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

mix.setPublicPath('public_html');

mix.options({
    processCssUrls: false
});

mix // default

    // admin
    .sass('resources/mixer/default/admin.scss', 'assets/admin/default')
    .js('resources/mixer/default/admin.js', 'assets/admin/default')
    .copyDirectory('resources/mixer/default/images/admin', 'public_html/assets/admin/default/images')

    // store
    .js('resources/mixer/tools/js/store.js', 'assets/admin/default')

    // boxes
    .js('resources/mixer/default/boxes.js', 'assets/admin/default')

    // icons
    .sass('resources/mixer/default/icons.scss', 'assets/admin/default')
    .js('resources/mixer/default/icons.js', 'assets/admin/default')

    // auth
    .sass('resources/mixer/default/auth.scss', 'assets/auth/default')

    // errors
    .sass('resources/mixer/default/errors.scss', 'assets/errors/default')

    // uploader
    .js('resources/mixer/default/uploader.js', 'assets/uploader/default')
    .js('resources/mixer/default/uploader2.js', 'assets/uploader/default')
    .sass('resources/mixer/default/uploader.scss', 'assets/uploader/default')
    .copyDirectory('resources/mixer/default/images/uploader', 'public_html/assets/uploader/default/images')

;

if (mix.inProduction()) {
    mix.version();
}
