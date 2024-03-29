
<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/updateQuantity.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/saveCart.php');
include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/session.php');

function get_location_data($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT location, (SELECT `name` FROM {*location_type*} WHERE id={*product_locations*}.id_type) as `name` FROM {*product_locations*} WHERE id='$id'"));
    $row = $q->fetch_assoc();
    if (isset($row['name']) && isset($row['location'])){
        return $row['name']." | ".$row['location'];

    } else {
        return "Check location";
    }
}
function reserveCart($note, $cart){
    if (isset($_GET['type'])){
        $type = $_GET['type'];
    } else {
        $type = 1;
    }

    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*reserved*} (`comment`, `id_type`) 
                                                                                                VALUES ('$note', '$type')"));
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
    while($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        if ($type == "2" || $type == 2){
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_status*} (id_status, id_shipment) VALUES ('1', '$id')"));
        }
        addCartToReservation($id, $cart);
    }
    updateCart();
}
function addCartToReservation($id, $cart){
    foreach ($cart as $key => $value){

        $quantity = $value['quantity'];
        $price = $value['price'];
        $basePrice = $value['basePrice'];
        $id_loc = "";
        if (isset($value['loc']['selected'])){
            $id_loc = $value['loc']['selected'];
        } elseif (isset($value['id_location'])){
            $id_loc = $value['id_location'];
        }
        if($value['tag'] != "Buffertoode") {
            update_quantity($key, $id_loc, "-", $quantity);
        } else {
            $key = $value['name'];
        }

        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "INSERT INTO {*reserved_products*} (id_reserved, id_product, price, quantity, basePrice, id_location
                                        ) VALUES ('$id', '$key', '$price', '$quantity', '$basePrice', '$id_loc')"));

    }
}
function reserveCartWithoutUpdate($note, $cart){
    if (isset($_GET['type'])){
        $type = $_GET['type'];
    } else {
        $type = 1;
    }

    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*reserved*} (`comment`, `id_type`) 
                                                                                                VALUES ('$note', '$type')"));
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
    while($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        if ($type == "2" || $type == 2){
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */"INSERT INTO {*shipment_status*} (id_status, id_shipment) VALUES ('1', '$id')"));
        }
        addCartToReservation($id, $cart);
    }
}
function getReservedCartsSearch($type){
    $arr = array(array());
    $qr1 = array();
    $qr2 = array();
    $str = "";
    if (isset($_GET['statusSearch']) && $_GET['statusSearch'] != ""){
        foreach ($_GET['statusSearch'] as $value) {
            array_push($qr1, "(SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)='$value'");
        }
        $str .= "AND (".implode(" OR ", $qr1).")";
    }
    if (isset($_GET['typeSearch']) && $_GET['typeSearch'] != ""){
        foreach ($_GET['typeSearch'] as $value){
            array_push($qr2, "(SELECT id_type FROM {*shipment_data*} WHERE id_shipment={*reserved*}.id)='$value'");
        }
        $str .= "AND (".implode(" OR ", $qr2).")";
    }
    if (isset($_GET['searchIDorBarcode']) && $_GET['searchIDorBarcode'] != ""){
        $s = $_GET['searchIDorBarcode'];
        $str .= "AND ((SELECT barcode FROM {*shipment_data*} WHERE id_shipment={*reserved*}.id) LIKE '%$s%' OR {*reserved*}.id LIKE '%$s%' OR {*reserved*}.comment LIKE '%$s%')";
    }


    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id_type='$type' AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)!='6' $str ORDER BY date DESC"));
    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        $arr[$id] = readReservationResult($row);
    }
    return array_filter($arr);
}

function getReservedCartsShipmentOnlyChecked($page, $type){
    $str = "";
    $onPage = _ENGINE['onPage'];
    $start = $page*$onPage;
    if (isset($_GET['searchIDorBarcode'])){
        $s = $_GET['searchIDorBarcode'];
        $str .= "AND ((SELECT barcode FROM {*shipment_data*} WHERE id_shipment={*reserved*}.id)='$s' OR {*reserved*}.id='$s')";
    }
    $arr = array(array());
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
        "SELECT * FROM {*reserved*} 
                WHERE id_type='$type' AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)='6' 
                $str ORDER BY date DESC LIMIT $start, $onPage"
    ));
    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        $arr[$id] = readReservationResult($row);
    }
    return array_filter($arr);
}

