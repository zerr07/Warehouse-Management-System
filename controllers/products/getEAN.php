<?php
header('Content-Type: text/plain');
include_once($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$arr = array();
$index = $_POST['index'];
$query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*product_codes*} WHERE id_product='$index'"));
while($row = mysqli_fetch_assoc($query)){
    $arr[$row['id']] = $row;
}
echo json_encode($arr);