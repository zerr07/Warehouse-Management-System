<?php
include($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/updateQuantity.php');

function restore($arr){
   foreach ($arr as $item){
       $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*sold_items*} 
                                                                                        WHERE id='$item'"));
        while ($row = mysqli_fetch_assoc($q)){
            $id = $row['id_item'];
            $quantity = $row['quantity'];
            if ($row['statusSet'] != "Tagastus") {
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*sold_items*} 
                                                                    SET statusSet='Tagastus' WHERE id='$item'"));
                if (is_numeric($id)){
                    update_quantity($id, $row['id_location'], "+", $quantity);
                }
            }
        }
    }

}