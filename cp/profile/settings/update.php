<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

if (!isset($_COOKIE['user_id'])){
    exit(json_encode(array("error"=>"No user id supplied. Please contact administrator.", "code"=>"101")));
} else {
    $id = $_COOKIE['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['defLoc']) || $data['defLoc'] == ""){
        exit(json_encode(array("error"=>"No default location supplied.", "code"=>"106")));
    } else {
        $defLoc = $data['defLoc'];
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*users*} SET default_location_type='$defLoc' WHERE id='$id'"));
    exit(json_encode(array("success"=>"Settings saved")));
}
