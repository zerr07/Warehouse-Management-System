<?php
header('Content-Type: text/plain');
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$arr = array();
$query = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*platforms_import*}"));
while($row = mysqli_fetch_assoc($query)){
    $arr[$row['id']] = $row;
}
echo json_encode($arr);