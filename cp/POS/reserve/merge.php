<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');
include_once($_SERVER["DOCUMENT_ROOT"].'/cp/POS/reserve/reserve.php');

function mergeReservations($data){
    set_error_handler(function($errno, $errstr, $errfile, $errline ){
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    });
    try {
        $merged = array(array());
        $notes = array();
        $c = 1;

        foreach ($data as $id){
            $p = getSingleCartReservation($id);
            array_push($notes, $p['comment']);
            foreach ($p['products'] as $key => $value){
                if (is_numeric($value['id_product'])){
                    if (array_key_exists($value['id_product'], $merged)){
                        $merged[$value['id_product']]['quantity'] += $value['quantity'];
                        $merged[$value['id_product']]['price'] += round($value['price'],2);
                    } else {
                        $merged[$value['id_product']] = $value;
                    }
                } else {
                    if (array_key_exists($value['id_product'], $merged)){
                        $merged[$value['id_product'].$c] = $value;
                    } else {
                        $merged[$value['id_product']] = $value;
                    }
                }
            }
        }
        $note = "Merged: ". implode(" | ", $notes);
        $merged = array_filter($merged);
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*reserved*} (`comment`, `id_type`)
                                                                                           VALUES ('$note', '1')"));
        $last = "";
        $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM {*reserved*}"));
        while($row = mysqli_fetch_assoc($q)) {
            $last = $row['id'];
            foreach ($merged as $key => $value){

                $quantity = $value['quantity'];
                $price = $value['price'];
                $basePrice = $value['basePrice'];
                $id_loc = $value['id_location'];
                mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
                    "INSERT INTO {*reserved_products*} (id_reserved, id_product, price, quantity, basePrice, id_location
                                        ) VALUES ('$last', '$key', '$price', '$quantity', '$basePrice', '$id_loc')"));

            }
        }
        foreach ($data as $id){
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*reserved_products*} WHERE id_reserved='$id'"));
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "DELETE FROM {*reserved*} WHERE id='$id'"));
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*reservation_merge_history*} (`from`, `merged_to`) VALUES ('$id', '$last')"));
        }
        return $last;
    } catch (\ErrorException $e){
        echo $e;
        echo "Error occured.";
    }

    return null;
}
if (isset($_GET['mergeList'])){
    $data = $_GET['mergeList'];
    $data = json_decode($data, true);
    mergeReservations($data);
}