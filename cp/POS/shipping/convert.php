<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function convertToShipping($id){

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"UPDATE {*reserved*} SET id_type='2' WHERE id='$id'"));
    if ($GLOBALS['DBCONN']->affected_rows == 0){
        exit(json_encode(array("error" => "Reservation id not found.", "code"=>"801")));
    } else {
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT id FROM {*shipment_status*} WHERE id_shipment='$id'"));
        if ($q){
            if ($q->num_rows == 0){
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_status*} (id_status, id_shipment) VALUES ('1', '$id')"));
            }
        }
    }
    exit(json_encode(array("success" => "Reservation converted to shipment.")));
}

if (isset($_GET['id'])){
    echo convertToShipping($_GET['id']);
}