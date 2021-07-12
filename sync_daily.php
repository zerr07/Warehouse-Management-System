<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once $_SERVER['DOCUMENT_ROOT'].'/configs/config.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/products/get_products.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/prestashop/Products.php';

if (isset($_GET['products'])){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM products 
        WHERE id IN (SELECT id_item FROM product_platforms WHERE id_item=products.id AND id_platform=2 AND export=1)"));
    $arr = array();
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row['tag'];
    }
    exit(json_encode($arr));
}
if (isset($_GET['tag']) && isset($_GET['id'])){
    $tag = $_GET['tag'];
    $pr_q = $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "SELECT id_product, (SELECT quantity FROM ps_stock_available WHERE ps_product.id_product=ps_stock_available.id_product) as qty FROM ps_product WHERE reference='$tag'"));
    $qt = get_quantity_sum($_GET['id']);
    while ($row_q = $pr_q->fetch_assoc()){
        if ($qt != $row_q['qty']){
            PR_PUT_Product($_GET['id']);
        }
    }
    exit(json_encode(array("success"=>"")));
}