<?php
ini_set("display_errors", "on");
error_reporting(E_ALL ^ E_NOTICE);

include($_SERVER["DOCUMENT_ROOT"].'/configs/setup.php');
include($_SERVER["DOCUMENT_ROOT"].'/controllers/session.php');
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
include($_SERVER["DOCUMENT_ROOT"]).'/controllers/getSales.php';
$desc = array_filter($desc);
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
    <?php
    foreach ($arr as $val){
    ?>
    <center style="font-weight: bold; padding-bottom: 50px">
        Korduv kviitung<br>
        <?= $val['date'];?><br>
        Arve nr: <?= $val['arveNr']; ?><br>
        Ostja: <?=$val['ostja']; ?>
    </center>
    <div style="padding-bottom: 50px">
        <?php
        foreach ($desc as $value){
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
        $sumKM = round($val['sum']/1.2, 2);
        $KM = $val['sum'] - $sumKM;
        ?>
        <p align="right" style="float: right">Kokku KM-ta: <?= $sumKM;?>€<br>
            KM: <?= $KM;?>€<br>
            Kokku KM-ga: <?= $val['sum'];?>€</p>
    </div>
    <p align="right" style="float: right">Sularahaga: <?= $val['cash'];?>€<br>
        Kaardiga: <?= $val['card'];?>€</p>
    <p style="display: inline-block">
        AZ TRADE OÜ<br> J. Koorti tn 2-122, 13623, Tallinn<br>
        Reg nr: 12474341 <br> KMKR: EE101681917<br>
        E-post: info@bigshop.ee<br>
        Telefon: +37258834435<br>
        www.bigshop.ee<br>
        <br>
    <center style="font-weight: bold;">Kohtumiseni!</center></p>
    <?php }
    ?>
</div>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.focus();
        window.print();
        window.close();
        document.body.innerHTML = originalContents;
    }
    window.onload = function(){ setTimeout( function(){ printDiv('printable'); }, 1000); };
</script>
</body>
</html>