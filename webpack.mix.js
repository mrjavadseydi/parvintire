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

mix // digishop
    .sass('resources/mixer/digishop/template.scss', 'assets/template/digishop')
    .sass('resources/mixer/digishop/errors.scss', 'assets/errors/digishop')
    .js('resources/mixer/digishop/template.js', 'assets/template/digishop')
    .copyDirectory('resources/mixer/digishop/images/errors', 'public_html/assets/errors/digishop/images')
    .copyDirectory('resources/mixer/digishop/images/template', 'public_html/assets/template/digishop/images')
;

mix // personalshop
    .sass('resources/mixer/digishop/personal-shop.scss', 'assets/template/personalshop/template.css')
    .js('resources/mixer/digishop/personal-shop.js', 'assets/template/personalshop/template.js')
    .copyDirectory('resources/mixer/digishop/images/template', 'public_html/assets/template/personalshop/images')
;

// vue admin
// mix.js('resources/js/admin.js', 'assets/admin/vue');
// mix.sass('resources/sass/admin.scss', 'assets/admin/vue');
// mix.copyDirectory('resources/images/admin', 'public_html/assets/admin/vue/images');

if (mix.inProduction()) {
    mix.version();
}
