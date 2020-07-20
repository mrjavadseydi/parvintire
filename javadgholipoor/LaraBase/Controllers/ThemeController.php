<?php

namespace LaraBase\LaraBase\Controllers;

use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use LaraBase\CoreController;
use Project\LaraBase\Models\Theme;

class ThemeController extends CoreController {

    public function updateThemes($force = false) {

        if (isDev()) {

            $checkForUpdate = checkForUpdates();

            if ($checkForUpdate['status'] == 'success') {
                $unLinks = [];
                foreach ($checkForUpdate['themes'] as $theme) {
                    $unLinks = array_merge($unLinks, $this->installTheme($theme, $force));
                }
                foreach ($unLinks as $unLink) {
                    if (file_exists($unLink)) {
                        unlink($unLink);
                    }
                }
            }

            return redirect(route('admin.dashboard'))->with('success', 'بروزرسانی با موفقیت انجام شد');

        }

        return redirect(route('admin.dashboard'));

    }

    public function downloadFiles() {
        // وقتی دانلود و حذف می شود درست کار نمیکند
        // ابتدا همه چیز رو دانلود میکنیم بعد استخراج و نصب و در آخر حذف
    }

    public function installTheme($params, $force) {

        $type = $params['type'];
        $name = $params['name'];
        $version = $params['version'];
        $jsonPath = base_path("resources/views/{$type}/{$name}/theme.json");
        $unLinks = [];

        foreach ([
            base_path('downloads'),
            base_path('downloads/themes'),
            base_path("downloads/themes/{$type}"),
            base_path("downloads/themes/{$type}/{$name}"),
            base_path("downloads/plugins"),
            base_path("downloads/fonts")
        ] as $path) {
            if (!is_dir($path)) {
                mkdir($path);
            }
        }

        $install = true;

        if (!$force) {
            if (file_exists($jsonPath)) {
                $jsonData = json_decode(file_get_contents($jsonPath), true);
                if ($jsonData['version'] == $version) {
                    $install = false;
                }
            }
        }

        if ($install) {

            $url = $params['url'];
            $appKey   = getAppKey();
            $appUrl   = mainUrl(url(''));
            $file = base_path("downloads/themes/{$type}/{$name}/{$version}.zip");
            $unLinks[] = $file;

            if (downloadFile($url, $file, [
                "Authorization: Basic " . base64_encode("{$appUrl}:{$appKey}")
            ])) {

                extractZip($file, base_path(''));

                $newJsonFile = base_path("resources/views/{$type}/{$name}/theme.json");

                if (file_exists($newJsonFile)) {
                    $jsonData = json_decode(file_get_contents($newJsonFile), true);

                    if (isset($jsonData['assets']['plugins'])) {
                        $plugins = $jsonData['assets']['plugins'];
                        foreach ($plugins as $plugin => $item) {

                            $pluginFiles = $item['files'];
                            $pluginVersion = $item['version'];

                            foreach ([
                                base_path("public_html"),
                                base_path("public_html/plugins"),
                                base_path("public_html/plugins/{$plugin}"),
                                base_path("public_html/plugins/{$plugin}/{$pluginVersion}")
                            ] as $path) {
                                if (!is_dir($path)) {
                                    mkdir($path);
                                }
                            }

                            $downloadPlugin = false;
                            foreach ($pluginFiles as $file) {
                                if (!file_exists(base_path("public_html/plugins/{$plugin}/{$pluginVersion}/{$file}"))) {
                                    $downloadPlugin = true;
                                    break;
                                }
                            }

                            if ($downloadPlugin) {
                                $pluginPath = base_path("downloads/plugins/{$plugin}({$pluginVersion}).zip");
                                if (!file_exists($pluginPath)) {
                                    if (downloadFile(getRepository("download/plugin/{$plugin}/{$pluginVersion}"), $pluginPath)) {
                                        extractZip($pluginPath, base_path("public_html/plugins/{$plugin}"));
                                        $unLinks[] = $pluginPath;
                                    }
                                }
                            }

                        }
                    }

                    if (isset($jsonData['assets']['fonts'])) {
                        $fonts = $jsonData['assets']['fonts'];
                        foreach ($fonts as $font => $fontVersion) {

                            foreach ([
                                base_path("public_html"),
                                base_path("public_html/fonts"),
                                base_path("public_html/fonts/{$font}"),
                                base_path("public_html/fonts/{$font}/{$fontVersion}")
                            ] as $path) {
                                if (!is_dir($path)) {
                                    mkdir($path);
                                }
                            }

                            $downloadFont = false;
                            if (!file_exists(base_path("public_html/fonts/{$font}/{$fontVersion}/font.css"))) {
                                $downloadFont = true;
                            }

                            if ($downloadFont) {
                                $fontPath = base_path("downloads/fonts/{$font}({$fontVersion}).zip");
                                if (!file_exists($fontPath)) {
                                    if (downloadFile(getRepository("download/font/{$font}/{$fontVersion}"), $fontPath)) {
                                        extractZip($fontPath, base_path("public_html/fonts/{$font}"));
                                        $unLinks[] = $fontPath;
                                    }
                                }
                            }

                        }
                    }

                }

            }

        }

        return $unLinks;

    }

}
