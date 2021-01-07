<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/categories/get_categories.php');
$dir    = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/products';
$files1 = scandir($dir);
foreach ($files1 as $val){
    $source_img = $dir."/".$val;
    $destination_img = $dir."/".$val;
    if (filesize ( $dir."/".$val )/1000 > 2500){
        echo '<pre>'; print_r($val." : ".filesize ( $dir."/".$val )/1000); echo '</pre>';
        compress($source_img, $destination_img, 35);
        echo '<pre>'; print_r($val." : ".filesize ( $dir."/".$val )/1000); echo '</pre>';

    }

}
function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

