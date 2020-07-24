<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="/templates/default/assets/css/bootstrap.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="  https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
</head>
<body>
<button type="button" onclick="printJS(
        {printable: 'form', type: 'html',documentTitle: 'Invoice', css: 'https\://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'}
        )">
    Print Form
</button>
<div id="form">
    <div class="row">
        <div class="col-9">
            <img src="aaaaaaa.png">
        </div>
        <div class="col-1">
            <b>
                ARVE<br />
                Kuupäev
            </b>
        </div>
        <div class="col-2">
            <b>
                №17062001 <br />
                7/23/2020
            </b>
        </div>
    </div>
    <div class="row">
        <div class="col-6" style="border: 1px solid black">
            <div class="row">
                <div class="col-2">
                    <b>Müüja:</b><br />
                    Reg Nr<br />
                    Aadress<br />
                    KMKR
                </div>
                <div class="col-10">
                    AZ Trade OÜ<br />
                    12474341<br />
                    J. Koorti tn 2-122 Tallinn Harjumaa 13623<br />
                    EE101681917
                </div>
            </div>
        </div>
        <div class="col-6" style="border: 1px solid black">
            <div class="row">
                <div class="col-2">
                    <b>Ostja:</b><br />
                    Reg Nr<br />
                    Aadress<br />
                    Makseviis
                </div>
                <div class="col-10">
                    LEIAD OÜ<br />
                    14042545<br />
                    Meeliku tn 21/3-105 Tallinn Harjumaa 13915<br />
                    Pank
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2 offset-6">
            <b>Maksetähtaeg:</b><br />
            Viivis:
        </div>
        <div class="col-4">
            <b>8/6/2020</b><br />
            0.05%
        </div>
    </div>
    <div class="row">
        <div class="col-1" style="border: 1px solid black">Kood</div>
        <div class="col-3" style="border: 1px solid black">Teenuste/kaupade nimetus</div>
        <div class="col-1" style="border: 1px solid black">Ühik</div>
        <div class="col-1" style="border: 1px solid black">Maht</div>
        <div class="col-2" style="border: 1px solid black">Hind KM-ga</div>
        <div class="col-2" style="border: 1px solid black">Hind KM-ta</div>
        <div class="col-2" style="border: 1px solid black">Kokku</div>
    </div>
    <div class="row">
        <div class="col-1" style="border: 1px solid black"></div>
        <div class="col-3" style="border: 1px solid black">topstee.ee Kodulehe uuendamine, analüüs, ettevalmistustööd</div>
        <div class="col-1" style="border: 1px solid black">tk</div>
        <div class="col-1" style="border: 1px solid black">40.00</div>
        <div class="col-2" style="border: 1px solid black">26.40</div>
        <div class="col-2" style="border: 1px solid black">22.00</div>
        <div class="col-2" style="border: 1px solid black">880.00</div>
    </div>
    <div class="row">
        <div class="col-1 offset-6">
            Summa<br />
            Käibemaks 20%<br />
            <b>Kokku</b><br />
            Valuuta
        </div>
        <div class="col-5">
            880.00<br />
            176.00<br />
            <b>1056.00</b><br />
            <b>EUR</b>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-6">
            AZ TRADE OÜ, J. Koorti tn 2-122, 13623, Tallinn<br />
            Reg nr: 12474341 , KMKR: EE101681917<br />
            E-post: info@bigshop.ee<br />
            Telefon: +37258834435
        </div>
        <div class="col-6">
            SWEDBANK EE792200221057460362<br />
            SWIFT: HABAEE2X
        </div>
    </div>
</div>
</body>
</html>