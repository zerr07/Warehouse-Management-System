<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/configs/config.php';

function MoveProducts($from, $to){
   $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET id_category='$to' WHERE id_category='$from'"));
}

if (isset($_GET['from']) && isset($_GET['to'])){
    try {
        MoveProducts($_GET['from'], $_GET['to']);
        if ($GLOBALS['DBCONN']->error)
            throw new Exception($GLOBALS['DBCONN']->error);
        exit(json_encode(array("success"=>"")));
    } catch (Exception $e){
        exit(json_encode(array("error"=>$e->getMessage())));
    }
}