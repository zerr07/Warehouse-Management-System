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
            $price = $_POST['price'][$key];
            $basePrice = round($price/$value, 2);
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "UPDATE {*reserved_products*} SET `quantity`='$value, price='$price', basePrice='$basePrice' WHERE id='$key'"));
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
            $price = $_POST['priceNew'][$key];
            $basePrice = round($price/$value, 2);
            $GLOBALS['DBCONN']->query(prefixQuery(/** @lang */ "INSERT INTO {*reserved_products*} (id_reserved, id_product, quantity, price, basePrice, id_location) VALUES ('$id', '$key', '$value', '$price', '$basePrice', '$loc')"));
        }
    }
    header("Location: /cp/POS/reserve/index.php?view=".$id);
}
header("Location: /cp/POS/reserve/");