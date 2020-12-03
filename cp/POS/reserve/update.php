<?php
include_once($_SERVER["DOCUMENT_ROOT"].'/configs/config.php');

if (isset($_POST['id'])){
    $id = $_POST['id'];
    if (isset($_POST['comment'])){
        $comment = $_POST['comment'];
        $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved*} SET `comment`='$comment' WHERE id='$id'"));
        echo prefixQuery(/** @lang */ "UPDATE {*reserved*} SET `comment`='$comment WHERE id='$id'");
    }
    if (isset($_POST['quantity']) && isset($_POST['price'])){

        foreach ($_POST['quantity'] as $key => $value){
            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*reserved_products*} WHERE id='$key'"));
            $row = $q->fetch_assoc();
            $id_product = $row['id_product'];
            $oldQuantity = $row['quantity'];
            $id_location = $row['id_location'];
            if ($id_location !== 0 || $id_location !== null){
                $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*product_locations*} WHERE id='$id_location'"));
                $row_q = $q->fetch_assoc();
                $quantity = $row_q['quantity']+$oldQuantity-$value;
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$id_location'"));
            } else {
                $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*product_locations*} WHERE id_product='$id_location' LIMIT 1"));
                $row_q = $q->fetch_assoc();
                $quantity = $row_q['quantity']+$oldQuantity-$value;
                $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$id_location'"));
            }

            $price = $_POST['price'][$key];
            $basePrice = round($price/$value, 2);
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved_products*} SET `quantity`='$value', price='$price', basePrice='$basePrice' WHERE id='$key'"));
        }
    }
    if (isset($_POST['nameNewBuffer']) && isset($_POST['quantityNewBuffer'])  && isset($_POST['priceNewBuffer'])){
        foreach ($_POST['quantityNewBuffer'] as $key => $value){
            $name = $_POST['nameNewBuffer'][$key];
            $price = $_POST['priceNewBuffer'][$key];
            $basePrice = round($price/$value, 2);
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*reserved_products*} (id_reserved, id_product, quantity, price, basePrice) VALUES ('$id', '$name', '$value', '$price', '$basePrice')"));
        }
    }
    if (isset($_POST['quantityNew']) && isset($_POST['priceNew'])  && isset($_POST['loc_select'])){
        foreach ($_POST['quantityNew'] as $key => $value){
            $loc = $_POST['loc_select'];

            $q = $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "SELECT * FROM {*product_locations*} WHERE id='$loc'"));
            $row_q = $q->fetch_assoc();
            $quantity = $row_q['quantity']-$value;
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*product_locations*} SET quantity='$quantity' WHERE id='$loc'"));

            $price = $_POST['priceNew'][$key];
            $basePrice = round($price/$value, 2);
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*reserved_products*} (id_reserved, id_product, quantity, price, basePrice, id_location) VALUES ('$id', '$key', '$value', '$price', '$basePrice', '$loc')"));
        }
    }
    header("Location: /cp/POS/reserve/index.php?view=".$id);
}
header("Location: /cp/POS/reserve/");