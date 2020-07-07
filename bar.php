<html>
<head>
    <script src="bar.js"></script>
    <script src="print.min.js"></script>
    <script
            src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous"></script>
    <style>
        table, tr, td {
            font-size: 18px;
            font-family: Calibri, serif;
            word-wrap: break-word;
            max-width: 235px;
        }

        @page {
            margin: 0;
        }
    </style>
</head>
<body>
<?php
if (isset($_GET['customBarcode']) && isset($_GET['customSubmit'])){
    ?>
<center>
        <div id="printable" style="height: 100%;">

<div style="position: absolute; bottom: 50px;font-size: 18px;font-family: Calibri, serif;">
    <div style="display: flex;justify-content: center;align-items: center;">
        <svg id="barcode"></svg>
    </div>
</div>
</div>
<input type="button" onclick="printDiv('printable')" value="print a div!"/>
</center>
<script>
    JsBarcode("#barcode", "<?php echo $_GET['customBarcode'];?>", {
        height: 50,
        width: 1.8,
        displayValue: true

    });

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.focus();
        window.print();
        window.close();
        document.body.innerHTML = originalContents;
    }

    window.onload = function () {
        setTimeout(function () {
            printDiv('printable');
        }, 1000);
    };

</script>
<?php
} else {

    ?>

    <center>
        <div id="printable" style="height: 100%;">
            <table>
                <tr>
                    <td>Product:</td>
                    <td><b style="display: -webkit-box;
-webkit-line-clamp: 3;
-webkit-box-orient: vertical;
overflow: hidden;
text-overflow: ellipsis;"><?php echo $_GET['name']; ?></b></td>
                </tr>
                <tr>
                    <td style="font-size: 16px !important;">WEB:</td>
                    <td style="font-size: 16px !important;">www.bigshop.ee</td>
                </tr>
                <tr>
                    <td style="font-size: 16px !important;">Email:</td>
                    <td style="font-size: 16px !important;">info@bigshop.ee</td>
                </tr>
                <tr>
                    <td style="font-size: 16px !important;">Address:</td>
                    <td style="font-size: 16px !important;">Laki tn 9a, Tallinn, 10621</td>
                </tr>
                <tr>
                    <td>Importer:</td>
                    <td>AZ TRADE OÜ</td>
                </tr>
            </table>
            <div style="position: absolute; bottom: 50px;font-size: 18px;font-family: Calibri, serif;">
                <div style="display: flex;justify-content: center;align-items: center;">
                    <svg id="barcode"></svg>
                    <b style="padding-right: 2px"><?php echo $_GET['tag']; ?></b>
                    <img width="40px" height="28px" alt="CE" src="/templates/default/assets/dPatEnV7f_I.jpg">
                    <img width="40px" height="40px" alt="CE" src="/templates/default/assets/rEmMzBJ5nks.jpg">
                </div>
            </div>
        </div>
        <input type="button" onclick="printDiv('printable')" value="print a div!"/>
    </center>
    <script>
        JsBarcode("#barcode", "<?php echo $_GET['tag'];?>", {
            height: 30,
            width: 1.8,
            displayValue: false

        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.focus();
            window.print();
            window.close();
            document.body.innerHTML = originalContents;
        }

        window.onload = function () {
            setTimeout(function () {
                printDiv('printable');
            }, 1000);
        };

    </script>


    <?php
}
?>
</body>
</html>