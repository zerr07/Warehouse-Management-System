<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function getShippingStatus($id, $type){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status_types*} WHERE id=(SELECT id_status FROM {*shipment_status*} WHERE id_shipment='$id');"));
    if ($q->num_rows == 0){
        if ($type == "JSON"){
            return json_encode(array("status"=>"Carrier not selected"));
        } elseif ($type == "STANDART") {
            return "Carrier not selected";
        }
    } else {
        $arr = $q->fetch_assoc();
        if ($type == "JSON"){
            return json_encode(array("name"=>$arr['name'], "id"=>$arr['id']));
        } elseif ($type == "STANDART") {
            return $arr['name'];
        }
    }
    if ($type == "JSON"){
        return json_encode(array("status"=>"empty"));
    } else {
        return "empty";
    }
}

function getShippingStatuses(){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_status_types*}"));
    if ($q->num_rows == 0){
        return json_encode(array("status"=>"empty"));
    } else {
        $arr = array();
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = array("name" => $row['name'], "id" => $row['id']);
        }
        return json_encode($arr);
    }
}

function getShippingType($id, $type){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT * FROM {*shipment_types*} WHERE id=(SELECT id_type FROM {*shipment_data*} WHERE id_shipment='$id')"));
    if ($q->num_rows == 0){
        if ($type == "JSON"){
            return json_encode(array("status"=>"empty"));
        } elseif ($type == "STANDART") {
            return "empty";
        }
    } else {
        $arr = $q->fetch_assoc();
        if ($type == "JSON"){
            return json_encode(array("name"=>$arr['name'], "id"=>$arr['id']));
        } elseif ($type == "STANDART") {
            return $arr['name'];
        }
    }
    if ($type == "JSON"){
        return json_encode(array("status"=>"empty"));
    } else {
        return "empty";
    }
}

function getShippingData($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"SELECT *
    FROM {*shipment_data*}
    WHERE id_shipment='$id'"));
    if ($q->num_rows == 0){
        return null;
    } else {
        $data = json_decode(rawurldecode($q->fetch_assoc()['data']), true);
        return json_encode(array("data"=>$data));
    }
}

if (isset($_GET['idJSON'])){
    echo getShippingStatus($_GET['idJSON'], "JSON");
}
if (isset($_GET['id'])){
    echo getShippingStatus($_GET['id'], "STANDART");
}
if (isset($_GET['type_idJSON'])){
    echo getShippingType($_GET['type_idJSON'], "JSON");
}
if (isset($_GET['type_id'])){
    echo getShippingType($_GET['type_id'], "STANDART");
}
if (isset($_GET['data_id'])){
    echo getShippingData($_GET['data_id']);
}