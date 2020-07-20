<?php

use Illuminate\Support\Facades\Cache;

function checkDatabaseConnection() {
    if (Cache::has(env('APP_NAME') . 'dbConnection'))
        return Cache::get(env('APP_NAME') . 'dbConnection');

    return false;
}

function checkUsersTable() {
    if (Cache::has(env('APP_NAME') . 'usersTable'))
        return Cache::get(env('APP_NAME') . 'usersTable');

    return false;
}

function checkVisitTable() {
    if (Cache::has(env('APP_NAME') . 'visitTable'))
        return Cache::get(env('APP_NAME') . 'visitTable');

    return false;
}

function checkPostTypesTable() {
    if (Cache::has(env('APP_NAME') . 'postTypesTable'))
        return Cache::get(env('APP_NAME') . 'postTypesTable');

    return false;
}

function getAppKey() {
    return str_replace("base64:", "", env('APP_KEY'));
}

function generateAppKey() {
    return "base64:" . base64_encode(generateUniqueToken());
}

function getRepository($path) {
    return 'https://webjungle.ir/'.$path;
}

function getVersion() {
    $appVersion = getOption('appVersion');
    if (!empty($appVersion)) {
        return $appVersion;
    }
    return 'install';
}

function getProjectVersion($project) {
    $appVersion = getOption("{$project}AppVersion");
    if (!empty($appVersion)) {
        return $appVersion;
    }
    return 'install';
}

function getEnvContent($params) {

    $url = url('');
    $urlRemoveHttps = str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url)));

    $env = [
        'APP_NAME'               => $params['appName'],
        'APP_ENV'                => 'local',
        'APP_KEY'                => $params['appKey'],
        'APP_DEBUG'              => 'false',
        'APP_URL'                => $url,
        'TIMEZONE'               => 'Asia/Tehran',
        'SERVER'                 => 'true',
        'ENV_SPACE_1'            => 'ENV_SPACE',
        'LOG_CHANNEL'            => 'stack',
        'ENV_SPACE'              => '',
        'DB_CONNECTION'          => 'mysql',
        'DB_HOST'                => '127.0.0.1',
        'DB_PORT'                => '3306',
        'DB_DATABASE'            => $params['databaseName'],
        'DB_USERNAME'            => $params['databaseUser'],
        'DB_PASSWORD'            => $params['databasePassword'],
        'ENV_SPACE_2'            => 'ENV_SPACE',
        'BROADCAST_DRIVER'       => 'log',
        'CACHE_DRIVER'           => 'file',
        'QUEUE_CONNECTION'       => 'sync',
        'SESSION_DRIVER'         => 'file',
        'SESSION_LIFETIME'       => '120',
        'ENV_SPACE_3'            => 'ENV_SPACE',
        'REDIS_HOST'             => '127.0.0.1',
        'REDIS_PASSWORD'         => 'null',
        'REDIS_PORT'             => '6379',
        'ENV_SPACE_4'            => 'ENV_SPACE',
        'MAIL_DRIVER'            => 'smtp',
        'MAIL_HOST'              => "mail.{$urlRemoveHttps}",
        'MAIL_PORT'              => '587',
        'MAIL_USERNAME'          => $params['siteEmail'],
        'MAIL_PASSWORD'          => $params['siteEmailPassword'],
        'MAIL_ENCRYPTION'        => 'null',
        'MAIL_FROM_ADDRESS'      => $params['siteEmail'],
        'MAIL_FROM_NAME'         => $params['appName'],
        'ENV_SPACE_6'            => 'ENV_SPACE',
        'AWS_ACCESS_KEY_ID'      => '',
        'AWS_SECRET_ACCESS_KEY'  => '',
        'AWS_DEFAULT_REGION'     => 'us-east-1',
        'AWS_BUCKET'             => '',
        'ENV_SPACE_7'            => 'ENV_SPACE',
        'PUSHER_APP_ID'          => '',
        'PUSHER_APP_KEY'         => '',
        'PUSHER_APP_SECRET'      => '',
        'PUSHER_APP_CLUSTER'     => 'mt1',
        'ENV_SPACE_8'            => 'ENV_SPACE',
        'MIX_PUSHER_APP_KEY'     => '"${PUSHER_APP_KEY}"',
        'MIX_PUSHER_APP_CLUSTER' => '"${PUSHER_APP_CLUSTER}"',
        'ENV_SPACE_9'            => 'ENV_SPACE',
        'NOCAPTCHA_SECRET'       => $params['googleReCaptchaV3SecretKey'] ?? '6LcvUdYUAAAAAECwQsZ9WQCSs9s-bRHbf6BpUFqB',
        'NOCAPTCHA_SITEKEY'      => $params['googleReCaptchaV3SiteKey'] ?? '6LcvUdYUAAAAAG4q6nbV6ruCOH0s9TUcixuKgFr1',
    ];

    $envContent = null;
    foreach ($env as $key => $value) {
        if ($value == 'ENV_SPACE') {
            $envContent .= "\n";
        } else {
            $envContent .= "{$key}={$value}\n";
        }
    }

    return $envContent;

}

function checkForUpdates() {
    $projectName = env('APP_NAME');
    if (hasCache($projectName . 'checkForUpdates')) {
        return json_decode(getCache($projectName . 'checkForUpdates'), true);
    }

    $cUrl = curl_init();

    curl_setopt($cUrl, CURLOPT_POST, 1);
    curl_setopt($cUrl, CURLOPT_TIMEOUT_MS , 10000);
    curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cUrl, CURLOPT_URL, getRepository('api/check-for-updates'));

    $appKey   = getAppKey();
    $appUrl   = mainUrl(url(''));
    $appVersion = getVersion();
    $projectVersion = getProjectVersion($projectName);
    curl_setopt($cUrl, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . base64_encode("{$appUrl}:{$appKey}"),
        'appVersion:' . $appVersion,
        'projectName:' . $projectName,
        'projectVersion:' . $projectVersion
    ]);

    $result = curl_exec($cUrl);
    setCache($projectName . 'checkForUpdates', $result, 5);

    if (curl_errno($cUrl)) {
        return [
            'status' => 'error'
        ];
    }

    curl_close($cUrl);
    return json_decode($result, true);
}

function picsum($width, $height) {
    return "https://picsum.photos/{$width}/{$height}?v=" . rand(0, 999);
}
