<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/updateQuantity.php');

function restore($arr){
   foreach ($arr as $item){
        restore_single($item);
    }
}
function restore_single($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} 
                                                                                        WHERE id='$id'"));
    while ($row = $q->fetch_assoc()){
        $id_prod = $row['id_item'];
        $quantity = $row['quantity'];
        if ($row['statusSet'] != "Tagastus") {
            echo prefixQuery(/** @lang text */ "UPDATE {*sold_items*} 
                                                                    SET statusSet='Tagastus' WHERE id='$id'");
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*sold_items*} 
                                                                    SET statusSet='Tagastus' WHERE id='$id'"));
            if (is_numeric($id_prod)){
                update_quantity($id_prod, $row['id_location'], "+", $quantity);
            }
        }
    }
}
function restore_full($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} 
                                                                                       WHERE id_sale='$id'"));
    while ($row = $q->fetch_assoc()){
        $id_prod = $row['id_item'];
        $quantity = $row['quantity'];
        if ($row['statusSet'] != "Tagastus") {
            if (is_numeric($id_prod)){
                update_quantity($id_prod, $row['id_location'], "+", $quantity);
            }
        }
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*sold_items*} 
                                                                   SET statusSet='Tagastus' WHERE id_sale='$id'"));
}