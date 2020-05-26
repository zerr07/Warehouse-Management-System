
<div id='printarea' style="visibility: hidden">
    <div id="printable" style="height: 100%;">
        <style>
            #bartd {
                font-size: 24px;
                font-family: Calibri, serif;
                word-wrap: break-word;
                max-width: 250px;
            }
        </style>
        <table id="bartd">
            <tr id="bartd">
                <td id="bartd">Product:</td>
                <td id="bartd"><b style="display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                        {$item.name}
                    </b></td>
            </tr>
            <tr id="bartd">
                <td id="bartd" style="font-size: 18px !important;">WEB:</td>
                <td id="bartd" style="font-size: 18px !important;">www.bigshop.ee</td>
            </tr>
            <tr id="bartd">
                <td id="bartd" style="font-size: 18px !important;">Email:</td>
                <td id="bartd" style="font-size: 18px !important;">info@bigshop.ee</td>
            </tr>
            <tr id="bartd">
                <td id="bartd" style="font-size: 18px !important;">Address:</td>
                <td id="bartd" style="font-size: 18px !important;">Narva mnt 150, Tallinn, 13628</td>
            </tr>
            <tr id="bartd">
                <td id="bartd">Importer:</td>
                <td id="bartd">AZ TRADE OÜ</td>
            </tr>
        </table>
        <div style="position: absolute; bottom: 60px;font-size: 24px;font-family: Calibri, serif;">
            <div style="display: flex;justify-content: center;align-items: center;">
                <svg id="barcode"></svg>
                <b style="padding-right: 2px">{$item.TagID}</b>
                <img width="40px" height="28px" alt="CE" src="/templates/default/assets/dPatEnV7f_I.jpg">
                <img width="40px" height="40px" alt="CE" src="/templates/default/assets/rEmMzBJ5nks.jpg">
            </div>
        </div>
    </div>
</div>
</center>
<script>
    JsBarcode("#barcode", "{$item.TagID}", {
        height: 50,
        width: 1.8,
        displayValue: false

    });
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
    printDiv('printable')
</script>