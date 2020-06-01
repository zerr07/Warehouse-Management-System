<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');


function getShards(){
    $arr = array(array());
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*shards*}"));
    while ($row = mysqli_fetch_assoc($query)) {
        $arr[$row['id']] = $row['name'];
    }
    return array_filter($arr);
}

function getShardID($name){
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*shards*} WHERE `name`='$name'"));
    while ($row = mysqli_fetch_assoc($query)) {
        return $row['id'];
    }
    return null;
}

function getShardName($id){
    $query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*shards*} WHERE id='$id'"));
    while ($row = mysqli_fetch_assoc($query)) {
        return $row['name'];
    }
    return null;
}

function deleteShard($id){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*shards*} WHERE id='$id'"));
}

function createShard($name){
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*shards*} (`name`) VALUES ('$name')"));
    // .htaccess generator here
}

if (isset($_GET['getSingleShardAJAX'])){
    header('Content-Type: text/plain');
    echo json_encode(getShardName($_GET['getSingleShardAJAX']));
}
if (isset($_GET['createShard'])){
    header('Content-Type: text/plain');
    createShard($_GET['createShard']);
    echo "Success";
}
if (isset($_GET['deleteShard'])) {
    header('Content-Type: text/plain');
    deleteShard($_GET['deleteShard']);
    echo "Success";
}