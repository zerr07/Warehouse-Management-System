<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_POST['bulk'])) {
    $id = $_POST['catID'];
    $platformID = $_POST['platformID'];
    $platformCat = $_POST['platformCategory'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*category_platform*}
                             (id_category, id_platform, id_category_platform) VALUES ('$id', '$platformID', '$platformCat')"));
    $cats = get_categories($id);
    foreach ($cats as $cat){
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*category_platform*}
                             (id_category, id_platform, id_category_platform) VALUES ('$cat', '$platformID', '$platformCat')"));
    }
    // TODO Make it link not only level 1 children
} else {
    $id = $_POST['catID'];
    $platformID = $_POST['platformID'];
    $platformCat = $_POST['platformCategory'];
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "INSERT INTO {*category_platform*}
                                        (id_category, id_platform, id_category_platform) VALUES ('$id', '$platformID', '$platformCat')"));
    echo "asd";
}

function get_categories($id){
    $arr = array();
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*categories*} WHERE `parent`='$id'"));
    if ($q->num_rows > 0 ){
        while ($row = $q->fetch_assoc()){
            $arr[$row['id']] = $row['id'];
            $arr = array_merge_recursive($arr, get_categories($row['id']));
        }

    }
    return $arr;
}