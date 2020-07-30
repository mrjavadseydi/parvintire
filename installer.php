<?php
$url = "https://webjungle.ir/download/larabase?accessKey=ME2uKwspwPNC32lLxIohu4ljqPjXrpdnF7BlO4jNDZUBbgCsE1FkOZczK4LDGzycsFsMmrl94LN2tS3p7WXS9AbjfQDOWgwiq3mSMJWSc6qh2EXdpZ8ATEJldyOSMrsMIS0XDIT2gmF1Ex9tQm7qHJyxi8goZdiwBis9dIm14H9EjUSQcV1PfWlgJgs1URrY9FxvhqGZWs5M887rgUOgt6CbOTtIAGs1DW8cOq5U4v02ekkr23HRBABbtAtVhmJsY9mFMoooH48ra34Smrhbj1qbSYUyeHxBahHOp6JN8iXn";
$file = "larabase.zip";
$zip = fopen($file, "w");

$cUrl = curl_init();
curl_setopt($cUrl, CURLOPT_URL, $url);
curl_setopt($cUrl, CURLOPT_FAILONERROR, true);
curl_setopt($cUrl, CURLOPT_HEADER, 0);
curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($cUrl, CURLOPT_AUTOREFERER, true);
curl_setopt($cUrl, CURLOPT_BINARYTRANSFER,true);
curl_setopt($cUrl, CURLOPT_TIMEOUT, 10);
curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($cUrl, CURLOPT_FILE, $zip);

if (!curl_exec($cUrl)) {
    unlink($file);
}

curl_close($cUrl);

$zipArchive = new \ZipArchive();
if ($zipArchive->open($file)) {
    for( $i = 0; $i < $zipArchive->numFiles; $i++ ){
        $name = $zipArchive->getNameIndex( $i );
        $zipArchive->extractTo('/', $name);
    }
}

unlink('installer.php');

die('completed open site url');
