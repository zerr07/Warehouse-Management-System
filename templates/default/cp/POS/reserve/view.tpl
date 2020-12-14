{include file='header.tpl'}
<script src="/templates/default/assets/js/print.min.js"></script>
<link rel="stylesheet" href="/templates/default/assets/css/print.min.css">

{include file='cp/POS/reserve/invoice.tpl'}
{include file='cp/POS/reserve/invoicePDF.tpl'}
<div class="row mt-3">
    <div class="col-md-12" >
        {include file='cp/POS/reserve/reserveConfirmModal.tpl'}
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-link p-0" style="color: gray; opacity: 0.1;" onclick="setWarning('{$reservation.id}')"><i class="fas fa-exclamation-triangle" style=" width: 64px; height: 64px"></i></button>
                        </div>


                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">ID: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.id}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Date: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.date}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Type: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.type_name}</div>

                        <div class="col-4 col-sm-6 col-md-6 col-lg-3">Comment: </div>
                        <div class="col-8 col-sm-6 col-md-6 col-lg-9">{$reservation.comment}</div>

                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-2">
                    <div class="row h-100">
                        <div class="col-12 col-sm-12 col-lg-6 d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-info w-100 h-100" data-toggle="modal" data-target="#invoiceModalPDF">
                                Get invoice PDF
                            </button>
                        </div>
                        <div class="col-12 col-sm-12  col-lg-6 d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-info w-100 h-100" data-toggle="modal" data-target="#invoiceModal">
                                Print invoice
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

            {foreach $reservation.products as $prod}
                <div class="row mt-3 border border-secondary p-1">
                    <div class="col-1 m-auto">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="select{$prod.id_product}" value="{$prod.id_product}">
                            <label class="custom-control-label" for="select{$prod.id_product}"></label>
                        </div>
                    </div>
                    {if $prod.tag == "Buffertoode"}
                        <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; ">{$prod.tag}</a>    </div>
                        <div class="col-8 col-sm-8 col-lg-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; ">{$prod.name}</a>   </div>
                    {else}
                        <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.tag}</a>       </div>
                        <div class="col-8 col-sm-8 col-lg-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.name.et}</a>   </div>
                    {/if}
                    <div class="col-4 col-sm-4 col-lg-1 m-auto d-flex justify-content-center">{$prod.quantity} pcs</div>
                    <div class="col-4 col-sm-4 col-lg-1 m-auto d-flex justify-content-center text-truncate">{$prod.price} €</div>
                    {if $prod.tag == "Buffertoode"}
                        <div class="col-4 col-sm-4 col-lg-1 m-auto d-flex justify-content-center"></div>
                    {else}
                        <div class="col-4 col-sm-4 col-lg-1 m-auto d-flex justify-content-center text-truncate" title="{$prod.location}">Loc: {$prod.location}</div>
                    {/if}
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 m-auto">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6">
                                <button type="button" class="btn btn-outline-success w-100"
                                        onclick="confirmItem('{$prod.id_product}', '{$prod.price}', '{$prod.basePrice}', '{$prod.quantity}', '{$reservation.id}','{$prod.id_location}')">
                                    <i class="far fa-smile"></i>
                                    Confirm item
                                </button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6">
                                <a class="btn btn-outline-danger w-100" href="/cp/POS/reserve/index.php?cancel={$reservation.id}&prodCancel={$prod.id}">
                                    <i class="fas fa-frown"></i>
                                    Cancel item
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            {/foreach}
        <div class="row mt-3">
            <div class="col">
                Total: {$sum}
            </div>
        </div>
        <div class="row mt-3">

            <div class="col-10 d-flex justify-content-start">
                <div class="row w-100">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-3">
                        <button type="button" class="btn btn-success w-100 h-100"
                                onclick="confirmAll('{$reservation.id}')">
                            <i class="far fa-check-square"></i> Confirm All
                        </button>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-3">
                        <button type="button" class="btn btn-primary w-100 h-100"
                                onclick="confirmSelected('{$reservation.id}')">
                            <i class="far fa-check-square"></i> Confirm selected
                        </button>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-3">
                        <button type="button" class="btn btn-info w-100 h-100"
                                onclick="window.location = '/cp/POS/reserve/loadReservationInCart.php?id={$reservation.id}'">
                            <i class="fas fa-download"></i> Load into POS
                        </button>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 mt-3">
                        <button type="button" class="btn btn-info w-100 h-100" onclick="convertToShippling('{$reservation.id}')">
                            <i class="fas fa-dolly"></i> Convert to shipping
                        </button>
                    </div>
                </div>



            </div>

            <div class="col-2 d-flex justify-content-end mt-3">
                <a class="btn btn-secondary mr-2" href="/cp/POS/reserve/edit.php?edit={$reservation.id}">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a class="btn btn-primary" href="/cp/POS/reserve">
                    <i class="fas fa-undo-alt"></i> Back
                </a>
            </div>
        </div>


    </div>
