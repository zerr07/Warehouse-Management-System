<?php
/**
 * Created by PhpStorm.
 * User: AZdev
 * Date: 18.01.2019
 * Time: 13:24
 */
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/get_products.php';
$smarty->assign('platforms', get_platforms());
$smarty->assign("item", get_product($_GET['view']));
$smarty->display('cp/WMS/view/index.tpl');
$arr = get_product($_GET['view']);

$exportName = "";
$name = explode(" ", $arr['name']['et']);
foreach ($name as $word){
    if (strlen($exportName." ".$word) <= 60){
        $exportName .= " ".$word;
    } else {
        break;
    }
}
var_dump(strlen($exportName));
?>