function getReservedCarts_range($page, $type){
    $arr = array(array());
    $onPage = _ENGINE['onPage'];
    $start = $page*$onPage;
    $str = "";
    if ($type == 2){
        $str = " AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)!='6'";
    }
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id_type='$type' $str ORDER BY date DESC LIMIT $start, $onPage"));
    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        $arr[$id] = readReservationResult($row);
    }
    return array_filter($arr);
}

function getReservationsDatalist($type){
    $arr = array(array());
    $str = "";
    if ($type == 2){
        $str = " AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)!='6'";
    }
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, comment FROM {*reserved*} WHERE id_type='$type' $str"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr[$row['id']] = $row['id']. " | ".$row['comment'];
    }
    return array_filter($arr);
}

function getReservedCarts($type='unset', $api=false){
    $arr = array(array());
    $str = "";
    if ($type == 2){
        $str = " AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)!='6'";
    }
    if ($type == "checked"){
        $type = 2;
        $str = " AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)='6'";
    }
    $type_q = "WHERE id_type='$type'";
    if ($type == 'unset'){
        $type_q = "WHERE id_type='1' OR id_type='2'";
    }
    if ($api){
        if ($type == 'unset'){
            $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, comment, id_type FROM {*reserved*} $type_q $str ORDER BY date DESC"));
        } else {
            $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, comment FROM {*reserved*} $type_q $str ORDER BY date DESC"));

        }
    } else {
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} $type_q $str ORDER BY date DESC"));
    }
    while ($row = mysqli_fetch_assoc($q)){
        if ($api){
            $arr[$row['id']]['comment'] = $row['comment'];
            if ($type == 'unset'){
                $arr[$row['id']]['id_type'] = $row['id_type'];
            }
        } else {
            $id = $row['id'];
            $arr[$id] = readReservationResult($row);
        }

    }
    return array_filter($arr);
}

function getSingleCartReservation($id){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT *, 
    (SELECT `name` FROM {*reservation_types*} WHERE id={*reserved*}.id_type) as type_name FROM {*reserved*} WHERE id='$id'"));
    $row = $q->fetch_assoc();
    $arr = readReservationResult($row);
    if ($row['id_type'] == 2){
        include_once $_SERVER['DOCUMENT_ROOT'] . "/cp/POS/shipping/getShippingData.php";
        $arr['shipping_data'] = getData_full($id);
    }
    return $arr;
}

function get_reservation_cart_sum($id){
    $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang text */ "SELECT * FROM {*reserved_products*} WHERE id_reserved='$id'"));
    if ($q){
        $sum = 0;
        while ($row = $q->fetch_assoc()){
            $sum += $row['price'];
        }
        return $sum;
    } else {
        return null;
    }

}

function readReservationResult($row){
    $id = $row['id'];
    $arr = $row;
    $q_products = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM 
                                                                    {*reserved_products*} WHERE id_reserved='$id'"));
    while ($row_products = mysqli_fetch_assoc($q_products)){
        $arr['products'][$row_products['id']] = $row_products;
        if (!is_numeric($row_products['id_product'])){
            $arr['products'][$row_products['id']]['tag'] = "Buffertoode";
            $arr['products'][$row_products['id']]['name'] = $row_products['id_product'];
            $arr['products'][$row_products['id']]['location'] = 0;
        } else {
            $arr['products'][$row_products['id']]['tag'] = get_tag($row_products['id_product']);
            $arr['products'][$row_products['id']]['name'] = get_name($row_products['id_product']);

            if($row_products['id_location'] !== 0 && $row_products['id_location'] !== "0"){
                $arr['products'][$row_products['id']]['location'] = get_location_data($row_products['id_location']);
            } else {
                $arr['products'][$row_products['id']]['location'] = 0;
            }
        }
    }
    if (!is_null($arr['products'])){
        usort($arr['products'] , function($a, $b) {
            return $a['location'] <=> $b['location'];
        });
    }

    return $arr;
}

function cancelReservationFull($id){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, quantity, id_product, id_location FROM 
                    {*reserved_products*} WHERE id_reserved='$id'"));


    while ($row = mysqli_fetch_assoc($q)){
        $quantity = $row['quantity'];
        $id_product = $row['id_product'];
        $reserved_prod_row_id = $row['id'];
        if (is_numeric($id_product)){
            update_quantity($id_product, $row['id_location'], "+", $quantity);
        }
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved_products*} WHERE id='$reserved_prod_row_id'"));
    }
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "DELETE FROM {*reserved*} WHERE id='$id'"));

}

