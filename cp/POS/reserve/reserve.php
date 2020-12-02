
<?php
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
    return $row['name']." | ".$row['location'];
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
    updateCart();
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

function getReservedCartsShipmentOnlyChecked($type){
    $str = "";
    if (isset($_GET['searchIDorBarcode'])){
        $s = $_GET['searchIDorBarcode'];
        $str .= "AND ((SELECT barcode FROM {*shipment_data*} WHERE id_shipment={*reserved*}.id)='$s' OR {*reserved*}.id='$s')";
    }
    $arr = array(array());
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id_type='$type' AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)='6' $str ORDER BY date DESC"));
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

function getReservedCarts($type){
    $arr = array(array());
    $str = "";
    if ($type == 2){
        $str = " AND (SELECT id_status FROM {*shipment_status*} WHERE id_shipment={*reserved*}.id)!='6'";
    }
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT * FROM {*reserved*} WHERE id_type='$type' $str ORDER BY date DESC"));
    while ($row = mysqli_fetch_assoc($q)){
        $id = $row['id'];
        $arr[$id] = readReservationResult($row);
    }
    return array_filter($arr);
}

function getSingleCartReservation($id){
    $arr = array();
    $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT *, 
    (SELECT `name` FROM {*reservation_types*} WHERE id={*reserved*}.id_type) as type_name FROM {*reserved*} WHERE id='$id'"));
    while ($row = mysqli_fetch_assoc($q)){
        $arr = readReservationResult($row);
    }
    return $arr;
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

if (isset($_POST['req'])){
    $request = json_decode($_POST['req']);
    if ($request->reserve == "true") {
        reserveCart($request->note, $_SESSION['cart']);
        $_SESSION['cart'] = array();
        $_SESSION['cartTotal'] = 0.00;
        updateCart();
        echo "Reserve success";
    }
}
if (isset($_GET['getReservationItemsJSON'])){
    $reservation = getSingleCartReservation($_GET['getReservationItemsJSON']);
    echo json_encode($reservation);
}


