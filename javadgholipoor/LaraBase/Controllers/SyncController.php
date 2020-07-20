<?php


namespace LaraBase\LaraBase\Controllers;


use LaraBase\CoreController;
use LaraBase\Options\Models\Option;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class SyncController extends CoreController
{

    private $masterToken = null;

    public function sync()
    {
        if (isDev()) {
            $appName = env('APP_NAME');
            $themes = Option::where('key', 'like', '%Theme')->get();
            return view('larabase.sync', compact(
                'appName', 'themes'
            ));
        }

        abort(404);
    }

    public function run()
    {

        if (isDev()) {

            set_time_limit(600);

            $this->masterToken = file_get_contents('../../master-token.txt');

            $message = null;

            if ($_GET['larabase'] ?? '' == 'on') {
                $res = json_decode($this->buildLaraBase());
                if ($res->status == 'success') {
                    $message .= '<span style="color: green;">larabase upload successfully</span><br>';
                } else {
                    $message .= '<span style="color: red;">larabase upload unsuccessfully</span><br>';
                }
            }

            if ($_GET['project'] ?? '' == 'on') {
                $res = json_decode($this->buildProject($_GET['projectName']));
                if ($res->status == 'success') {
                    $message .= '<span style="color: green;">project upload successfully</span><br>';
                } else {
                    $message .= '<span style="color: red;">project upload unsuccessfully</span><br>';
                }
            }

            foreach ($_GET['themes'] ?? [] as $type => $name) {
                $res = json_decode($this->buildTheme($type, $name));
                if ($res->status == 'success') {
                    $message .= '<span style="color: green;">'.$type.'/'.$name.' upload successfully</span><br>';
                } else {
                    $message .= '<span style="color: red;">'.$type.'/'.$name.' upload successfully</span><br>';
                }
            }

            deleteCache('options');

            echo $message;

        } else {
            abort(404);
        }

    }

    public function buildLaraBase()
    {
        $getVersion = Option::where('key', 'appVersion')->first();
        $version = intval($getVersion->value) + 1;
        $fileName = 'LaraBase(' . $version . ').zip';
        $this->compress('downloads/apps/LaraBase', $fileName, [
            'app',
            'bootstrap',
            'config',
            'database',
            'javadgholipoor',
            'resources/lang',
            'resources/views/boxes/default',
            'resources/views/errors/default',
            'resources/views/filters/default',
            'resources/views/icons/default',
            'resources/views/larabase',
            'resources/views/modals/default',
            'resources/views/template/default',
            'resources/views/uploader/default',
            'resources/views/vendor',
            'routes',
            'storage/framework/testing/disks/local',
            'tests',
            'vendor',
        ], [
            '.editorconfig',
            '.gitattributes',
            '.gitignore',
            '.styleci.yml',
            '_ide_helper.php',
            'artisan',
            'composer.json',
            'composer.lock',
            'package.json',
            'package-lock.json',
            'phpunit.xml',
            'readme.md',
            'server.php',
            'storage/app/.gitignore',
            'storage/app/public/.gitignore',
            'storage/framework/cache/.gitignore',
            'storage/framework/cache/data/.gitignore',
            'storage/framework/sessions/.gitignore',
            'storage/framework/testing/.gitignore',
            'storage/framework/views/.gitignore',
            'storage/logs/.gitignore',
            'projects/AppServiceProvider.php',
            'projects/autoload.php',
            'public_html/default/css/uploader.css',
            'public_html/default/js/uploader.js',
            'public_html/.htaccess',
            'public_html/favicon.ico',
            'public_html/index.php',
            'public_html/mix-manifest.json',
            'public_html/web.config',
            'resources/views/admin/head.blade.php',
            'resources/views/template/head.blade.php',
            'resources/views/auth/head.blade.php',
            'resources/views/errors/head.blade.php'
        ], [
            '.env' => getEnvContent([
                'appKey' => '',
                'appName' => '',
                'databaseName' => '',
                'databaseUser' => '',
                'databasePassword' => '',
                'siteEmailPassword' => '',
                'siteEmail' => '',
                'googleReCaptchaV3SecretKey' => '',
                'googleReCaptchaV3SiteKey' => '',
            ])
        ]);

        $key = 'appVersion';
        if (Option::where('key', $key)->exists()) {
            Option::where('key', $key)->update(['value' => $version]);
        } else {
            Option::create(['key' => $key, 'value' => $version, 'more' => 'autoload']);
        }

        return uploadFiles(getRepository('sync/larabase'), [
            'file' => base_path("downloads/apps/LaraBase/{$fileName}")
        ], [
            'token' => $this->masterToken,
            'fileName' => $fileName,
            'version' => $version
        ]);

    }

    public function buildProject($appName)
    {

        $version = intval(getProjectVersion($appName)) + 1;
        $fileName = 'LaraBase('. $appName .')(' . $version . ').zip';

        $this->compress("downloads/apps/{$appName}", $fileName, [
            "projects/{$appName}"
        ],[], []);

        $key = "{$appName}AppVersion";
        if (Option::where('key', $key)->exists()) {
            Option::where('key', $key)->update(['value' => $version]);
        } else {
            Option::create(['key' => $key, 'value' => $version, 'more' => 'autoload']);
        }

        return uploadFiles(getRepository('sync/project'), [
            'file' => base_path("downloads/apps/{$appName}/{$fileName}")
        ], [
            'token' => $this->masterToken,
            'appName' => $appName,
            'fileName' => $fileName,
            'version' => $version
        ]);

    }

    public function buildTheme($type, $name)
    {

        $themePath = base_path("resources/views/{$type}/{$name}");
        $jsonPath = "{$themePath}/theme.json";
        $jsonData = [];
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
        }

        $version = $jsonData['version'];
        $fileName = "{$version}.zip";
        $this->compress("downloads/themes/{$type}/{$name}", $fileName, [
            "public_html/assets/$type/".strtolower($name),
            "resources/views/{$type}/{$name}"
        ], [
            "resources/views/{$type}/{$name}/theme.json",
        ], []);

        return uploadFiles(getRepository('sync/theme'), [
            'file' => base_path("downloads/themes/{$type}/{$name}/{$fileName}")
        ], [
            'token' => $this->masterToken,
            'type' => $type,
            'name' => $name,
            'fileName' => $fileName
        ]);

    }

    public function compress($path, $fileName, $directories, $files, $fileFromString)
    {

        $dir = [];
        $pathParts = explode('/', $path);
        for ($i = 0; $i < count($pathParts); $i++) {
            $dir[] = $pathParts[$i];
            $directory = implode('/', $dir);
            if (!is_dir(base_path($directory))) {
                mkdir(base_path($directory));
            }
        }

        $filePath = base_path($path . '/' . $fileName);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $zip = new ZipArchive;

        if ($zip->open($filePath, ZipArchive::CREATE) === TRUE) {

            foreach ($files as $file) {
                $zip->addFile(base_path($file), $file);
            }

            foreach ($directories as $directory) {
                $rootPath = realpath(base_path($directory));
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = "{$directory}/" . substr($filePath, strlen($rootPath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }

            }

            foreach ($fileFromString as $fName => $fContent) {
                $zip->addFromString($fName, $fContent);
            }

            $zip->close();

        }

    }

}