function cancelReservationProduct($id, $id_prod_res){
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT id, quantity, id_product, id_location FROM 
                    {*reserved_products*} WHERE id='$id_prod_res'"));
    while ($row = mysqli_fetch_assoc($q)){
        $quantity = $row['quantity'];
        $reserved_prod_row_id = $row['id'];
        $id_product = $row['id_product'];
        if (is_numeric($id_product)){
            update_quantity($id_product, $row['id_location'], "+", $quantity);
        }
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved_products*} WHERE id='$reserved_prod_row_id'"));
    }
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT COUNT(*) as count FROM 
                    {*reserved_products*} WHERE id_reserved='$id'"));
    while ($row = mysqli_fetch_assoc($q)){
        if ($row['count'] == 0){
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "DELETE FROM {*reserved*} WHERE id='$id'"));
        }
    }
}
function editReservation($id, $data){
    $oldData = getSingleCartReservation($id);
    if (empty($data)){
        exit(json_encode(array("error"=>"No products found in the array.", "code"=>"203")));
    }
    foreach ($data as $key => $value){
        if (is_numeric($key)){
            foreach ($oldData['products'] as $val){
                if ($val['id'] == $key){
                    if (isset($value['quantity'])){
                        editReservationProduct($value, $val);
                    } else {
                        exit(json_encode(array("error"=>"No quantity field supplied.", "code"=>"200")));
                    }
                }
            }
        } else {
            $found = false;
            foreach ($oldData['products'] as $val){
                if ($val['tag'] == $key){
                    if (isset($value['quantity'])){
                        editReservationProduct($value, $val);
                        $found = true;
                    } else {
                        exit(json_encode(array("error"=>"No quantity field supplied.", "code"=>"200")));
                    }
                }
            }
            if (!$found){
                include_once($_SERVER['DOCUMENT_ROOT'] . "/api/func/fromCart.php");
                $cart = formCart($key, array_filter($value));
                addCartToReservation($id, array_filter($cart));
            }
        }
    }
    return $oldData;
}

function editReservationProduct($value, $oldValues){
    $id = $oldValues['id'];
    $quantity = $value['quantity'];
    if (isset($value['price']) && isset($value['basePrice'])){
        $price = round($value['price'], 2);
        $basePrice = round($value['basePrice'], 2);
    } elseif (isset($value['basePrice'])){
        $basePrice = round($value['basePrice'], 2);
        $price = round($quantity*$basePrice, 2);
    } elseif (isset($value['price'])){
        $price = round($value['price'], 2);
        $basePrice = round($price/$quantity, 2);
    } else {
        $price = round($oldValues['basePrice']*$quantity, 2);
        $basePrice = round($oldValues['basePrice'], 2);
    }

    if (!is_numeric($price) || !is_numeric($basePrice)){
        exit(json_encode(array("error"=>"Price or Base price is not number.", "code"=>"201")));
    }
    $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved_products*} SET `price`='$price', basePrice='$basePrice', quantity='$quantity' WHERE id='$id'"));
    updateProductPriceInReservationEdit($oldValues, $quantity);
}

function updateProductPriceInReservationEdit($value, $newQuantity){
    $id_location = $value['id_location'];
    $id_product = $value['id_product'];
    if ($id_product != "Buffertoode"){

        if ($value['id_location'] == 0 || is_null($value['id_location'])){
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*product_locations*} WHERE id_item='$id_product' LIMIT 1"));
            $row_q = $q->fetch_assoc();
            $quantity = $row_q['quantity']+$value['quantity']-$newQuantity;
            $new_loc_id = $row_q['id'];

            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$new_loc_id'"));
        } else {
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*product_locations*} WHERE id='$id_location'"));
            $row_q = $q->fetch_assoc();
            $quantity = $row_q['quantity']+$value['quantity']-$newQuantity;
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$id_location'"));

        }
        PR_PUT_Product($id_product);
    }

}


$post=json_decode(file_get_contents("php://input"));

if (isset($post->req)){
    $request = json_decode($post->req);
    if ($request->reserve == "true") {

        reserveCart($request->note, $_SESSION['cart']);
        $_SESSION['cart'] = array();
        $_SESSION['cartTotal'] = 0.00;
        updateCart();
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));

        exit(json_encode(array("id"=>mysqli_fetch_assoc($q)['id'])));
    }
}
if (isset($_GET['getReservationItemsJSON'])){
    $reservation = getSingleCartReservation($_GET['getReservationItemsJSON']);
    echo json_encode($reservation);
}

if (isset($_GET['getReservationDataList'])){
    echo json_encode(getReservationsDatalist($_GET['getReservationDataList']));
}

