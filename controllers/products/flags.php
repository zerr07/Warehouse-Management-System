<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
function addFlag($id_product, $flag){
    removeFlag($id_product, $flag);
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*product_flags*} (id_product, flag) VALUES ('$id_product', '$flag')"));
}

function removeFlag($id_product, $flag){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_flags*} WHERE flag='$flag' AND id_product='$id_product'"));
}

function getFlag($id_product, $flag): bool {
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_flags*} WHERE flag='$flag' AND id_product='$id_product'"));
    if ($q->num_rows == 0){
        return false;
    } else {
        return true;
    }
}

if (isset($_GET['insert']) && isset($_GET['id'])){
    addFlag($_GET['id'], $_GET['insert']);
    exit(json_encode(array("success"=>"")));
}