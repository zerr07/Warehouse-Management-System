{include file='header.tpl'}
<div class="row">
    <div class="col-md-12 mt-3" style="white-space: nowrap;">
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
                    <input type="text" class="form-control" name="searchNamePOS" id="searchnamePOS" placeholder="Search by name" list="productDataList">
                    <template id="productDataListTemplate"></template>
                    <datalist id="productDataList"></datalist>
                </div>
                <div class="col-sm-12 col-md-4 mt-2">
                    <input type="button" formaction="/cp/POS/search.php" id='search' name="search" class="btn btn-outline-secondary w-100" value="Search">
                </div>
            </div>
            {include file='cp/POS/searchModal.tpl'}
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 form-group">
                        <div id="POScart">
                            {* Some fucking magic happens here *}
                        </div>

                        <h5 id="sum">Sum: {$cartTotal}</h5>
                    </div>
                    {*<div class="col-12 col-sm-12 col-md-3  form-group">
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
                    </div>*}
                    <div class="col-12 col-sm-12 col-md-12 col-lg-3  form-group">
                        <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#saleModal" style=" height: 5em; margin-top: 10px; display: block;" id="performSale">Perform sale</button>
                        <select class="custom-select mr-sm-2" id="modeSelect" name="mode"
                                style="height:42px;margin-top: 10px; display: block;
                                            background: #009ac0; border-color: #009ac0;color: white;">
                            <option value="Bigshop" selected>Shop</option>
                            <option value="Osta">Osta</option>
                            <option value="Minuvalik">Minuvalik</option>
                            <option value="Shoppa">Shoppa</option>
                        </select>
                        <div class="row">
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="javascript:void(0)" onclick="saveCart()">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/diskette.svg" data-toggle="tooltip" data-placement="top" title="Save cart">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="javascript:void(0)" onclick="clearCart()">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/rubber.svg" data-toggle="tooltip" data-placement="top" title="Clear cart">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="/cp/POS/sales">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/history.svg" data-toggle="tooltip" data-placement="top" title="Sales history">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="javascript:void(0)" onclick="addBuffer()">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/buffer.svg" data-toggle="tooltip" data-placement="top" title="Add Buffertoode">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="javascript:void(0)" onclick="reserveCart('1')">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/online-booking.svg" data-toggle="tooltip" data-placement="top" title="Reserve this cart">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="/cp/POS/reserve">
                                <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/reserved.svg" data-toggle="tooltip" data-placement="top" title="Reserved carts">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="javascript:void(0)" onclick="reserveCart('2')">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/cargo-box.svg" data-toggle="tooltip" data-placement="top" title="Add shipping">
                                </a>
                            </div>
                            <div class="col-3 col-sm-3 col-md-3 col-lg-6 col-xl-4">
                                <a href="/cp/POS/shipping">
                                    <img class="p-3 my-3 POS-btn" src="/templates/default/assets/icons/box.svg" data-toggle="tooltip" data-placement="top" title="Shipping list">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="/templates/default/assets/js/cart.js?t=16102020T165705"></script>
<script src="/cp/POS/displayCartPOS.js?t=20201125T122410"></script>

<script src="/cp/POS/updateCart.js?t=20102020T145725"></script>
<script>
    {literal}
    function clearCart(){
        let r = confirm("Press a button!");
        if (r === true) {
            window.location = "/cp/POS/search.php?clear=yes";
        }
    }
    function saveCart() {
        let form = document.getElementById("POScartForm");
        let prev_act = form.action;
        form.action = "/cp/POS/update.php";
        form.submit();
        form.action = prev_act;
    }
    function addBuffer() {
        let form = document.getElementById("POScartForm");
        let prev_act = form.action;
        form.action = "/cp/POS/buffer.php";
        form.submit();
        form.action = prev_act;
    }
    function reserveCart(type){
        let data = null;
        if (type === "2"){
            data = {reserve: "true", note: prompt("Shipping note: ")};
        } else {
            data = {reserve: "true", note: prompt("Reservation note: ")};
        }
        if (data.note === null) {
            return; //prompt cancelled
        }


        $.ajax({
            type     : "POST",
            cache    : false,
            url      :"/cp/POS/update.php",
            data     : $("#POScartForm").serialize(),
            success: function (result) {
                const requestOptions = {
                    method: "POST",
                    headers:  new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        req: JSON.stringify(data)
                    })
                };
                fetch("/cp/POS/reserve/reserve.php?type="+type, requestOptions)
                    .then(response => response.json())
                    .then(async (d) => {
                        if (type === "1"){
                            location.reload();
                        } else if (type === "2"){
                            window.location.href = "/cp/POS/shipping/index.php?view="+d.id;
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
        setPageTitle("POS");
        $('[data-toggle="tooltip"]').tooltip();
        fetch("/controllers/products/get_products.php?getDataList=true")
        .then(response => response.json())
        .then((d) => {
            let datalist = document.getElementById("productDataListTemplate");
            Object.keys(d).forEach(k => {
                let el = document.createElement("option");
                el.setAttribute("value", d[k]);
                el.setAttribute("data-id", k);
                el.innerText = d[k];
                datalist.appendChild(el);
            })
        }).finally(function () {
            LimitDataList(document.getElementById("searchnamePOS"),
                document.getElementById("productDataList"),
                document.getElementById("productDataListTemplate"), 5);
        });
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
    document.getElementById("search").addEventListener('click', function () {
        let nameSearch   = document.getElementById("searchnamePOS");
        let nameID = document.querySelector("datalist[id='productDataList'] > option[value='"+nameSearch.value+"']");
        if (nameID){
            updateByID(nameID.getAttribute("data-id"));
        } else {
            let form = document.getElementById("POScartForm");
            form.action = "/cp/POS/search.php";
            form.submit();
            searchByName(nameSearch.value);
        }
        document.getElementById("searchnamePOS").value = "";
        setTimeout(function(){ displayCart("POScart");sum();}, 1000);
        return false;
    });

    $(function() {
        $("#searchnamePOS").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('#search').click();
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
