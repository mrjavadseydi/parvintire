<?php
/**
 * include plugins helpers
 */
foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $directory) {
    $helpersPath = "{$directory}/Helpers";
    if ( file_exists( $helpersPath ) && is_dir( $helpersPath ) ) {
        foreach (glob("{$helpersPath}/*.php") as $file) {
            include $file;
        }
    }
}
