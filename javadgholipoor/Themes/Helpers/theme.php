<?php

function getThemeTypes() {
    return [
        'auth'       => $options['authTheme'] ?? 'default',
        'admin'      => $options['adminTheme'] ?? 'default',
        'template'   => $options['templateTheme'] ?? 'default',
        'email'      => $options['emailTheme'] ?? 'default',
        'uploader'   => $options['uploaderTheme'] ?? 'default',
        'errors'     => $options['errorsTheme'] ?? 'default',
    ];
}

function getTheme($type) {
    $options = doAction('themes');
    return $options[$type] ?? 'default';
}

function getDefaultOptionImages() {
    return [
        'تصاویر پیشفرض' => [
            [
                'title' => 'لوگوی سایت',
                'description' => '',
                'key' => 'siteLogo',
                'more' => 'autoload'
            ],
            [
                'title' => 'لوگوی متنی سایت',
                'description' => '',
                'key' => 'siteTextLogo',
                'more' => 'autoload'
            ],
            [
                'title' => 'فاوآیکون',
                'description' => 'سایز استاندارد فاوآیکون 16x16 پیکسل می باشد',
                'key' => 'favicon',
                'more' => 'autoload'
            ]
        ]
    ];
}

function getThemesImages() {

    $images = getDefaultOptionImages();

    foreach ([
        'admin' => 'تصاویر پنل مدیریت',
        'template' => 'تصاویر قالب سایت',
        'auth' => 'تصاویر قالب ورود/ثبت‌نام',
        'email' => 'تصاویر قالب ایمیل',
        'uploader' => 'تصاویر قالب آپلودر'
    ] as $type => $title) {

        $theme = getTheme($type);
        $jsonPath = base_path("resources/views/{$type}/{$theme}/theme.json");
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            if (isset($jsonData['options']['images'])) {
                foreach ($jsonData['options']['images'] as $image) {
                    $images[$title][] = [
                        'title' => $image['title'],
                        'description' => $image['description'],
                        'key' => $image['key'],
                        'more' => $image['more']
                    ];
                }
            }
        }

    }

    return $images;

}

function getThemesValues() {

    $images = [];

    foreach ([
        'admin' => 'پنل مدیریت',
        'template' => 'قالب سایت',
        'auth' => 'قالب ورود/ثبت‌نام',
        'email' => 'قالب ایمیل',
        'uploader' => 'قالب آپلودر',
        'errors' => 'قالب ارور'
    ] as $type => $title) {

        $theme = getTheme($type);
        $jsonPath = base_path("resources/views/{$type}/{$theme}/theme.json");
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            if (isset($jsonData['options']['values'])) {
                foreach ($jsonData['options']['values'] as $image) {
                    $images[$title][] = $image;
                }
            }
        }

    }

    return $images;

}

function getThemeVersion($type, $name) {
    $jsonPath = base_path("resources/views/{$type}/{$name}/theme.json");
    if (file_exists($jsonPath)) {
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        return $jsonData['version'];
    }
    return 'withoutJson';
}

function getIcons($type) {
    $theme = getTheme($type);
    $json = json_decode(file_get_contents(base_path("resources/views/{$type}/{$theme}/theme.json")));
    return $json->icons;
}

function renderTheme($type, $theme = null) {

    if ($theme == null)
        $theme = getTheme($type);

    $json = json_decode(file_get_contents(base_path("resources/views/{$type}/{$theme}/theme.json")));
    $themeName = $json->name;
    $themeVersion = $json->version;

    $path = "assets/{$type}/" . $themeName;

    $pluginsJs = "{$path}/plugins.js";
    $pluginsCss = "{$path}/plugins.css";
    $appCss = "{$path}/{$type}.css";
    $appJs = "{$path}/{$type}.js";

    $remove = false;
    if (env('APP_DEBUG'))
        if (!env('SERVER'))
            $remove = true;

    if ($remove) {
        foreach ([$pluginsJs, $pluginsCss, $appCss, $appJs] as $delete) {
            if (file_exists($delete)) {
                unlink($delete);
            }
        }
    }

    $directories = [
        "assets",
        "assets/{$type}",
        $path
    ];

    foreach ($directories as $directory) {
        $dir = public_path($directory);
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    // fonts
    if (isset($json->assets->fonts)) {
        foreach ($json->assets->fonts as $name => $version) {
            css("fonts/{$name}/{$version}/font.min.css");
        }
    }

    // plugins
    if (!file_exists($pluginsCss) || !file_exists($pluginsJs)) {
        $pCssFiles = $pJsFiles = [];
        if (isset($json->assets->plugins)) {
            foreach ($json->assets->plugins as $name => $data) {
                $version = $data->version;
                foreach ($data->files as $file) {
                    $parts = explode('.', $file);
                    $extension = end($parts);
                    $pluginPath = "plugins/{$name}/{$version}/{$file}";
                    if ($extension == 'css')
                        $pCssFiles[] = $pluginPath;
                    else
                        js($pluginPath);
                }
            }
        }
        compressCss($pluginsCss, $pCssFiles);
    }

    css($pluginsCss);
    css($appCss, $themeVersion);
    js($appJs, $themeVersion);

}

function css($path, $version = '') {
    if (file_exists($path))
        echo '<link rel="stylesheet" href="' . asset($path) . ($version != '' ? '?v=' . $version : '') . '">' . "\n\t";
}

function js($path, $version = '') {
    if (file_exists($path))
        echo '<script src="' . asset($path) . ($version != '' ? '?v=' . $version : '') . '"></script>' . "\n\t";
}

function image($name, $type = 'admin', $returnPath = false) {
    $theme = getTheme($type);
    $path = "assets/{$type}/{$theme}/images/{$name}";

    if ($returnPath)
        return $path;

    return asset($path);
}

function includeTemplate($file) {
    return 'template.' . getTheme('template') . '.' . $file;
}

function includeAdmin($file) {
    return 'admin.' . getTheme('admin') . '.' . $file;
}

function compressCss($path, $files)
{
    $minifier = new \MatthiasMullie\Minify\CSS();
    foreach ($files as $file) {
        if (file_exists($file))
            $minifier->add($file);
    }
    $minifier->minify($path);
}

function compressJs($path, $files)
{
    $minifier = new \MatthiasMullie\Minify\JS();
    foreach ($files as $file) {
        if (file_exists($file))
            $minifier->add($file);
    }
    $minifier->minify($path);
}
