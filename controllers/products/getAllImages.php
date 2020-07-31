<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
$files = glob($_SERVER['DOCUMENT_ROOT']."/dump/temp/*");
echo '<pre>'; print_r ($files); echo '</pre>';
foreach($files as $file){
    if(is_file($file))
        unlink($file);
}
$prod = get_product($_GET['id']);
$zip = new ZipArchive;
$tmp_file = $_SERVER['DOCUMENT_ROOT']."/dump/temp/".$prod['tag'].".zip";
$zip->open($tmp_file,  ZipArchive::CREATE);
foreach ($prod['images'] as $val){
    $zip->addFile($_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$val['image'], $val['image']);
}
$zip->close();
echo 'Archive created!';
header("Content-disposition: attachment; filename=".$prod['tag'].".zip");
header('Content-type: application/zip');
readfile($tmp_file);