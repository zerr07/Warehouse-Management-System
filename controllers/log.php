<?php

function sys_log($var){
    $stamp = date_timestamp_get(date_create());
    /* File name that called this function */
    $fileName = pathinfo(debug_backtrace()[0]['file'])['filename'];
    file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/".$stamp."-".$fileName.".log", print_r ($var, true));
}