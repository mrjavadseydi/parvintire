<?php

use \LaraBase\Uploader\Uploader;

function uploader() {
    $uploader = new Uploader();
    return $uploader->manager();
}

function uploaderOptions() {
    $get = \LaraBase\Options\Models\Option::where('key', 'uploaderOptions')->first();
    if ($get != null)
        return json_decode($get->value, true);
}

function uploaderHash($fullUrl) {
    $parse = parse_url($fullUrl);
    $url = $parse['host'];
    if (isset($parse['path']))
        $url .= $parse['path'];
    if (isset($parse['query'])) {
        $parts = explode('&', $parse['query']);
        sort($parts);
        $url .= implode($parts);
    }
    return md5($url . ip());
}

function uploaderTheme() {
    return getTheme('uploader');
}

function uploaderLoading() {
    return image('loading.gif', 'uploader');
}

function uploaderIcon($extension) {
    $icons = config('uploader.icons');
    $name = "{$extension}.png";
    if (isset($icons[$extension]))
        $name = $icons[$extension];

    return $name;
}

function uploaderSelectIcon() {
    return image('selected.gif', 'uploader');
}

function uploaderDeleteIcon() {
    return image('delete.png', 'uploader');
}

function uploaderGenerateNameBySize($nameOrPath, $width, $height) {
    $parts = explode('/', $nameOrPath);
    $index = count($parts) - 1;
    $parts[$index] = "{$width}x{$height}-{$parts[$index]}";
    return implode('/', $parts);
}

function getDownloadServerUrl() {
    return getOption('downloadServerUrl');
}

function getDlToken() {
    $cacheKey = 'user' . auth()->id() . 'DlToken';

    if (!hasCache($cacheKey))
        setCache($cacheKey, generateUniqueToken(), 1440);

    return getCache($cacheKey);
}

function ftpCreateToken($token, $data) {
    $tokenPath = base_path('uploads/tokens');
    if (!is_dir($tokenPath)) {
        mkdir($tokenPath);
    }
    $json = base_path("uploads/tokens/{$token}.json");
    \File::put($json, json_encode($data));
    ftpUpload($json, "tokens/{$token}.json");
    return getOption('dlUrl') . '/api/v1/' . $token;
}

function ftpUpload($source, $destination, $ftpHost = null, $ftpUser = null, $ftpPass = null) {

    $output = [
        'status' => 'error',
        'message' => 'ftp login failed'
    ];

    if ($ftpHost == null)
        $ftpHost = '192.168.1.194';

    if ($ftpUser == null)
        $ftpUser = 'javaddev';

    if ($ftpPass == null)
        $ftpPass = 'mylove';

    $ftpConnect = ftp_connect($ftpHost);
    $ftpResult = ftp_login($ftpConnect, $ftpUser, $ftpPass);

    if ($ftpResult) {
        $dir = [];
        $dirs = explode('/', $destination);
        for ($i = 0; $i < count($dirs) - 1; $i++) {
            $dir[] = $dirs[$i];
            $implodeDir = implode('/', $dir);
            if (!ftpIsDir($ftpConnect, $implodeDir)) {
                ftp_mkdir($ftpConnect, $implodeDir);
            }
        }
        if (ftp_put($ftpConnect, $destination, $source, FTP_BINARY)) {
            $output['status'] = 'success';
            $output['message'] = 'ftp upload successfully';
        } else {
            $output['message'] = 'ftp upload error';
        }
    }

    ftp_close($ftpConnect);
    return $output;

}

function ftpIsDir($ftp, $dir)
{
    $pwd = ftp_pwd($ftp);
    if ($pwd !== false && @ftp_chdir($ftp, $dir)) {
        ftp_chdir($ftp, $pwd);
        return true;
    }
    return false;
}

function getUserDownloadServerToken($userId = null) {
    if (empty($userId))
        $userId = auth()->id();

    $cacheKey = "user{$userId}DownloadServerToken";
    if (!hasCache($cacheKey)) {
        $key  = 'downloadServerToken';
        $token = $userId . '_' . generateUniqueToken();
        $get = \LaraBase\Auth\Models\UserMeta::where(['key' => $key, 'user_id' => $userId])->first();
        if ($get == null) {
            \LaraBase\Auth\Models\UserMeta::create([
                'key' => 'downloadServerToken',
                'user_id' => $userId,
                'value' => $userId . '_' . generateUniqueToken()
            ]);
        }
        setCache($cacheKey, $token);
    }
    return getCache($cacheKey);
}
