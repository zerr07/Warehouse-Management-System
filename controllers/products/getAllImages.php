<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$files = glob($_SERVER['DOCUMENT_ROOT']."/dump/temp/*");
foreach($files as $file){
    if(is_file($file))
        unlink($file);
}
$prod = get_product($_GET['id']);
$zip = new ZipArchive;
$tmp_file = $_SERVER['DOCUMENT_ROOT']."/dump/temp/".$prod['tag'].".zip";
if ($zip->open($tmp_file, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE) === TRUE) {
    foreach ($prod['images'] as $val){
        $zip->addFile($_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$val['image'], "warehouse/".$val['image']);
    }
    foreach ($prod['images_live'] as $val){
        $zip->addFile($_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$val['image'], "live/".$val['image']);
    }
    $zip->close();
    header("Content-disposition: attachment; filename=".$prod['tag'].".zip");
    header('Content-type: application/zip');
    readfile($tmp_file);
} else {
    echo 'EEEEEEee';
}
