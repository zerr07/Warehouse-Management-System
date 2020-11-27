{include file='header.tpl'}

<div class="row">
    <div class="col-md-12" style="white-space: nowrap;">
            <div class="row mt-4">
                <div class="col-10 col-sm-10 col-md-3">
                    <input type="text" class="form-control" id="salesSearch" list="salesSearchList" placeholder="Arve Nr or Clients name"><div class='' id='salesSearchFeedback'></div>
                    <datalist id="salesSearchList">
                        {foreach $SalesHistoryDatalist as $key => $value}
                            <option value="{$value}" data-id="{$key}">{$value}</option>
                        {/foreach}
                    </datalist>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-primary" onclick="goToSale()">Go to</button>
                </div>
                <div class="col-12 col-sm-12 col-md-3 offset-md-4">
                    <form action="#" class="text-left" method="GET">
                        <select class="custom-select" id="modeSelect" name="mode" onchange="this.form.submit()"
                                style="height: 42px;">
                            <option value="All" {if $modeSearch=='All'}selected{/if}>All</option>
                            <option value="Bigshop" {if $modeSearch=='Bigshop'}selected{/if}>Shop</option>
                            <option value="Osta" {if $modeSearch=='Osta'}selected{/if}>Osta</option>
                            <option value="Minuvalik" {if $modeSearch=='Minuvalik'}selected{/if}>Minuvalik</option>
                            <option value="Shoppa" {if $modeSearch=='Shoppa'}selected{/if}>Shoppa</option>
                        </select>
                    </form>
                </div>
            </div>
        {if $sales|@count == 0}
            <div class="row">
                <div class="col-md-12" style="margin-top: 50px;">
                    <p>Nothing Found</p>
                    <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                </div>
            </div>
        {else}

            {foreach $sales as $item}
                <div class="row mt-3 border border-secondary p-1">
                    <div class="col-6   col-sm-6    col-md-2 col-lg-2 col-xl-1 m-auto d-flex justify-content-center">{include file='cp/POS/sales/badges.tpl'}</div>
                    <div class="col-6   col-sm-6    col-md-2 col-lg-2 col-xl-2 m-auto d-flex justify-content-center"><a class="btn btn-outline-primary" href="/cp/POS/sales/index.php?view={$item.id}" >{$item.arveNr}</a></div>
                    <div class="col-6   col-sm-6    col-md-3 col-lg-3 col-xl-3 m-auto d-flex justify-content-center text-truncate"><span>{$item.date}</span></div>
                    <div class="col-6   col-sm-6    col-md-1 col-lg-1 col-xl-1 m-auto d-flex justify-content-center text-truncate"><span>{$item.status}</span></div>
                    <div class="col-6   col-sm-6    col-md-2 col-lg-2 col-xl-1 m-auto d-flex justify-content-center text-truncate"><span>{$item.ostja}</span></div>
                    <div class="col-6   col-sm-6    col-md-2 col-lg-2 col-xl-1 m-auto d-flex justify-content-center text-truncate"><span>{$item.sum}€</span></div>
                    <div class="col-12  col-sm-12   col-md-12 col-lg-12 col-xl-3 m-auto d-flex justify-content-center">
                        <div class="row w-100">
                            <div class="col-6 p-0">
                                <a class="btn btn-outline-primary w-100" href="/cp/POS/sales/index.php?view={$item.id}" >
                                    <i class="fas fa-link"></i>
                                    View
                                </a>
                            </div>
                            <div class="col-6 p-0">
                                <a class="btn btn-outline-danger w-100" href="/cp/POS/sales/index.php?{$item.tagastusFull}">
                                    <i class="fas fa-frown"></i>
                                    Tagastus
                                </a>
                            </div>

                        </div>


                    </div>
                </div>
            {/foreach}
        {/if}
        <div class="row mt-3">

            <div class="col-12 d-flex justify-content-end">
                <a class="btn btn-primary d-inline-flex ml-2" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>

<script>
    function goToSale(){
        let saleSearch   = document.getElementById("salesSearch");
        saleSearch.setAttribute("class", "form-control");
        let salesSearchFeedback   = document.getElementById("salesSearchFeedback");
        let saleID = document.querySelector("datalist[id='salesSearchList'] > option[value='"+saleSearch.value+"']");
        if (saleID){
            window.location.href = "/cp/POS/sales/index.php?view="+saleID.getAttribute("data-id");
        } else {
            saleSearch.setAttribute("class", "form-control is-invalid");
            salesSearchFeedback.setAttribute("class", "invalid-feedback");
            salesSearchFeedback.innerText = "Invalid data provided!";
        }
    }
    window.onload = function (){
        let select = $("select#modeSelect");
        var val = select.children("option:selected").val();
        if (val == 'All'){
            select.css("background", "white");
            select.css("border-color", "white");
            select.css("color", "black");
        } else if (val == 'Bigshop'){
            select.css("background", "#009ac0");
            select.css("border-color", "#009ac0");
            select.css("color", "white");
        } else if (val == "Osta") {
            select.css("background", "orange");
            select.css("border-color", "orange");
            select.css("color", "black");
        } else if (val == "Minuvalik") {
            select.css("background", "greenyellow");
            select.css("border-color", "greenyellow");
            select.css("color", "black");
        } else if (val == "Shoppa") {
            select.css("background", "coral");
            select.css("border-color", "coral");
            select.css("color", "black");
        }
    }
    $("select#modeSelect").change(function(){

    });
</script>
{include file='pagination.tpl'}
{include file='footer.tpl'}