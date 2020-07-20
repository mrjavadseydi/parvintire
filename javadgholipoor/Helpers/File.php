<?php
namespace LaraBase\Helpers;

class File
{
    public static function byteFormat( $bytes, $precision = 2, $lang = 'fa' )
    {
        $units = [
            'fa'	=> ['بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت'],
            'en'	=> ['B', 'KB', 'MB', 'GB', 'TB'],
        ];

        $i = 0;
        while($bytes > 1024) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, $precision) . ' ' . $units[$lang][$i];
    }

    public static function getStringExtension($string, $byDot = false) {
        $parts = explode('.', $string);

        if ($byDot)
            return '.' . $parts[count($parts) - 1];

        return $parts[count($parts) - 1];
    }

    public static function downloadFile($url, $pathFile, $headers = []) {

        if (!file_exists($pathFile)) {

            $source = fopen($pathFile, "w+");

            set_time_limit(0);
            $cUrl = curl_init();

            curl_setopt($cUrl, CURLOPT_URL, $url);
            curl_setopt($cUrl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($cUrl, CURLOPT_BINARYTRANSFER,true);
            curl_setopt($cUrl, CURLOPT_TIMEOUT, 600);
            curl_setopt($cUrl, CURLOPT_FILE, $source);

            if (!curl_exec($cUrl)) {
                unlink($pathFile);
                return false;
            }

            curl_close($cUrl);
            return true;

        }

        return true;

    }

    public static function extractZip( $file, $extractTo ){
        $zip = new \ZipArchive();
        if ($zip->open($file)) {
            for( $i = 0; $i < $zip->numFiles; $i++ ){
                $name = $zip->getNameIndex( $i );
                $zip->extractTo($extractTo, $name);
            }
        }
    }

    /**
     * For safe multipart POST request for PHP5.3 ~ PHP 5.4.
     *
     * @param resource $cUrl cURL resource
     * @param array $params "name => value"
     * @param array $files "name => path"
     * @return bool
     */
    public static function curlPostFields($cUrl, array $params = array(), array $files = array()) {

        // invalid characters for "name" and "filename"
        static $disallow = array("\0", "\"", "\r", "\n");

        // build normal parameters
        foreach ($params as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "",
                filter_var($v),
            ));
        }

        // build file parameters
        foreach ($files as $k => $v) {
//            switch (true) {
//                case false === $v = realpath(filter_var($v)):
//                case !is_file($v):
//                case !is_readable($v):
//                    continue; // or return false, throw new InvalidArgumentException
//            }
            $data = file_get_contents($v);
            $p = explode(DIRECTORY_SEPARATOR, $v);
            $v = end($p);
            $k = str_replace($disallow, "_", $k);
            $v = str_replace($disallow, "_", $v);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
                "Content-Type: application/octet-stream",
                "",
                $data,
            ));
        }

        // generate safe boundary
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));

        // add boundary for each parameters
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });

        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = "";

        // set options
        return @curl_setopt_array($cUrl, array(
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => implode("\r\n", $body),
            CURLOPT_HTTPHEADER => array(
                "Expect: 100-continue",
                "Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
            ),
        ));
    }

    public static function uploadFiles($url, $files, $params = [])
    {
        $cUrl = curl_init();
        curl_setopt($cUrl, CURLOPT_URL, $url);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
        curlPostFields($cUrl, $params, $files);
        $response = curl_exec($cUrl);
        curl_close($cUrl);
        return $response;
    }

    public static function removeDir($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::removeDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

}
