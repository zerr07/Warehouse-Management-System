<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/update.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_location_types.php');

include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/session.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';

if (isset($_GET['addID']) && $_GET['addID'] != ""){
    $addID = $_GET['addID'];
}
if (isset($_POST['searchTagIDPOS']) && $_POST['searchTagIDPOS'] != ""){
    $id = $_POST['searchTagIDPOS'];
}

if (isset($_POST['searchNamePOS']) && $_POST['searchNamePOS'] != ""){
    $name = $_POST['searchNamePOS'];
}
echo $id;

if (isset($id) || isset($name) || isset($addID)) {
    if (isset($addID)){
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*} 
                                                                                            WHERE id='$addID'"));
    } elseif (isset($id)) {
        if (is_numeric($id)){
            $tempTag = "AZ".$id;
        } else {
            $tempTag = $id;
        }
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE tag='$tempTag'"));
        if(mysqli_num_rows($q) == 0){
            $q= $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*products*} WHERE id in (SELECT {*id_product*} FROM product_codes WHERE ean='$id')"));
        }

    } elseif (isset($name)) {
        $search = "WHERE id IN (SELECT id_product FROM {*product_name*} WHERE id_lang='3'";
        $searchString = htmlentities($name, ENT_QUOTES, "UTF-8");
        $searchString = explode(" ", $searchString);
        foreach ($searchString as $str){
            $search .= " AND `name` LIKE '%".$str."%'";
        }
        $search.=")";
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*products*} $search"));

    }
    $items = array(array());
    while ($row = mysqli_fetch_assoc($q)) {
        if (mysqli_num_rows($q) != 1) {
            $items[$row['id']] = $row;
            $items[$row['id']]['name'] = get_name($row['id'])['et'];
            $items[$row['id']]['mainImage'] = get_main_image($row['id']);

        } else {

            $dbID = $row['id'];
            if (isset($_SESSION['cart'][$row['id']])){
                $_SESSION['cart'][$row['id']]['quantity']++;
            } else {
                $itemID =  $row['id'];
                $_SESSION['cart'][$row['id']]['IMG'] = get_main_image($itemID);
                $_SESSION['cart'][$row['id']]['id'] = $row['id'];
                $_SESSION['cart'][$row['id']]['tag'] = $row['tag'];
                $_SESSION['cart'][$row['id']]['date_added'] = date("Y-m-d H:i:s");
                $_SESSION['cart'][$row['id']]['quantity'] = 1;
                $_SESSION['cart'][$row['id']]['Available'] = get_quantity_sum($row['id']);
                $_SESSION['cart'][$row['id']]['name'] = get_name($row['id'])['et'];


                $_SESSION['cart'][$row['id']]['loc'] = get_locations($row['id']);
                $_SESSION['cart'][$row['id']]['loc']['selected'] =
                    get_single_location_with_type($_COOKIE['default_location_type'], $_SESSION['cart'][$row['id']]['loc']['locationList']);
                $query = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM 
                                                {*product_platforms*} WHERE id_item='$dbID' AND id_platform='1'"));
                while ($rowQ = mysqli_fetch_assoc($query)) {
                    $_SESSION['cart'][$row['id']]['price'] = number_format($rowQ['price'], 2, ".", "");
                    if (is_numeric($rowQ['price'])){
                        $_SESSION['cart'][$row['id']]['basePrice'] = number_format($rowQ['price'], 2, ".", "");
                    } else {
                        $_SESSION['cart'][$row['id']]['basePrice'] = number_format(0, 2, ".", "");
                    }
                }
                if (mysqli_num_rows($query) != 1){
                    $_SESSION['cart'][$row['id']]['basePrice'] = number_format(0, 2, ".", "");
                }
            }
            updateCart();
        }
    }
    if (count($items) != 1){
        $items = array_filter($items);
        $smarty->assign("items", $items);
        $smarty->display('cp/POS/search.tpl');
    } else {
        calcCart();
        header('Location: /cp/POS' );
    }
}
if (isset($_GET['clear'])){
    unset($_SESSION['cart']);
    updateCart();
    header('Location: /cp/POS' );
}
