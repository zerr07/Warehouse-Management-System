<?php
include_once($_SERVER["DOCUMENT_ROOT"]).'/controllers/checkLogin.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manual Mobile Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="/templates/default/assets/js/script.js"></script>
    <script type="text/javascript" src="api/quagga/dist/quagga.min.js"></script>
    <link rel="stylesheet" href="/templates/default/assets/css/default/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="/templates/default/assets/js/fontawesome-all.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <style>


        html {
            -webkit-text-size-adjust: none
        }
        .container-fluid {
            height: 100vh;
            width: 100vw;
            max-height: 100vh;
            max-width: 100vw;
            overflow: auto;
        }
        .btnQuantity {
            /*width: 18.5%;
            margin-right: 2%;
            height: 7vh;
            font-size: 3vh;*/
        }
        .btnScan {
            width: 100%;
            margin-top: 3vh;
            margin-right: 10px;
            height: 7vh;
            font-size: 3vh;
        }

    </style>
</head>
<body>
<div class="container-fluid h-100 min-vh-100">
    <div class="row flex-xl-nowrap" style="min-height: 75vh !important;">
        <div class="col-12">
            <div class="col-12" id="productBox">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <input type="text" id="tagbox" class="form-control-lg w-100 inputTag" placeholder="Product tag" onfocus="setScannerState('product')">
        </div>
        <div class="col-12">
            <button type="button" class="btn btn-primary w-100" style="height: 18vh;" onclick="scanProduct()">
                <span style="font-size: xxx-large;font-size: -webkit-xxx-large;"><i class="fa fa-barcode"></i> Scan</span>
            </button>
        </div>

    </div>
    <!-- location Scanner Modal -->
    <div id="location_scanner" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="location_scannerLabel" aria-hidden="true" bis_skin_checked="1">
        <div class="modal-dialog" role="document" bis_skin_checked="1">
            <div class="modal-content" bis_skin_checked="1">
                <div class="modal-header" bis_skin_checked="1">
                    <h5 class="modal-title" id="location_scannerLabel">Scanner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" bis_skin_checked="1">
                    <input type="text" id="scanLocationBox" class="form-control-lg w-100" placeholder="New Location" onfocus="setScannerState('location')">
                    <button type="button" class="btn btn-lg btn-primary w-100"><span style="font-size: xxx-large;font-size: -webkit-xxx-large;">
                        <i class="fa fa-barcode"></i> Submit</span>
                    </button>
                </div>
                <div class="modal-footer" bis_skin_checked="1">
                    <button type="button" class="btn btn-lg btn-secondary w-100" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of product scanner -->
    <!-- location delete Modal -->
    <div id="location_delete" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="location_deleteLabel" aria-hidden="true" bis_skin_checked="1">
        <div class="modal-dialog" role="document" bis_skin_checked="1">
            <div class="modal-content" bis_skin_checked="1">
                <div class="modal-header" bis_skin_checked="1">
                    <h5 class="modal-title" id="location_deleteLabel">Delete locations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center" bis_skin_checked="1" id="locationDeleteList">

                </div>
                <div class="modal-footer" bis_skin_checked="1">
                    <button type="button" class="btn btn-lg btn-secondary w-100" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of location delete -->
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
    let tagbox = $("#tagbox");
    let locbox = $("#scanLocationBox");
    let locdeletebox = $("#location_delete");
    function setScannerState(state){
        scannerState = state;
    }
    tagbox.on('change', function() {
        setScannerState('product');
        scanProduct();
    });
    locbox.on('change', function() {
        setScannerState('location');
        scanNewLocation(productData['id'], locbox.val());
    });
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            if (scannerState === "product"){
                scanProduct();
            } else if (scannerState === "location"){
                //scanNewLocation(productData['id'], locbox.val());
            }

        }
    });
    function scanProduct(){
        let productTag = tagbox.val();
        tagbox.val("");

        if (productTag == null || productTag == ""){
            tagbox.focus();
        } else {
            loadProduct(productTag);
        }
    }
    $('#location_scanner').on('shown.bs.modal', function (e) {
        locbox.val("");
        locbox.focus();
    });


    function loadProduct(barcode) {
        product = $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/getAJAXproduct.php?barcode="+barcode
        });
        productData = JSON.parse(product.responseText);
        buildProductBox(productData);
    }
    function delete_loc(index, id_product){
        if (confirm('Do you really want to delete location? Supplied quantity will be lost!')){
            $.ajax({
                type: "GET",
                cache: false,
                url: "/controllers/products/deleteLoc.php?id=" + index
            });
            getProductDataByID(id_product);
            buildProductBox(productData);
        }
    }
    function buildProductBox(data){
        if (data === null){
            $("#productBox").html("");
            $("#locationDeleteList").html("");
        } else {
            let locations = "";
            for (i in productData['locationList']){
                locations +=
                    "<div class='row'>" +
                    "<div class='col-12'><span class='align-middle' style='font-size: 5em;'>" + data['locationList'][i]['location']+"</span>"+
                    " <button type=\"button\" class=\"btn btn-lg btn-danger\" onclick=\"delete_loc("+i+", "+data['id']+")\" style='font-size: 3rem'>"+
                    "<i class=\"fas fa-trash\"></i>"+
                    "</button>" +
                    "</div>" +
                    "</div>";
            }
            $("#locationDeleteList").html(locations);
            $("#productBox").html(
                "<div class='jumbotron'>" +
                "<div class='row'>" +
                "<div class='col-3 col-sm-3 col-md-3 col-lg-3'>" +
                "<img class='img-fluid' style='min-height: 3vh;max-height: 18vh;min-width: 3vh' src='/uploads/images/products/"+data['mainImage']+"'>" +
                "</div>" +
                "<div class='col-9 col-sm-9 col-md-9 col-lg-6'>" +
                "<h1 style='font-size: 3vh'>"+ data['name']['et'] +"</h1>" +
                "</div>" +
                "<div class='col-12 col-sm-12 col-md-12 col-lg-3'>" +
                "<p class='lead' style='font-size: 3vh'>Tag: " + data['tag'] + "</p>"+
                "<p class='lead' style='font-size: 3vh'>Location: " + data['locations'] + "</p>"+
                "<p class='lead' style='font-size: 3vh'>Quantity: " + data['quantity'] + "</p>" +
                "</div>"+
                "</div>"+

                "<select class=\"custom-select w-100\" name=\"location\" id='location'>"+
                "</select>"+
                "<div class='row mt-2'>" +
                "<div class='col-3 col-sm-3 col-md-2'> <button type=\"button\" class=\"btn btn-primary btnQuantity w-100\"   onclick=\"changeQuantity('plus1',      "+ data['id'] +")\">+1</button></div>" +
                "<div class='col-3 col-sm-3 col-md-2'> <button type=\"button\" class=\"btn btn-primary btnQuantity w-100\"   onclick=\"changeQuantity('plus3',      "+ data['id'] +")\">+3</button></div>" +
                "<div class='col-3 col-sm-3 col-md-2'> <button type=\"button\" class=\"btn btn-primary btnQuantity w-100\"   onclick=\"changeQuantity('plus5',      "+ data['id'] +")\">+5</button></div>" +
                "<div class='col-3 col-sm-3 col-md-3'> <button type=\"button\" class=\"btn btn-primary btnQuantity w-100\"   onclick=\"changeQuantity('plus10',     "+ data['id'] +")\">+10</button></div>" +
                "<div class='col-12 col-sm-12 col-md-3 mt-2 mt-sm-2 mt-md-0'> <button type=\"button\" class=\"btn btn-warning btnQuantity w-100\" onclick=\"changeQuantity('minus1',     "+ data['id'] +")\">-1</button></div>" +
                "</div>" +
                "<button type=\"button\" class=\"btn btn-primary btnScan\"   onclick=\"setScannerState('location');locbox.focus();\"" +
                " data-toggle=\"modal\" data-target=\"#location_scanner\">Scan new location</button>" +
                "<button type=\"button\" class=\"btn btn-primary btnScan\"" +
                " data-toggle=\"modal\" data-target=\"#location_delete\">Delete location</button>" +
                "</div>");
        }
        let options = "";
        let def = getCookie("default_location_type");
        console.log(data)
        for (let c in data['locationList']){
            if (def === data['locationList'][c]['id_type']){
                options += "<option value='"+data['locationList'][c]['id']+"' selected>";
            } else {
                options += "<option value='"+data['locationList'][c]['id']+"'>";
            }
            options +=data['locationList'][c]['type_name']+" : "+
                data['locationList'][c]['location']+" : "+
                data['locationList'][c]['quantity']+
                "</option>";
        }
        $("#location").html(options);
    }
    function inputNewLocation(id) {
        let newLoc = prompt("Enter new location: ", "");
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
        $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/products/setLocation.php?id="+id+"&location="+scanResult
        });
        getProductDataByID(id);
        buildProductBox(productData);
        $('#location_scanner').modal('hide')
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
    document.addEventListener('touchmove', function(event) {
        event = event.originalEvent || event;
        if(event.scale > 1) {
            event.preventDefault();
        }
    }, false);
</script>

</body>
</html>