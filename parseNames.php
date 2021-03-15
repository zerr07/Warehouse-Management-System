<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once $_SERVER['DOCUMENT_ROOT'].'/configs/config.php';

$q = $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "SELECT id_image FROM ps_image"));
//while ($row = $q->fetch_assoc()){
//    $arr = str_split($row['id_image']);
//    $str = implode("/", $arr);
//    $dir = dirname($_SERVER["DOCUMENT_ROOT"]).'/bigshop.ee/img/p/'.$str;
//    if (is_dir($dir)){
//        echo $dir." Exists<br>";
//    } else {
//        echo "HUJ";
//    }
//}

function getFileCount($path) {
    $size = 0;
    $ignore = array('.','..');
    $files = scandir($path);
    $dir = 0;
    $file = 0;
    $removed = 0;
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        if (is_dir(rtrim($path, '/') . '/' . $t)) {
            $dir++;
        }
        if (is_file(rtrim($path, '/') . '/' . $t)) {
            $file++;
        }
    }
    if ($file == 1){
        foreach($files as $t) {
            if (is_file(rtrim($path, '/') . '/' . $t)) {
                if ($t == "fileType"){
                    $removed++;
                    unlink(rtrim($path, '/') . '/' . $t);
                }
            }
        }
    }
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        if (is_dir(rtrim($path, '/') . '/' . $t)) {
            $size += getFileCount(rtrim($path, '/') . '/' . $t);
        } else {
            $size++;
        }
    }
    echo '<pre>'; print_r ("Removed: ".$removed); echo '</pre>';

    return $size;
}
echo '<pre>'; print_r (getFileCount(dirname($_SERVER["DOCUMENT_ROOT"]).'/bigshop.ee/img/p')); echo '</pre>';
