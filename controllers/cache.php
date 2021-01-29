<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/shards.php';


function cacheProductNames(): bool {
    $shards = getShards();
    foreach ($shards as $key => $value){
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/products_'.$key.'.json', json_encode(get_products_dataList($key), JSON_UNESCAPED_UNICODE));
    }
    return true;
}


function getProductCacheStr($shard){
    return file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cache/products_'.$shard.'.json');
}

function getProductCache($shard){
    return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cache/products_'.$shard.'.json'), true);
}

function findProducts($shard, $str): array {
    $prods = getProductCache($shard);
    return array_slice(array_filter($prods, function ($var) use ($str) {
        return preg_match("/$str/i", $var);
    }), 0, 5);
}
if (isset($_GET['getProductDataList'])){
    exit (json_encode(findProducts($_GET['getProductDataList'], $_GET['getDataListStr']), JSON_UNESCAPED_UNICODE));
}
if (isset($_GET['forceCache'])){
    if (cacheProductNames()){
        exit(json_encode(array("result"=>"success")));
    }
}