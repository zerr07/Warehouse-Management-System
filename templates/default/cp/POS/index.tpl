{include file='header.tpl'}
<div class="row">
    <div class="col-md-12" style="white-space: nowrap;">
        {if isset($success) and $success=='true'}
            <div class="alert alert-success" role="alert">
                Sale performed!
            </div>
        {/if}
        <form class="text-left" style="padding-top: 10px;" method="POST" id="POScartForm">
            {include file='cp/POS/saleModal.tpl'}
            <div class="row">
                <div class="col-sm-12 col-md-4 mt-2">
                    <input type="text" class="form-control" name="searchTagIDPOS" id="searchtagidPOS" placeholder="Search by ID" autofocus>
                </div>
                <div class="col-sm-12 col-md-4 mt-2">
                    <input type="text" class="form-control" name="searchNamePOS" id="searchnamePOS" placeholder="Search by name">
                </div>
                <div class="col-sm-12 col-md-4 mt-2">
                    <input type="submit" formaction="/cp/POS/search.php" id='search' name="search" class="btn btn-outline-secondary w-100" value="Search">
                </div>
            </div>
            {include file='cp/POS/searchModal.tpl'}
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-9 form-group">
                        <div id="POScart">
                            {* Some fucking magic happens here *}
                        </div>

                        <h5 id="sum">Sum: {$cartTotal}</h5>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3  form-group">
                        <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#saleModal" style=" height: 5em; margin-top: 10px; display: block;">Perform sale</button>
                        <button type="submit" class="btn btn-info w-100" id="saveCart" name="update" formaction="/cp/POS/update.php" style="margin-top: 25px;">Save cart</button>
                        <a href="/cp/POS/search.php?clear=yes" class="btn btn-dark w-100"  style="margin-top: 10px; display: block;">Clear cart</a>
                        <a href="/cp/POS/sales" class="btn btn-light w-100"  style="margin-top: 10px; display: block;">Sales history</a>
                        <select class="custom-select mr-sm-2" id="modeSelect" name="mode"
                                style="height:42px;margin-top: 10px; display: block;
                                            background: #009ac0; border-color: #009ac0;color: white;">
                            <option value="Bigshop" selected>Shop</option>
                            <option value="Osta">Osta</option>
                            <option value="Minuvalik">Minuvalik</option>
                            <option value="Shoppa">Shoppa</option>
                        </select>
                        <input type="submit"  formaction="/cp/POS/buffer.php" name="addBuffer" class="btn btn-outline-success w-100" style="margin-top: 10px; display: block;" value="Add Buffertoode">
                        <button type="button" class="btn btn-outline-info w-100" style="margin-top: 10px; display: block;" onclick="reserveCart()">Reserve this cart</button>
                        <a href="/cp/POS/reserve" class="btn btn-info w-100"  style="margin-top: 10px; display: block;">Reserved carts</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="/templates/default/assets/js/cart.js?t=16102020T165705"></script>
<script src="/cp/POS/displayCartPOS.js?t=20102020T132023"></script>

<script src="/cp/POS/updateCart.js?t=20102020T145725"></script>
<script>
    {literal}
    function reserveCart(){
        let data = {reserve: "true", note: prompt("Reservation note: ")};
        if (data.note === null) {
            return; //prompt cancelled
        }
        $.ajax({
            type     : "POST",
            cache    : false,
            url      :"/cp/POS/update.php",
            data     : $("#POScartForm").serialize(),
            success: function (result) {
                $.ajax({
                    type     : "POST",
                    cache    : false,
                    url      :"/cp/POS/reserve/reserve.php",
                    data: {req: JSON.stringify(data)},
                    success: function(result){
                        location.reload();
                    },
                    error: function (req, res){
                        alert("Error!");
                    }
                });
            }
        });


    }
    {/literal}
    $(document).ready(function(){
        $('input#buffer').tooltip();
        displayCart("POScart");
        sum();
    });
    $(window).on('load', function () {

    });
    $('#modalOTHER').hide();
    const target = document.querySelector('body');

    function checkQuantity(available, item) {
        console.log(available)
        console.log(item)
        if ($("#quantity"+item).val() > parseInt(available)){
            alert("Item out of stock!");
        }
    }
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
    $(function() {
        $("#searchnamePOS").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('#search').click();
                if (($("#exampleModalScrollable").data('bs.modal') || {})._isShown === false){
                    //$('#exampleModalScrollable').modal('toggle');
                }
                //searchByName(document.getElementById("searchname").value);

                return false;
            }
        });
    });
    $(function() {
        $("#searchtagidPOS").keypress(function (e) {
            $.ajax({
                type     : "POST",
                cache    : false,
                url      :"/cp/POS/update.php",
                data     : $("#POScartForm").serialize()
            });
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                updateCart($("#searchtagidPOS").val());
                document.getElementById("searchtagidPOS").value = "";
                setTimeout(function(){ displayCart("POScart");sum();}, 1000);
                return false;
            }
        });
    });
</script>
{include file='footer.tpl'}
