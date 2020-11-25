<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function convertToShipping($id){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*reserved*} SET id_type='2' WHERE id='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_status*} (id_status, id_shipment) VALUES ('1', '$id')"));

    return json_encode(array("response"=>"success"));
}

if (isset($_GET['id'])){
    echo convertToShipping($_GET['id']);
}