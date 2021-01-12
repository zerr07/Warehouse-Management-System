<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

if (!isset($_COOKIE['user_id'])){
    exit(json_encode(array("error"=>"No user id supplied. Please contact administrator.", "code"=>"101")));
} else {
    $id = $_COOKIE['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['oldPassword']) || $data['oldPassword'] == ""){
        exit(json_encode(array("error"=>"No old password supplied.", "code"=>"103")));
    } else {
        $oldPassword = $data['oldPassword'];
        $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT password FROM {*users*} AND id='$id'"));
        if ($q){
            $pass = $q->fetch_assoc()['password'];
            if (!password_verify($oldPassword, $pass)){
                exit(json_encode(array("error"=>"Invalid password.", "code"=>"109")));
            }
        }
    }
    if (!isset($data['newPassword']) || $data['newPassword'] == ""){
        exit(json_encode(array("error"=>"No new password supplied.", "code"=>"104")));
    } else {
        $newPassword = $data['newPassword'];
    }
    if (!isset($data['confNewPassword']) || $data['confNewPassword'] == ""){
        exit(json_encode(array("error"=>"No new password confirmation supplied.", "code"=>"105")));
    } else {
        $confNewPassword = $data['confNewPassword'];
        if ($confNewPassword != $newPassword){
            exit(json_encode(array("error"=>"New password doesn't match.", "code"=>"110")));
        }
    }
    if (strlen($data['newPassword']) < 8){
        exit(json_encode(array("error"=>"Password must be at least 8 characters long.", "code"=>"107")));
    }
    $password = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*users*} SET password='$password' WHERE id='$id'"));
    exit(json_encode(array("success"=>"Settings saved")));

}
