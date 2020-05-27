<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');
$id = $_GET['editSMT'];
$q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT quantity FROM {*products*} WHERE id='$id'"));

$row = mysqli_fetch_assoc($q);
$quantity = $row['quantity'];
if ($_GET['ammount'] == "plus1"){
    $quantity += 1;
} elseif ($_GET['ammount'] == "plus5"){
    $quantity += 5;
} elseif ($_GET['ammount'] == "plus3"){
    $quantity += 3;
} elseif ($_GET['ammount'] == "plus10"){
    $quantity += 10;
} elseif ($_GET['ammount'] == "minus1"){
    $quantity -= 1;
}
$GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*products*} SET quantity='$quantity' WHERE id='$id'"));

header("Location: /cp/WMS/view/?view=$id");
