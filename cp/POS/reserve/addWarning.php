<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');


function addWarning($id, $comment){
    $user_id = $_COOKIE['user_id'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*reservation_warning*} (id_user, id_reservation, `comment`) VALUES ('$user_id', '$id', '$comment')"));
}
function removeWarning($id){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*reservation_warning*} WHERE id_reservation='$id'"));
}
function getWarning(){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT *, (SELECT username FROM {*users*} WHERE id={*reservation_warning*}.id_user) as `user` FROM {*reservation_warning*}"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id_reservation']] = $row;
    }
    return array_filter($arr);
}
function getSingleWarning($id){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT *, (SELECT username FROM {*users*} WHERE id={*reservation_warning*}.id_user) as `user` FROM {*reservation_warning*} WHERE id_reservation='$id'"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id_reservation']] = $row;
    }
    return array_filter($arr);
}
$post=json_decode(file_get_contents("php://input"));

if (isset($post->remove) && isset($post->id)){
    removeWarning($post->id);
}
if (isset($post->add) && isset($post->id) && isset($post->comment)){
    addWarning($post->id, $post->comment);
}
if (isset($post->get)){
    echo json_encode(getWarning());
}
if (isset($post->getSingle)){
    echo json_encode(getSingleWarning($post->getSingle));
}