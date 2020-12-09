<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

function addList($id, $id_platform){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*XML_lists*} (id_platform, id_product) VALUES ('$id_platform', '$id')"));
}
function removeList($id, $id_platform){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*XML_lists*} WHERE id_platform='$id_platform' AND id_product='$id'"));
}
function getList($id_platform){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*XML_lists*} WHERE id_platform='$id_platform'"));
    while ($row = $q->fetch_assoc()){
        $arr[$row['id']] = $row;
    }
    return $arr;
}
if (isset($_GET['get']) && isset($_GET['id_platform'])){
    echo json_encode(getList($_GET['id_platform']));
}
if (isset($_GET['post']) && isset($_GET['id']) && isset($_GET['id_platform'])){
    addList($_GET['id'], $_GET['id_platform']);
}
if (isset($_GET['remove']) && isset($_GET['id']) && isset($_GET['id_platform'])){
    removeList($_GET['id'], $_GET['id_platform']);
}