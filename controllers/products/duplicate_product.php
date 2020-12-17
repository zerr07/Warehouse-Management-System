<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Products.php');

function get_extension($file) {
    $array = explode(".", $file);
    $extension = end($array);
    return $extension ? $extension : false;
}
if (isset($_GET['id'])){
    $id = $_GET['id']; //id of product to duplicate
    if (isset($_COOKIE['id_shard'])){
        $shard = $_COOKIE['id_shard'];
    } else {
        $shard = _ENGINE['id_shard'];
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */"SELECT tag_prefix FROM {*shards*} WHERE id='$shard'"));
    $prefix = $q->fetch_assoc()['tag_prefix'];
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT tag FROM {*products*} WHERE tag LIKE '$prefix%' ORDER BY id DESC LIMIT 1"));
    if ($q->num_rows > 0){
        $tag = explode($prefix, $q->fetch_assoc()['tag']);
        $tag = $tag[1]+1;
    } else {
        $tag = 1;
    }

    while(strlen($tag) < 3){
        $tag = "0".$tag;
    }
    $tag = "$prefix".$tag;
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*products*} (`name`, id_category, actPrice, 
        tag, quantity, override, def_margin_percent, def_margin_number, ean, id_shard)
        SELECT `name`, id_category, actPrice, '$tag', quantity, override, def_margin_percent, def_margin_number,
        ean, id_shard FROM {*products*} WHERE id=$id;"));
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*products*}"));
    $last = $q->fetch_assoc()['id'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_codes*} (id_product, ean) 
        SELECT '$last', ean FROM {*product_codes*} WHERE id_product=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_images*} (id_item, image, `primary`) 
        SELECT '$last', image, `primary` FROM {*product_images*} where id_item=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_images_live*} (id_item, image, `primary`) 
        SELECT '$last', image, `primary` FROM {*product_images_live*} where id_item=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_name*} (id_product, id_lang, `name`) 
        SELECT '$last', id_lang, `name` FROM  {*product_name*} WHERE id_product=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_platforms*} (id_item, id_platform, URL, price, `custom`, `export`) 
        SELECT '$last', id_platform, URL, price, `custom`, '0' FROM {*product_platforms*} WHERE id_item=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*supplier_data*} (id_item, URL, price, priceVAT, supplierName) 
        SELECT '$last', URL, price, priceVAT, supplierName FROM {*supplier_data*} WHERE id_item=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_details*} (id_carrier, id_product, enabled) 
        SELECT id_carrier ,'$last', enabled FROM {*carrier_details*} WHERE id_product=$id"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*carrier_custom*} (id_carrier, id_product, price) 
        SELECT id_carrier ,'$last', price FROM {*carrier_custom*} WHERE id_product=$id"));
    $file = $_SERVER['DOCUMENT_ROOT']."/translations/products/$id.json";
    $newfile = $_SERVER['DOCUMENT_ROOT']."/translations/products/$last.json";
    if (!copy($file, $newfile)) {
        echo "Error copying translations file";
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image FROM {*product_images*} WHERE id_item='$last'"));
    while ($row = $q->fetch_assoc()){
        $oldfilename = $row['image'];
        $file = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$oldfilename;
        $newfilename = $last . rand(1, 100000000000000) . "." .get_extension($row['image']);
        $newfile = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$newfilename;
        if (!copy($file, $newfile)) {
            echo "Error copying file";
        } else {
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_images*} SET image='$newfilename' WHERE image='$oldfilename' AND id_item='$last'"));
        }
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id, image FROM {*product_images_live*} WHERE id_item='$last'"));
    while ($row = $q->fetch_assoc()){
        $oldfilename = $row['image'];
        $file = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$oldfilename;
        $newfilename = $last . rand(1, 100000000000000) . "_live." .get_extension($row['image']);
        $newfile = $_SERVER['DOCUMENT_ROOT']."/uploads/images/products/".$newfilename;
        if (!copy($file, $newfile)) {
            echo "Error copying file";
        } else {
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_images_live*} SET image='$newfilename' WHERE image='$oldfilename' AND id_item='$last'"));
        }
    }
    PR_POST_Product($last);
}