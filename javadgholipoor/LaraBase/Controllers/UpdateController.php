<?php

namespace LaraBase\LaraBase\Controllers;

use LaraBase\CoreController;
use LaraBase\Options\Models\Option;
use Mockery\Exception;

class UpdateController extends CoreController {

    public function update() {

        if (isDev()) {

            $result = checkForUpdates();

            if ($result['status'] == 'success') {

                $this->makeDirectories();

                $update = false;

                if (isset($result['project'])) {
                    $project = $result['project']['name'];
                    if ($result['project']['version'] != getProjectVersion("{$project}AppVersion")) {
                        $update = $this->downloadProject($result);
                    }
                }

                if ($result['version'] != getVersion()) {
                    $update = $this->downloadLaraBase($result);
                }

                if ($update) {
                    return view('larabase.instalUpdate');
                } else {
                    die('up to date');
                }

            } else {

                die('error');

            }

        }

        return redirect('admin');

    }

    public function install() {

        if (isDev()) {

            $appEmail = auth()->user()->email;
            $appName  = env('APP_NAME');
            $appKey   = env('APP_KEY');
            $appUrl   = mainUrl(url(''));
            $headers  = [
                "Authorization: Basic " . base64_encode("{$appEmail}:{$appKey}"),
                "APP_NAME: {$appName}",
                "APP_URL: {$appUrl}",
            ];

            $result = checkForUpdates();

            if ($result['status'] == 'success') {

                $this->makeDirectories();

                $basePath = base_path('');

                if ($result['version'] != getVersion()) {

                    $envChanged = false;
                    $this->downloadLaraBase($result);
                    $larabaseZipArchive = new \ZipArchive();
                    $changes = json_decode(json_encode($result['changes']), true);
                    $LaraBaseZip = base_path('downloads/apps/' . $result['versionName']);
                    if ($larabaseZipArchive->open($LaraBaseZip)) {
                        for( $i = 0; $i < $larabaseZipArchive->numFiles; $i++ ){
                            $name = $larabaseZipArchive->getNameIndex( $i );

                            $extract = true;

                            if ($name == '.env') {

                                if ($changes[$name]) {
                                    $envChanged = true;
                                }

                                if (file_exists(base_path('.env'))) {
                                    $extract = false;
                                }

                            }

                            if ($name == '.htaccess') {

                                if (file_exists(public_path('.htaccess'))) {
                                    $extract = false;
                                }

                            }

                            if ($extract) {
                                if (strpos($name, '/') === false) { // files
                                    if ($changes[$name]) {
                                        $larabaseZipArchive->extractTo($basePath, $name);
                                    }
                                } else  { // directory files
                                    $dir = explode('/', $name)[0];
                                    if ($changes[$dir]) {
                                        $larabaseZipArchive->extractTo($basePath, $name);
                                    }
                                }
                            }

                        }
                    }
                    if ($envChanged) {
                        echo 'env Changed <br>';
                    }
                    $larabaseZipArchive->close();

                    if (file_exists($LaraBaseZip)) {
                        unlink($LaraBaseZip);
                    }

                    $key = "appVersion";
                    if (Option::where('key', $key)->exists()) {
                        Option::where('key', $key)->update(['value' => $result['version']]);
                    } else {
                        Option::create(['key' => $key, 'value' => $result['version'], 'more' => 'autoload']);
                    }

                }

                if (isset($result['project'])) {
                    $project = $result['project']['name'];
                    if ($result['project']['version'] != getProjectVersion("{$project}AppVersion")) {

                        $projectZipArchive = new \ZipArchive();
                        $this->downloadProject($result, $headers, $project);
                        $projectZip = base_path('downloads/apps/' . $result['project']['versionName']);

                        if ($projectZipArchive->open($projectZip)) {
                            $projectZipArchive->extractTo($basePath);
                        }

                        $projectZipArchive->close();

                        if (file_exists($projectZip)) {
                            unlink($projectZip);
                        }

                        $key = "{$project}AppVersion";
                        if (Option::where('key', $key)->exists()) {
                            Option::where('key', $key)->update(['value' => $result['project']['version']]);
                        } else {
                            Option::create(['key' => $key, 'value' => $result['project']['version'], 'more' => 'autoload']);
                        }

                    }
                }

                return redirect(route('larabase.migrate'));

            } else {
                die('larabase server error');
            }

        }

        return redirect('admin');

    }

    public function makeDirectories() {
        foreach ([
            base_path('downloads'),
            base_path('downloads/apps')
        ] as $path) {
            if (!is_dir($path)) {
                mkdir($path);
            }
        }
    }

    public function downloadLaraBase($result) {

        $file = $result['versionName'];
        $path = base_path('downloads/apps');
        $url  = $result['versionUrl'];

        $appKey   = getAppKey();
        $appUrl   = mainUrl(url(''));
        downloadFile($url, "{$path}/{$file}", [
            "Authorization: Basic " . base64_encode("{$appUrl}:{$appKey}")
        ]);

    }

    public function downloadProject($result) {

        $url = $result['project']['versionUrl'];
        $file = $result['project']['versionName'];
        $path = base_path("downloads/apps");

        $appKey   = getAppKey();
        $appUrl   = mainUrl(url(''));

        downloadFile($url, "{$path}/{$file}", [
            "Authorization: Basic " . base64_encode("{$appUrl}:{$appKey}")
        ]);

    }

}
