<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/controllers/prestashop/Products.php');

if (isset($_GET['delete'])){
    $id = $_GET['delete'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*products*} WHERE id='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_name*} WHERE id_product='$id'"));
    PR_DELETE_Product($id);
} else if (isset($_GET['deleteSMTcategory'])){
    $id = $_GET['deleteSMTcategory'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*categories*} WHERE id='$id'"));
    $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*ps_category*} WHERE id_category='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} 
                                                                    SET id_category=2 WHERE id_category='$id'"));
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*categories*} 
                                                                    SET parent=2 WHERE parent='$id'"));
    $GLOBALS['BIGCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*ps_category*} 
                                                                    SET id_parent=2 WHERE id_parent='$id'"));
} elseif (isset($_GET['deleteSMTitemURL'])){
    $id = $_GET['deleteSMTitemURL'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*supplier_data*} WHERE id='$id'"));
} elseif (isset($_GET['deleteSMTitemPlatform'])){
    $id = $_GET['deleteSMTitemPlatform'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "DELETE FROM {*product_platforms*} WHERE id='$id'"));
}
header("Location: /cp/WMS/");

