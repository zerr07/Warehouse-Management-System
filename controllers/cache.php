<?php

$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 1);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/shards.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}


function cacheProductNames(): bool {
    $shards = getShards();
    foreach ($shards as $key => $value){
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/products_'.$key.'.json', json_encode(get_products_dataList($key), JSON_UNESCAPED_UNICODE));
    }
    return true;
}
function cacheProductName($id): bool {
    $prod = get_product($id);
    if (!is_null($prod)){
        $cache = getProductCache($prod['id_shard']);
        $cache[$prod['id']] = generateDatalistName($prod['tag'], $prod['name']['et']);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/products_'.$prod['id_shard'].'.json', json_encode($cache, JSON_UNESCAPED_UNICODE));
        return true;
    } else {
        return false;
    }
}

function cacheProductNamesBackground(): bool {
    if (strtoupper(substr(php_uname(), 0, 3)) == "WIN") {
        pclose(popen("curl https://".$_SERVER['SERVER_NAME']."/controllers/cache.php?forceCache","r"));
    }
    // (B) FOR LINUX
    else {
        pclose(popen("curl https://".$_SERVER['SERVER_NAME']."/controllers/cache.php?forceCache &","r"));
    }
    return true;
}
function cacheProductNameBackground($id): bool {
    if (strtoupper(substr(php_uname(), 0, 3)) == "WIN") {
        pclose(popen("curl https://".$_SERVER['SERVER_NAME']."/controllers/cache.php?forceCacheProduct=".$id,"r"));
    }
    // (B) FOR LINUX
    else {
        pclose(popen("curl https://".$_SERVER['SERVER_NAME']."/controllers/cache.php?forceCacheProduct=".$id." &","r"));
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
    }), 0, 5, true);
}

if (isset($_GET['getProductDataList'])){
    exit (json_encode(findProducts($_GET['getProductDataList'], $_GET['getDataListStr']), JSON_UNESCAPED_UNICODE));
}
if (isset($_GET['forceCache'])){
    if (cacheProductNames()){
        exit(json_encode(array("result"=>"success")));
    }
}
if (isset($_GET['forceCacheProduct'])){
    if (cacheProductName($_GET['forceCacheProduct'])){
        exit(json_encode(array("result"=>"success")));
    }
}
