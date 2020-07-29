<?php

ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/update.php';

include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
if (!defined('PRODUCTS_INCLUDED')){
    include_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/products/get_products.php');
}
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/reserve/reserve.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/orderMode.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/products/updateQuantity.php';
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/log.php';
if (isset($_GET['reservation'])){
    if ($_GET['reservation'] === NULL || $_GET['reservation'] == "" || empty($_GET['reservation'])){
        exit("Cannot process empty reservation. Please verify that the reservation contained items or contact the 
        administrator.");
    } else {
        $sum = 0.00;
        if (isset($_GET['cart'])){
            $cartItems = json_decode($_GET['cart'],true);
            $cartItemsTemp = array();
            foreach ($cartItems as $value){
                $key = $value['id'];
                $cartItemsTemp[$key] = $value;
                $sum += $cartItemsTemp[$key]['quantity']*$cartItemsTemp[$key]['basePrice'];
                if (!is_numeric($value['id'])){
                    $cartItemsTemp[$key]['name'] = $value['id'];
                    $cartItemsTemp[$key]['tag'] = "Buffertoode";
                } else {
                    $cartItemsTemp[$key]['name'] = get_name($key)['et'];
                    $cartItemsTemp[$key]['tag'] = get_tag($key);
                }

            }
            $cartItems = $cartItemsTemp;
        } elseif ($_GET['id_cart']){
            $reservation = getSingleCartReservation($_GET['id_cart']);
            $cartItems = $reservation['products'];
            foreach ($cartItems as $key => $value){
                $sum += $cartItems[$key]['quantity']*$cartItems[$key]['basePrice'];
                if (is_numeric($value['id_product'])){
                    $cartItems[$key]['name'] = $cartItems[$key]['name']['et'];
                } else {
                    $cartItems[$key]['name'] = $value['id_product'];
                }
            }

        } else {
            exit("No reserved product or reservation id found in the URL contact the administrator.");
        }
    }
} else {
    $cartItems = $_SESSION['cart'];
    $sum = $_SESSION['cartTotal'];
}

sys_log(array("GET"=>$_GET, "POST"=>$_POST));

// card init
if (isset($_POST['card'])){
    $card = $_POST['card'];
} elseif (isset($_GET['card'])){
    $card = $_GET['card'];
}
// cash init
if (isset($_POST['cash'])){
    $cash = $_POST['cash'];
} elseif (isset($_GET['cash'])){
    $cash = $_GET['cash'];
}
// mode init
if (isset($_POST['mode'])){
    $mode = $_POST['mode'];
} elseif (isset($_GET['mode'])){
    $mode = $_GET['mode'];
}

$tagasi = number_format((float)($card+$cash)-$sum, 2, ".", "");
$sumKM = round($sum/1.2, 2);
$KM = $sum - $sumKM;

$date = date("d.m.Y H:i:s");
$mysqldate = date("Y-m-d H:i:s");
$stamp = date_timestamp_get(date_create())*9;

if (isset($_POST['ostja'])){
    $ostja = orderMode($mode, $_POST['ostja']);
} elseif (isset($_GET['ostja'])){
    $ostja = orderMode($mode, $_GET['ostja']);
}

// Tellimuse number init
if(isset($_POST['tellimuseNr']) && $_POST['tellimuseNr'] != ""){
    $telli = $_POST['tellimuseNr'];
} else {
    $telli = "";
}
if(isset($_GET['tellimuseNr']) && $_GET['tellimuseNr'] != ""){
    $telli = $_GET['tellimuseNr'];
} else {
    $telli = "";
}


mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*sales*}
                                (cartSum, card, cash, arveNr, saleDate, ostja, modeSet, tellimuseNr) 
                                VALUES ('$sum', '$card', '$cash', '$stamp','$mysqldate', '$ostja', '$mode', '$telli')"));


$q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM sales"));

