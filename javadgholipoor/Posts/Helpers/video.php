<?php

function secondToTime($seconds)
{
    $hours  = floor($seconds / 3600);
    $mins   = floor($seconds / 60 % 60);
    $secs   = floor($seconds % 60);
    $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    return $timeFormat;
}
