<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (isset($_POST['delete'])){
    $id = $_POST['delete'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*products*} WHERE id='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_name*} WHERE id_product='$id'"));
} else if (isset($_POST['deleteSMTcategory'])){
    $id = $_POST['deleteSMTcategory'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*categories*} WHERE id='$id'"));
    $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*ps_category*} WHERE id_category='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} 
                                                                    SET id_category=2 WHERE id_category='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*categories*} 
                                                                    SET parent=2 WHERE parent='$id'"));
    $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*ps_category*} 
                                                                    SET id_parent=2 WHERE id_parent='$id'"));
} elseif (isset($_POST['deleteSMTitemURL'])){
    $id = $_POST['deleteSMTitemURL'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*supplier_data*} WHERE id='$id'"));
} elseif (isset($_POST['deleteSMTitemPlatform'])){
    $id = $_POST['deleteSMTitemPlatform'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_platforms*} WHERE id='$id'"));
}
header("Location: /cp/WMS/");

