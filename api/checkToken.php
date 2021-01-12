<?php
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

function checkToken(){
    if($_SERVER["HTTPS"] != "on"){
        return json_encode(array("error"=>"You should use HTTPS in your requests.", "code"=>"104"));
    }
    $headers_copy = array_change_key_case(getallheaders(), CASE_LOWER);
    $GET_Copy = array_change_key_case($_GET, CASE_LOWER);
    if (isset($headers_copy['token'])){
        $token = $headers_copy['token'];
    } elseif (isset($GET_Copy['token'])) {
        $token = $GET_Copy['token'];
    } else {
        return json_encode(array("error"=>"Access token not supplied.", "code"=>"101"));
    }
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT id FROM {*users*} WHERE access_token='$token'"));
    if ($q){
        if ($q->num_rows != 1){
            return json_encode(array("error"=>"Either no user or multiple users with supplied access token.", "code"=>"102"));
        } else {
            return json_encode(array("user_id"=>$q->fetch_assoc()['id']));
        }
    } else {
        return json_encode(array("error"=>"SQL error, please contact administrator or check your request parameters.", "code"=>"103"));
    }
}