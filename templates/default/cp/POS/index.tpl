{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    {if isset($success) and $success=='true'}
                        <div class="alert alert-success" role="alert">
                            Sale performed!
                        </div>
                    {/if}

                    <form class="text-left" style="padding-top: 10px;" method="POST" id="POScartForm">
                        {include file='cp/POS/saleModal.tpl'}
                        <input type="text" class="form-control inline-items" style="width: 20%; height: 42px;" name="searchTagID" id="searchtagid" placeholder="Search by ID" autofocus>
                        <input type="text" class="form-control inline-items" style="width: 59.8%; height: 42px;" name="searchName" id="searchname" placeholder="Search by name">
                        <input type="submit" formaction="/cp/POS/search.php" id='search' name="search" class="btn btn-outline-secondary inline-items" style="width: 20%; height: 42px;" value="Search">
                        {include file='cp/POS/searchModal.tpl'}
                        <div class="col-12">
                            <div class="d-inline-flex w-100">
                                <div class="col-10 form-group">

                                    <table class="table table-sm table-responsive">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th class="POStalble">Name</th>
                                            <th class="POStalble">Quantity</th>
                                            <th class="POStalble">Available</th>
                                            <th class="POStalble">Location</th>
                                            <th class="POStalble">Base price</th>
                                            <th class="POStalble">Total price</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="POScart">
                                            {* Some fucking magic happens here *}
                                        </tbody>
                                    </table>
                                    <h5 id="sum">Sum: {$cartTotal}</h5>
                                </div>
                                <div class="col-2 form-group">
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
        </div>
    </div>
</main>
<script src="/templates/default/assets/js/cart.js"></script>
{debug}
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
                    dataType: 'json',
                    data: {req: JSON.stringify(data)},
                    success: function(result){
                        console.log(result);
                    }
                });
            }
        });

        location.reload();
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
        if ($("#quantity"+item).val() > available){
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
        $("#searchname").keypress(function (e) {
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
        $("#searchtagid").keypress(function (e) {
            $.ajax({
                type     : "POST",
                cache    : false,
                url      :"/cp/POS/update.php",
                data     : $("#POScartForm").serialize()
            });
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                updateCart($("#searchtagid").val());
                document.getElementById("searchtagid").value = "";
                setTimeout(function(){ displayCart("POScart");sum();}, 1000);
                return false;
            }
        });
    });
</script>
{include file='footer.tpl'}