</div>
<div id="inputs">
</div>
<script src="/templates/default/assets/js/cart.js?t=16102020T165728"></script>
<script src="/templates/default/assets/js/warning.js?d=20201203T102437"></script>

<script>
    let product_arr = [
        {foreach $reservation.products as $prod}
            {if $prod.tag == "Buffertoode"}
                    ["{$prod.tag}", "{$prod.name}", "tk", "{$prod.quantity}", "{$prod.basePrice}"],
            {else}
                    ["{$prod.tag}", "{$prod.name.et}", "tk", "{$prod.quantity}", "{$prod.basePrice}"],
            {/if}
        {/foreach}
    ]
    window.addEventListener("load", function () {
        const requestParams = {
            method: "POST",
            headers: new Headers({
                "Content-Type": "application/json"
            }),
            body: JSON.stringify({
                getSingle: "{$reservation.id}",
            })
        };
        fetch("/cp/POS/reserve/addWarning.php", requestParams)
            .then(response => response.json())
            .then((d) => {

                Object.keys(d).forEach(el => {
                    enableWarning(document.querySelector("button[onclick=\"setWarning('"+el+"')\"]"), d[el].comment, d[el].user)
                });
            });
    })
    $('#modalOTHER').hide();
    $("select#modeSelect").change(function(){
        var val = $(this).children("option:selected").val();
        if (val === 'Bigshop'){
            $(this).css("background", "#009ac0");
            $(this).css("border-color", "#009ac0");
            $(this).css("color", "white");
            $('#modalSHOP').show();
            $('#modalOTHER').hide();
            document.getElementById('sale').disabled = true;
        } else if (val === "Osta") {
            $(this).css("background", "orange");
            $(this).css("border-color", "orange");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;
        } else if (val === "Minuvalik") {
            $(this).css("background", "greenyellow");
            $(this).css("border-color", "greenyellow");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;

        } else if (val === "Shoppa") {
            $(this).css("background", "coral");
            $(this).css("border-color", "coral");
            $(this).css("color", "black");
            $('#modalSHOP').hide();
            $('#modalOTHER').show();
            document.getElementById('sale').disabled = false;
        }
    });
    function convertToShippling(id){
        fetch("/cp/POS/shipping/convert.php?id="+id)
            .then(response => response.json())
            .then((d) => {
                window.location.href = "/cp/POS/shipping/index.php?view={$reservation.id}";
            });
    }
    function confirmProcess(id, price, basePrice, quantity, id_reservation, id_location) {
        let arr = {};
        arr[id] = {};
        arr[id].id = id;
        arr[id].price = price;
        arr[id].basePrice = basePrice;
        arr[id].quantity = quantity;
        arr[id].id_location = id_location;
        let urlPart = encodeURIComponent(JSON.stringify(arr));
        let cash = $("#cash").val();
        let card = $("#card").val();
        let ostja = $("#ostja").val();
        let tellimuseNr = $("#tellimuseNr").val();
        let mode = $("#modeSelect option:selected").val();
        window.location.href = "/cp/POS/sale.php?reservation=true&cash="+cash+"&card="+card+"&ostja="+ostja+"&tellimuseNr="+tellimuseNr+"&mode="+mode+"&cart="+urlPart+"&id_res="+id_reservation;
    }
    function confirmItem(id, price, basePrice, quantity, id_reservation, id_location) {
        document.getElementById('inputs').innerHTML = "";
        let quantityInput = "<input type='text' id='quantity1' name='quantity[]' value='"+quantity+"' hidden>";
        let priceInput = "<input type='text' id='price1' name='price[]' value='"+basePrice+"' hidden>";
        let totalPrice = "<table><td id='totalPrice1' hidden>"+price+"</td></table>";
        $("#inputs").append(quantityInput+priceInput+totalPrice);
        document.getElementById('sale').setAttribute("onclick","confirmProcess('"+id+"', '"+price+"', '"+basePrice+"', '"+quantity+"', '"+id_reservation+"', '"+id_location+"')");
        sum();
        $('#saleModal').modal('show');
    }

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
        let mode = $("#modeSelect option:selected").val();
        window.location.href = "/cp/POS/sale.php?reservation=true&cash="+cash+"&card="+card+"&ostja="+ostja+"&tellimuseNr="+tellimuseNr+"&mode="+mode+"&id_cart="+id_reservation;
    }

    function confirmSelected(id_reservation) {
        let products;
        $.ajax({
            type: "GET",
            cache: false,
            url: "/cp/POS/reserve/reserve.php?getReservationItemsJSON=" + id_reservation,
            success: function (result) {
                products = JSON.parse(result)['products'];
                document.getElementById('inputs').innerHTML = "";
                for ( let key in products ) {
                    if (document.getElementById("select"+products[key]['id_product']).checked){
                        let quantityInput = "<input type='text' id='quantity"+key+"' name='quantity[]' value='"+products[key]['quantity']+"' hidden>";
                        let priceInput = "<input type='text' id='price"+key+"' name='price[]' value='"+products[key]['basePrice']+"' hidden>";
                        let totalPrice = "<table><td id='totalPrice"+key+"' hidden>"+products[key]['price']+"</td></table>";
                        $("#inputs").append(quantityInput+priceInput+totalPrice);
                    }

                }
                document.getElementById('sale').setAttribute("onclick","confirmProcessSelected('"+id_reservation+"', '"+encodeURIComponent(JSON.stringify(products))+"')");
                sum();
                $('#saleModal').modal('show');
            }
        });
    }

    function confirmProcessSelected(id_reservation, p) {
        let arr = [];
        let urlPart;
        let products = JSON.parse(decodeURIComponent(p));
        for (let k in products) {
            if (document.getElementById("select" + products[k]['id_product']).checked) {
                arr[products[k]['id_product']] = {
                    id: products[k]['id_product'],
                    price: products[k]['price'],
                    basePrice: products[k]['basePrice'],
                    quantity: products[k]['quantity'],
                    id_location: products[k]['id_location']
                };
            }
        }
        const filtered = arr.filter(el => {
            return el != null && el !== '';
        });
        for (let k in products) {
            if (document.getElementById("select" + products[k]['id_product']).checked) {
                if (!isNumeric(products[k]['id_product'])) {
                    filtered.push({
                        id: products[k]['id_product'],
                        price: products[k]['price'],
                        basePrice: products[k]['basePrice'],
                        quantity: products[k]['quantity'],
                        id_location: products[k]['id_location']
                    });
                }
            }
        }
        urlPart = encodeURIComponent(JSON.stringify(filtered));
        let cash = $("#cash").val();
        let card = $("#card").val();
        let ostja = $("#ostja").val();
        let tellimuseNr = $("#tellimuseNr").val();
        let mode = $("#modeSelect option:selected").val();
        window.location.href = "/cp/POS/sale.php?reservation=true&cash="+cash+"&card="+card+"&ostja="+ostja+"&tellimuseNr="+tellimuseNr+"&mode="+mode+"&cart="+urlPart+"&id_res="+id_reservation;

    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
</script>
{include file='footer.tpl'}