$row = mysqli_fetch_assoc($q);
$id = $row['id'];
foreach ($cartItems as $value){
    $quantity = $value['quantity'];
    $loc = "";
    if($value['tag'] != "Buffertoode"){
        if(isset($value['id_location'])){
            $loc = $value['id_location'];
        } elseif (isset($value['loc']['selected'])){
            $loc = $value['loc']['selected'];
        }
    }
    if (isset($_GET['id_cart'])) {
        $itemID = $value['id_product'];
    } elseif (isset($_GET['cart'])){
        $itemID = $value['id'];
    } else {
        $itemID = $value['id'];
        if($value['tag'] != "Buffertoode"){
            update_quantity($itemID ,$loc, "-", $quantity);
        } else {
            $itemID = $value['name'];
        }
    }
    $price = $value['price'];
    $basePrice = $value['basePrice'];

    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
        "INSERT INTO {*sold_items*} (id_sale, id_item, price, quantity, basePrice, statusSet, id_location
                                        ) VALUES ('$id', '$itemID', '$price', '$quantity', '$basePrice','Müük', '$loc')"));

}
?>
<html>
<head>

    <style>
        @font-face {
            font-family: 'merchant_copyregular';
            src: url('/templates/default/assets/font/fake_receipt-webfont.woff2') format('woff2'),
            url('/templates/default/assets/font/fake_receipt-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;

        }
        body, div, table{
            font-family: 'merchant_copyregular';
            width: 330px;
            font-size: 16px;
            display: inline-block;
        }
        td, tr {
            word-wrap:break-word;
        }
        .line{
            font-size: 14px;
            display: table-cell;
        }
    </style>
    <script src="/print.min.js"></script>
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
</head>
<body>
<div id="printable" style="height: auto">
    <center style="font-weight: bold; padding-bottom: 50px"><?= $date;?><br>
     Arve nr: <?= $stamp; ?><br>
    <?php if ($ostja != ""){
        echo "Ostja:".$ostja; ?><?php
    } else {
        ?>Ostja: Eraisik<?php
    }
    ?>
    </center>
    <div style="padding-bottom: 50px">
        <?php
        foreach ($cartItems as $value){
        ?>
        <table>
            <tr>
                <?php
                if ($value['tag'] != "Buffertoode"){
                    echo "<td style=\"padding-right: 25px\">";
                    echo $value['tag']."</td>";
                } else {
                }
                ?>
                <td><?= $value['name'];?></td>
            </tr>
        </table>
            <div style="width: 330px; border-bottom: 1px solid black; border-spacing: 0px 5px;">
                <div class="line">Hind <?= $value['basePrice'];?>€</div>
                <div class="line" style="padding-right: 10px"><?= $value['quantity'];?>tk</div>
                <div class="line">Summa <?= $value['price'];?>€</div>
            </div>
        <br>
        <?php }
        ?>
        <p align="right" style="float: right">Kokku KM-ta: <?= $sumKM;?>€<br>
        KM: <?= $KM;?>€<br>
        Kokku KM-ga: <?= $sum;?>€</p>
        </div>
    <p align="right" style="float: right">Sularahaga: <?= $cash;?>€<br>
        Kaardiga: <?= $card;?>€<br>
        Tagasi: <?= $tagasi;?>€
    </p>
    <p style="display: inline-block">
    AZ TRADE OÜ<br> J. Koorti tn 2-122, 13623, Tallinn<br>
    Reg nr: 12474341 <br> KMKR: EE101681917<br>
    E-post: info@bigshop.ee<br>
    Telefon: +37258834435<br>
    www.bigshop.ee<br>
    <br>
    <center style="font-weight: bold;">Kohtumiseni!</center></p>
</div>
<?php
if (!isset($_GET['reservation'])){
    unset($_SESSION['cart']);
    unset($_SESSION['cartTotal']);
    updateCart();
} else {
    if (isset($_GET['cart'])){
        foreach ($cartItems as $key => $value){
            $id_res=$_GET['id_res'];
            mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "DELETE FROM {*reserved_products*} WHERE id_product='$key' AND id_reserved='$id_res'"));
            $q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT COUNT(*) as count FROM 
                    {*reserved_products*} WHERE id_reserved='$id_res'"));
            while ($row = mysqli_fetch_assoc($q)){
                if ($row['count'] == 0){
                    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
                        "DELETE FROM {*reserved*} WHERE id='$id_res'"));
                }
            }
        }
    } elseif ($_GET['id_cart']){
        $id_res = $_GET['id_cart'];
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "DELETE FROM {*reserved*} WHERE id='$id_res'"));
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "DELETE FROM {*reserved_products*} WHERE id_reserved='$id_res'"));
    }
}
?>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.focus();
        window.print();
        window.location.replace("/cp/POS?success");
        document.body.innerHTML = originalContents;
    }
    window.onload = function(){ setTimeout( function(){ printDiv('printable'); }, 1000); };
</script>
</body>
</html>
