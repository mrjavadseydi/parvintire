<?php

function getClientIp()
{
    if (getenv('HTTP_CLIENT_IP'))
        $ipAddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipAddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipAddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipAddress = getenv('REMOTE_ADDR');
    else
        $ipAddress = 'UNKNOWN';

    return $ipAddress;
}

function download($data_file, $stream = false) {
    date_default_timezone_set('GMT');
    $data_size = filesize($data_file);
    $mime = 'application/otect-stream';
    $filename = basename($data_file);

    if ($stream) {
        if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
            $ranges_str = (isset($_SERVER['HTTP_RANGE']))?$_SERVER['HTTP_RANGE']:$HTTP_SERVER_VARS['HTTP_RANGE'];
            $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));
            if ((intval($ranges_arr[0]) >= intval($ranges_arr[1]) && $ranges_arr[1] != "" && $ranges_arr[0] != "" )
                || ($ranges_arr[1] == "" && $ranges_arr[0] == "")
            ) {
                $ranges_arr[0] = 0;
                $ranges_arr[1] = $data_size - 1;
            }
        } else {
            $ranges_arr[0] = 0;
            $ranges_arr[1] = $data_size - 1;
        }
    } else {
        if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
            $ranges_str = (isset($_SERVER['HTTP_RANGE']))?$_SERVER['HTTP_RANGE']:$HTTP_SERVER_VARS['HTTP_RANGE'];
            $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));
            if ((intval($ranges_arr[0]) >= intval($ranges_arr[1]) && $ranges_arr[1] != "" && $ranges_arr[0] != "" )
                || ($ranges_arr[1] == "" && $ranges_arr[0] == "")
            ) {
                $ranges_arr[0] = 0;
                $ranges_arr[1] = $data_size - 1;
            }
        } else {
            $ranges_arr[0] = 0;
            $ranges_arr[1] = $data_size - 1;
        }
    }

    $file = fopen($data_file, 'rb');

    $start = $stop = 0;
    if ($ranges_arr[0] === "") {
        $stop = $data_size - 1;
        $start = $data_size - intval($ranges_arr[1]);
    } elseif ($ranges_arr[1] === "") {
        $start = intval($ranges_arr[0]);
        $stop = $data_size - 1;
    } else {
        $stop = intval($ranges_arr[1]);
        $start = intval($ranges_arr[0]);
    }

    fseek($file, $start, SEEK_SET);
    $start = ftell($file);
    fseek($file, $stop, SEEK_SET);
    $stop = ftell($file);

    $data_len = $stop - $start;

    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
        header('HTTP/1.0 206 Partial Content');
        header('Status: 206 Partial Content');
    }

    header('Accept-Ranges: bytes');
    if ($stream) {
        header('Content-type: video/mp4');
        header('Access-Control-Allow-Origin: *');
    } else {
        header('Content-type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    header("Content-Range: bytes $start-$stop/" . $data_size );
    header("Content-Length: " . ($data_len + 1));

    fseek($file, $start, SEEK_SET);
    $bufsize = 102400;

    ignore_user_abort(true);
    @set_time_limit(0);
    while (!(connection_aborted() || connection_status() == 1) && $data_len > 0) {
        echo fread($file, $bufsize);
        $data_len -= $bufsize;
        flush();
    }

    fclose($file);

}

if(isset($_GET['token'])) {
    if ($_GET['token'] == '10322295AGr9Q4JWZZnzsBf5m4zWVq29ZCiphxq7lM10322295AGr9Q4JWZZnzsBf5m4zWVq29ZCiphxq7lM10322295AGr9Q4JWZZnzsBf5m4zWVq29ZCiphxq7lM') {
        $getPath = null;
        if(isset($_GET['path'])) {
            $getPath = $_GET['path'];
        }
        $p = '../downloads/' . $getPath;
        if (isset($_GET['size'])) {
            if (file_exists($p)) {
                die('{"status":"success","size":"'.filesize($p).'"}');
            } else {
                die('{"status":"error","message":"file not found"}');
            }
        } else {
            if (isset($_GET['stream'])) {
                download($p, true);
            } else {
                download($p);
            }
        }
    }
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://ostadbeyk.com/api/isAllow');
$_GET['ip'] = getClientIp();
curl_setopt($curl, CURLOPT_POSTFIELDS, $_GET);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
$response = json_decode(curl_exec($curl));
if ($response->status == 'success') {

    $path = '../downloads/' . $response->path;
    if (isset($_GET['stream'])) {
        download($path,true);
    } else {
        download($path);
    }

} else {
    header("Location: https://ostadbeyk.com/download/failed");
}
