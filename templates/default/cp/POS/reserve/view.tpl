{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">

                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">

                        {include file='cp/POS/reserve/reserveConfirmModal.tpl'}

                    <div class="col-12" style="display: inline-flex;">
                        <p style="margin-right: auto; margin-left: auto">
                            ID: {$reservation.id}<br>
                            Date: {$reservation.date}<br>
                            {$reservation.comment}
                        </p>
                    </div>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Tag</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $reservation.products as $prod}
                            <tr>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="select{$prod.id_product}" value="{$prod.id_product}">
                                        <label class="custom-control-label" for="select{$prod.id_product}"></label>
                                    </div>
                                </td>
                                <td class="td-20"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.tag}</a></td>
                                <td class="td-20"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id_product}">{$prod.name.et}</a></td>
                                <td>{$prod.quantity}</td>
                                <td>{$prod.price}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-success"
                                            onclick="confirmItem('{$prod.id_product}', '{$prod.price}', '{$prod.basePrice}', '{$prod.quantity}', '{$reservation.id}','{$prod.id_location}')">
                                        <i class="far fa-smile"></i>
                                        Confirm item
                                    </button>
                                    <a class="btn btn-outline-primary" href="/cp/POS/reserve/index.php?cancel={$reservation.id}&prodCancel={$prod.id}">
                                        <i class="fas fa-frown"></i>
                                        Cancel item
                                    </a>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-success" style="display: inline-block; float:left;"
                            onclick="confirmAll('{$reservation.id}')">
                        <i class="far fa-check-square"></i> Confirm All
                    </button>
                    <button type="button" class="btn btn-info ml-2" style="display: inline-block; float:left;"
                            onclick="confirmSelected('{$reservation.id}')">
                        <i class="far fa-check-square"></i> Confirm selected
                    </button>



                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS/reserve">
                        <i class="fas fa-undo-alt"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="inputs">

    </div>
</main>
<script src="/templates/default/assets/js/cart.js"></script>

<script>
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
        for (let k in products){
            if (document.getElementById("select"+products[k]['id_product']).checked) {
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
        urlPart = encodeURIComponent(JSON.stringify(filtered));
        let cash = $("#cash").val();
        let card = $("#card").val();
        let ostja = $("#ostja").val();
        let tellimuseNr = $("#tellimuseNr").val();
        let mode = $("#modeSelect option:selected").val();

        window.location.href = "/cp/POS/sale.php?reservation=true&cash="+cash+"&card="+card+"&ostja="+ostja+"&tellimuseNr="+tellimuseNr+"&mode="+mode+"&cart="+urlPart+"&id_res="+id_reservation;

    }

</script>
{include file='footer.tpl'}