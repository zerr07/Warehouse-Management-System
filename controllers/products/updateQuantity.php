<?php
include($_SERVER["DOCUMENT_ROOT"] . '/configs/config.php');

if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}

function update_quantity($index, $id_loc, $mode, $quantity){ //mode can me either "-" or "+"
    $locations = get_locations($index);
    if(!is_numeric($id_loc) || $id_loc==0){
        $id_loc = get_single_location_with_type($_COOKIE['default_location_type'], $locations['locationList']);
    }
    if ($mode == "+"){
        $new_quantity = $locations['locationList'][$id_loc]['quantity']+$quantity;
    } else {
        $new_quantity = $locations['locationList'][$id_loc]['quantity']-$quantity;

    }

    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "UPDATE {*product_locations*}
        SET quantity='$new_quantity' WHERE id='$id_loc'"));
}