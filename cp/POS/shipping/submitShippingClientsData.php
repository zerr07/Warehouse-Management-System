<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');


if (isset($_GET['cash']) &&
    isset($_GET['card']) &&
    isset($_GET['ostja']) &&
    isset($_GET['tellimuseNr']) &&
    isset($_GET['mode']) &&
    isset($_GET['id_cart']) &&
    isset($_GET['shipmentID'])){
    $data = array(
        "data"=>array( "cash" => $_GET['cash'],
            "card" => $_GET['card'],
            "ostja" => $_GET['ostja'],
            "tellimuseNr" => $_GET['tellimuseNr'],
            "mode" => $_GET['mode'],
            "id_cart" => $_GET['id_cart'],
            "shipmentID" => $_GET['shipmentID'])
        );
    $id = $_GET['shipmentID'];
    $data = rawurlencode(json_encode($data));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*shipment_client_data*} WHERE id_shipment='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*shipment_client_data*} (id_shipment, data) VALUES ('$id', '$data')"));
}

function getShipmentClientData($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*shipment_client_data*} WHERE id_shipment='$id' LIMIT 1"));
    if ($q->num_rows == 0){
        return "{}";
    } else {
        return rawurldecode($q->fetch_assoc()['data']);
    }
}

if (isset($_GET['getFromID'])){
    echo getShipmentClientData($_GET['getFromID']);
}