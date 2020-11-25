{include file='header.tpl'}
<script src="/templates/default/assets/js/print.min.js"></script>
<link rel="stylesheet" href="/templates/default/assets/css/print.min.css">
{include file='cp/POS/reserve/invoice.tpl'}
<div class="row mt-3">
    <div class="col-md-12" >
        {include file='cp/POS/reserve/reserveConfirmModal.tpl'}
        {include file='cp/POS/shipping/dataInsertModal.tpl'}

        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="row">
                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">ID: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.id}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Date: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.date}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Type: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.type_name}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Status: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9" id="shippingType"></div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Comment: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.comment}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Additional info: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9" id="additionalInfo"></div>

                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-2">
                    <button type="button" class="btn btn-success w-100 h-100" id="markAsShipped" onclick="markAsShipped()" disabled>
                        Mark as shipped
                    </button>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-2">
                    <button type="button" class="btn btn-secondary w-100 h-100" data-toggle="modal" data-target="#dataInsertModal">
                        Manage shipping data
                    </button>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-info w-100 h-100" data-toggle="modal" data-target="#invoiceModal">
                        Print invoice
                    </button>
                </div>
            </div>
        </div>

        {foreach $reservation.products as $prod}
            <div class="row mt-3 border border-secondary p-1">
                {if $prod.tag == "Buffertoode"}
                    <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; ">{$prod.tag}</a>    </div>
                    <div class="col-9 col-sm-9 col-lg-4 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; ">{$prod.name}</a>   </div>
                {else}
                    <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.tag}</a>       </div>
                    <div class="col-8 col-sm-8 col-lg-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.name.et}</a>   </div>
                {/if}
                <div class="col-6 col-sm-6 col-lg-2 m-auto d-flex justify-content-center">{$prod.quantity} pcs</div>
                <div class="col-6 col-sm-6 col-lg-2 m-auto d-flex justify-content-center">{$prod.price} €</div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-3 m-auto">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                            <button type="button" class="btn btn-outline-danger w-100 cancelShipping" onclick="goToUrl('/cp/POS/reserve/index.php?cancel={$reservation.id}&prodCancel={$prod.id}')" disabled>
                                <i class="fas fa-frown"></i>
                                Cancel item
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        {/foreach}
        <div class="row mt-3">



            <div class="col-10 d-flex justify-content-start">
                <div class="row w-100">
                    <div class="col-12 col-sm-12 col-lg-3 mt-3">
                        <button type="button" class="btn btn-success w-100 h-100" id="checkoutShipment"
                                onclick="confirmAll('{$reservation.id}')" disabled>
                            <i class="far fa-check-square"></i> Checkout
                        </button>
                    </div>
                    <div class="col-12 col-md-12 col-lg-3 mt-3">
                        <a class="btn btn-info w-100"
                           href="/cp/POS/reserve/loadReservationInCart.php?id={$reservation.id}">
                            <i class="far fa-check-square"></i> Load into POS
                        </a>
                    </div>
                </div>



            </div>

            <div class="col-2 d-flex justify-content-end mt-3">
                <a class="btn btn-primary" href="/cp/POS/shipping">
                    <i class="fas fa-undo-alt"></i> Back
                </a>
            </div>
        </div>


    </div>
</div>
<div id="inputs">
</div>
<script src="/templates/default/assets/js/cart.js?t=16102020T165728"></script>

<script>
    $(window).on("load", function () {
        setShippingStatus();
    })

    function setShippingStatus(){
        fetch("/cp/POS/shipping/getShippingStatus.php?idJSON={$reservation.id}")
            .then(response => response.json())
            .then((d) => {
                if (d.id === "1" || d.id === "2"){
                    document.getElementById("checkoutShipment").disabled = false;
                    document.getElementById("markAsShipped").disabled = false;
                    document.querySelectorAll(".cancelShipping").forEach(a => {
                        a.disabled = false;
                    });
                } else if (d.id === "3" || d.id === "4"){
                    document.getElementById("markAsShipped").disabled = false;
                    document.getElementById("checkoutShipment").disabled = true;
                    document.querySelectorAll(".cancelShipping").forEach(a => {
                        a.parentNode.removeChild(a);
                    });
                } else if (d.id === "5"){
                    document.getElementById("markAsShipped").disabled = true;
                    document.getElementById("checkoutShipment").disabled = false;
                    document.querySelectorAll(".cancelShipping").forEach(a => {
                        a.parentNode.removeChild(a);
                    });
                } else if (d.id === "6"){
                    document.getElementById("markAsShipped").disabled = true;
                    document.getElementById("checkoutShipment").disabled = true;
                    document.querySelectorAll(".cancelShipping").forEach(a => {
                        a.parentNode.removeChild(a);
                    });
                }

                fetch("/cp/POS/shipping/getShippingStatus.php?type_idJSON={$reservation.id}")
                    .then(response => response.json())
                    .then((r) => {
                        document.getElementById("shippingType").innerText = d.name + "(" + r.name + ")";
                    });
            });
    }
    $('#modalOTHER').hide();
    $("select#modeSelect").change(function(){
        var val = $(this).children("option:selected").val();
        if (val == 'Bigshop'){
            $(this).css("background", "#009ac0");
            $(this).css("border-color", "#009ac0");
            $(this).css("color", "white");
            $('#modalSHOP').show();
            $('#modalOTHER').hide();
            document.getElementById('sale').disabled = true;
        } else if (val == "Osta") {
            $(this).css("background", "orange");
            $(this).css("border-color", "orange");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;
        } else if (val == "Minuvalik") {
            $(this).css("background", "greenyellow");
            $(this).css("border-color", "greenyellow");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;

        } else if (val == "Shoppa") {
            $(this).css("background", "coral");
            $(this).css("border-color", "coral");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;
        }
    });

    function confirmAll(id_reservation) {
        let products;
        $.ajax({
            type: "GET",
            cache: false,
            url: "/cp/POS/reserve/reserve.php?getReservationItemsJSON=" + id_reservation,
            success: function (result) {
                products = JSON.parse(result)['products'];
                document.getElementById('inputs').innerHTML = "";
                for ( let key in products ) {
                    let quantityInput = "<input type='text' id='quantity"+key+"' name='quantity[]' value='"+products[key]['quantity']+"' hidden>";
                    let priceInput = "<input type='text' id='price"+key+"' name='price[]' value='"+products[key]['basePrice']+"' hidden>";
                    let totalPrice = "<table><td id='totalPrice"+key+"' hidden>"+products[key]['price']+"</td></table>";
                    $("#inputs").append(quantityInput+priceInput+totalPrice);
                }
                document.getElementById('sale').setAttribute("onclick","confirmProcessAll('"+id_reservation+"')");
                sum();
                $('#saleModal').modal('show');
            }
        });
    }
    function confirmProcessAll(id_reservation) {
        let cash = $("#cash").val();
        let card = $("#card").val();
        let ostja = $("#ostja").val();
        let tellimuseNr = $("#tellimuseNr").val();
        let shipmentID = "{$reservation.id}";
        let mode = $("#modeSelect option:selected").val();
        window.location.href = "/cp/POS/sale.php?reservation=true&shipment=true&cash="+cash+"&card="+card+"&ostja="+ostja+"&tellimuseNr="+tellimuseNr+"&mode="+mode+"&id_cart="+id_reservation+"&shipmentID="+shipmentID;
    }

    function markAsShipped(){
        fetch("/cp/POS/shipping/getShippingData.php?setSmartpostPosted={$reservation.id}").finally(function (){
            setShippingStatus();
        });

    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
</script>
{include file='footer.tpl'}