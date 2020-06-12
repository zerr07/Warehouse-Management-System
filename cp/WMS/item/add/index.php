<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/categories/get_categories.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_carriers.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/products/get_platforms.php');

$smarty->assign('cat_tree', array_filter(get_tree()));
$smarty->assign('platforms', get_platforms());
$smarty->assign('carriers', get_carrier_default());
$tag_prefix = $smarty->getTemplateVars('shard_prefix');
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT tag FROM {*products*} WHERE tag LIKE '$tag_prefix%' ORDER BY id DESC LIMIT 1"));
if ($q->num_rows > 0){
    $tag = explode("AZ", $q->fetch_assoc()['tag']);
    $tag = $tag[1]+1;
} else {
    $tag = 1;
}

while(strlen($tag) < 3){
    $tag = "0".$tag;
}
$smarty->assign('inputTag', $tag_prefix.$tag);

$smarty->display('cp/WMS/item/add/index.tpl');

?>
