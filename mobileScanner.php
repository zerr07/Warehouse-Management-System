<?php
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mobile Scanner</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/templates/default/assets/js/cookie.js"></script>
    <script type="text/javascript" src="api/quagga/dist/quagga.min.js"></script>
    <link rel="stylesheet" href="/templates/default/assets/css/bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="/templates/default/assets/js/fontawesome-all.js"></script>
    <style>
        .container-fluid {
            height: 100vh;
            width: 100vw;
            max-height: 100vh;
            max-width: 100vw;
            overflow: auto;
        }
        .btnQuantity {
            width: 18.5%;
            margin-right: 10px;
            height: 100px;
            font-size: xxx-large;
            font-size: -webkit-xxx-large;
        }
        .btnScan {
            width: 48%;
            margin-top: 100px;
            margin-right: 10px;
            height: 100px;
            font-size: xx-large;
            font-size: -webkit-xx-large;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-xl-nowrap">
        <div class="col-12">
            <div class="col-12" id="productBox">

            </div>
            <!-- Product Scanner Modal -->
            <div id="livestream_scanner" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="livestream_scannerLabel" aria-hidden="true" bis_skin_checked="1">
                <div class="modal-dialog" role="document" bis_skin_checked="1">
                    <div class="modal-content" bis_skin_checked="1">
                        <div class="modal-header" bis_skin_checked="1">
                            <h5 class="modal-title" id="livestream_scannerLabel">Scanner</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" bis_skin_checked="1">
                            <div id="interactive" class="viewport"></div>
                            <div class="error"></div>
                        </div>
                        <div class="modal-footer" bis_skin_checked="1">
                            <button type="button" class="btn btn-lg btn-secondary w-100" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of product scanner -->
        </div>
    </div>
    <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#livestream_scanner" style="height: 250px;position: fixed;
    bottom: 0;
    left: 0;" onclick="setScannerState('product')">
        <span style="font-size: xxx-large;font-size: -webkit-xxx-large;"><i class="fa fa-barcode"></i> Scan</span>
    </button>
</div>
<style>
    #interactive.viewport {position: relative; width: 100%; height: auto; overflow: hidden; text-align: center;}
    #interactive.viewport > canvas, #interactive.viewport > video {max-width: 100%;width: 100%;}
    canvas.drawing, canvas.drawingBuffer {position: absolute; left: 0; top: 0;}
</style>
<script type="text/javascript">
    let product = [];
    let productData;
    let scannerState;
    function setScannerState(state){
        scannerState = state;
    }

    function loadProduct(barcode) {
        product = $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/getAJAXproduct.php?barcode="+barcode
        });
        productData = JSON.parse(product.responseText);
        buildProductBox(productData);
    }
    function buildProductBox(data){
        $("#productBox").html(
        "<div class='jumbotron'>" +
            "<div class='row'>" +
            "<div class='col-3'>" +
            "<img class='img-fluid' src='/uploads/images/products/"+data['mainImage']+"'>" +
            "</div>" +
            "<div class='col-9'>" +
            "<h1 class='display-4'>"+ data['name']['et'] +"</h1>" +
            "</div></div>"+
            "<p class='lead display-4'>Tag: " + data['tag'] + "</p>"+
            "<p class='lead display-4'>Location: " + data['locations'] + "</p>"+
            "<p class='lead display-4'>Quantity: " + data['quantity'] + "</p>"+
            "<select class=\"custom-select w-100\" name=\"location\" id='location'>"+
            "</select>"+
            "<button type=\"button\" class=\"btn btn-primary btnQuantity\"   onclick=\"changeQuantity('plus1',      "+ data['id'] +")\">+1</button>" +
            "<button type=\"button\" class=\"btn btn-primary btnQuantity\"   onclick=\"changeQuantity('plus3',      "+ data['id'] +")\">+3</button>" +
            "<button type=\"button\" class=\"btn btn-primary btnQuantity\"   onclick=\"changeQuantity('plus5',      "+ data['id'] +")\">+5</button>" +
            "<button type=\"button\" class=\"btn btn-primary btnQuantity\"   onclick=\"changeQuantity('plus10',     "+ data['id'] +")\">+10</button>" +
            "<button type=\"button\" class=\"btn btn-secondary btnQuantity\" onclick=\"changeQuantity('minus1',     "+ data['id'] +")\">-1</button>" +

            "<button type=\"button\" class=\"btn btn-primary btnScan\"   onclick=\"inputNewLocation("+ data['id'] +")\">Manual Location</button>" +
            "<button type=\"button\" class=\"btn btn-primary btnScan\"   onclick=\"setScannerState('location')\"" +
            " data-toggle=\"modal\" data-target=\"#livestream_scanner\">Scan new location</button>" +

            "</div>");
        let options = "";
        let def = getCookie("default_location_type");
        for (let c in data['locationList']){
            if (def === data['locationList'][c]['id_type']){
                options += "<option value='"+data['locationList'][c]['id']+"' selected>";
            } else {
                options += "<option value='"+data['locationList'][c]['id']+"'>";
            }
            options +=data['locationList'][c]['type_name']+" - "+
                data['locationList'][c]['location']+" - "+
                data['locationList'][c]['quantity']+
                "</option>";
        }
        $("#location").html(options);
    }
    function inputNewLocation(id) {
        let newLoc = prompt("Enter new location: ", productData['locations']);
        if (newLoc == null || newLoc == ""){
        } else {
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/products/setLocation.php?id="+id+"&location="+newLoc
            });

        }
        getProductDataByID(id);
        buildProductBox(productData);
    }
    function scanNewLocation(id, scanResult) {
        if (confirm("Set location to " + scanResult + "?")) {
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/products/setLocation.php?id="+id+"&location="+scanResult
            });
        }
        getProductDataByID(id);
        buildProductBox(productData);
    }
    function getProductDataByID(id) {
        product = $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/getAJAXproduct.php?id="+id
        });
        productData = JSON.parse(product.responseText);
    }
    function changeQuantity(type, id){
        let id_location = $("#location").find(":selected").val();
        $.ajax({
            dataType: "text",
            async: false,
            url: "/cp/SupplierManageTool/item/edit/addQuantity.php?editSMT="+id+"&amount="+type+"&location="+id_location
        });
        getProductDataByID(id);
        buildProductBox(productData);
    }
    $(function() {
        // Create the QuaggaJS config object for the live stream
        var liveStreamConfig = {
            inputStream: {
                type : "LiveStream",
                constraints: {
                    width: {min: 640},
                    height: {min: 480},
                    aspectRatio: {min: 1, max: 100},
                    facingMode: "environment" // or "user" for the front camera
                }
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
            decoder: {
                "readers":[
                    {"format":"code_128_reader","config":{}},
                    {"format":"ean_reader","config":{}},
                    {"format":"ean_8_reader","config":{}},
                    {"format":"code_39_reader","config":{}},
                    {"format":"code_39_vin_reader","config":{}},
                    {"format":"codabar_reader","config":{}},
                    {"format":"upc_reader","config":{}},
                    {"format":"upc_e_reader","config":{}},
                    {"format":"i2of5_reader","config":{}},
                    {"format":"2of5_reader","config":{}},
                    {"format":"code_93_reader","config":{}}
                ]
            },
            locate: true
        };
        // The fallback to the file API requires a different inputStream option.
        // The rest is the same
        var fileConfig = $.extend(
            {},
            liveStreamConfig,
            {
                inputStream: {
                    size: 800
                }
            }
        );
        // Start the live stream scanner when the modal opens
        $('#livestream_scanner').on('shown.bs.modal', function (e) {
            Quagga.init(
                liveStreamConfig,
                function(err) {
                    if (err) {
                        $('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><i class="fa fa-exclamation-triangle"></i> '+err.name+'</strong>: '+err.message+'</div>');
                        Quagga.stop();
                        return;
                    }
                    Quagga.start();
                }
            );
        });

        // Make sure, QuaggaJS draws frames an lines around possible
        // barcodes on the live stream
        Quagga.onProcessed(function(result) {
            var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;

            if (result) {
                if (result.boxes) {
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function (box) {
                        return box !== result.box;
                    }).forEach(function (box) {
                        Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                    });
                }

                if (result.box) {
                    Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
                }

                if (result.codeResult && result.codeResult.code) {
                    Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
                }
            }
        });

        // Once a barcode had been read successfully, stop quagga and
        // close the modal after a second to let the user notice where
        // the barcode had actually been found.
        Quagga.onDetected(function(result) {
            if (result.codeResult.code){
                if (scannerState === 'product'){
                    loadProduct(result.codeResult.code);
                } else if (scannerState === 'location'){
                    scanNewLocation(productData['id'], result.codeResult.code);
                }


                Quagga.stop();
                setTimeout(function(){ $('#livestream_scanner').modal('hide'); }, 1000);
            }
        });

        // Stop quagga in any case, when the modal is closed
        $('#livestream_scanner').on('hide.bs.modal', function(){
            if (Quagga){
                Quagga.stop();
            }
        });

        // Call Quagga.decodeSingle() for every file selected in the
        // file input
        $("#livestream_scanner input:file").on("change", function(e) {
            if (e.target.files && e.target.files.length) {
                Quagga.decodeSingle($.extend({}, fileConfig, {src: URL.createObjectURL(e.target.files[0])}), function(result) {alert(result.codeResult.code);});
            }
        });
    });
</script>

</body>
</html>