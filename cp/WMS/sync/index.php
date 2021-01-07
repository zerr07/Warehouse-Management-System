<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/prestashop/Products.php';

//$smarty->assign("products", get_products($_COOKIE['id_shard']));
if (isset($_GET['getProducts'])){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id, tag, (SELECT export FROM {*product_platforms*} WHERE id_item={*products*}.id AND id_platform=2) as active
    FROM {*products*} WHERE (SELECT COUNT(*) FROM {*product_platforms*} WHERE id_item={*products*}.id AND id_platform=2 and export=1)!=0"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['tag']] = array("active"=>$row['active'], "quantity" => get_quantity_sum($row['id']));
    }
    echo json_encode(array_filter($arr));
    exit();
} elseif (isset($_GET['getProductsPR'])){
    $arr = PR_GET_ProductsSyncData();
    $new_arr = array();
    foreach ($arr['products'] as $value){
        $new_arr[$value['reference']] = $value;
    }
    echo json_encode($new_arr);
    exit();
} elseif (isset($_GET['POSTPROD'])){
    $tag = $_GET['POSTPROD'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM {*products*} WHERE tag='$tag' LIMIT 1"));
    $id = $q->fetch_assoc()['id'];
    PR_POST_Product($id);
    exit("POSTPROD " . $id);
} elseif (isset($_GET['PUTPROD'])){
    $tag = $_GET['PUTPROD'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM {*products*} WHERE tag='$tag' LIMIT 1"));
    $id = $q->fetch_assoc()['id'];
    PR_PUT_Product_Without_IMG($id);
    exit("PUTPROD " . $id);
} elseif (isset($_GET['DELETEPROD'])){
    PR_DELETE_Product_By_Tag($_GET['DELETEPROD']);
    exit("DELETEPROD " . $_GET['DELETEPROD']);
} else {
    //$smarty->assign("products", $arr);

    //$smarty->assign("products_pr", PR_GET_ProductsSyncData());

    $smarty->display('cp/WMS/sync.tpl');
}
