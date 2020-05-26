<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);
include($_SERVER["DOCUMENT_ROOT"]).'/cp/POS/update.php';

include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';

$sum = $_SESSION['cartTotal'];
$card = $_POST['card'];
$cash = $_POST['cash'];
$tagasi = number_format((float)($card+$cash)-$sum, 2, ".", "");
$sumKM = round($_SESSION['cartTotal']/1.2, 2);
$KM = $_SESSION['cartTotal'] - $sumKM;
$mode = $_POST['mode'];
$date = date("d.m.Y H:i:s");
$mysqldate = date("Y-m-d H:i:s");
$stamp = date_timestamp_get(date_create())*9;
if ($_POST['ostja'] != ""){
    $ostja = $_POST['ostja'];
} else {
    $ostja = "Eraisik";
}
if($mode != 'Bigshop'){
    $ostja = $mode;
}
if ($_POST['tellimuseNr'] != "") {
    $telli = $_POST['tellimuseNr'];
} else {
    $telli = "";
}
mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "INSERT INTO {*sales*}
                                (cartSum, card, cash, arveNr, saleDate, ostja, modeSet, tellimuseNr) 
                                VALUES ('$sum', '$card', '$cash', '$stamp','$mysqldate', '$ostja', '$mode', '$telli')"));


$q = mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */ "SELECT MAX(id) as id FROM sales"));

$row = mysqli_fetch_assoc($q);
$id = $row['id'];
foreach ($_SESSION['cart'] as $key => $value){
    $itemID = $value['id'];
    $quantity = $value['quantity'];
    $price = $value['price'];
    $basePrice = $value['basePrice'];
    if($value['tag'] != "Buffertoode"){
        mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
            "UPDATE {*products*} SET quantity=quantity-$quantity WHERE id='$key'"));

    } else {
        $key = $value['name'];
    }
    mysqli_query($GLOBALS['DBCONN'], prefixQuery(/** @lang text */
        "INSERT INTO {*sold_items*} (id_sale, id_item, price, quantity, basePrice, statusSet
                                        ) VALUES ('$id', '$key', '$price', '$quantity', '$basePrice','Müük')"));

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
    <?php if ($_POST['ostja'] != ""){
        echo "Ostja:".$_POST['ostja']; ?><?php
    } else {
        ?>Ostja: Eraisik<?php
    }
    ?>
    </center>
    <div style="padding-bottom: 50px">
        <?php
        foreach ($_SESSION['cart'] as $key => $value){
        ?>
        <table>
            <tr>
                <td style="padding-right: 25px"><?= $value['tag'];?></td>
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
        Kokku KM-ga: <?= $_SESSION['cartTotal'];?>€</p>
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
</div><?php unset($_SESSION['cart']); unset($_SESSION['cartTotal']); updateCart();?>
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
