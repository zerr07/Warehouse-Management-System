<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/products/updateQuantity.php');
$id = $_GET['editSMT'];

if ($_GET['amount'] == "plus1"){
    update_quantity($id, $_GET['location'], "+", 1);
} elseif ($_GET['amount'] == "plus5"){
    update_quantity($id, $_GET['location'], "+", 5);
} elseif ($_GET['amount'] == "plus3"){
    update_quantity($id, $_GET['location'], "+", 3);
} elseif ($_GET['amount'] == "plus10"){
    update_quantity($id, $_GET['location'], "+", 10);
} elseif ($_GET['amount'] == "minus1"){
    update_quantity($id, $_GET['location'], "-", 1);
}
header("Location: /cp/WMS/view/?view=$id");
