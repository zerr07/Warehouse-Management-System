{include file='header.tpl'}

<div class="row">
    <div class="col-md-12" style="white-space: nowrap;">
        <div class="row">
            <div class="col-sm-12 col-md-9 mt-3">
                <form action="#" class="text-left" method="POST">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-9">
                            <input type="text" class="form-control" name="searchArve" id="form17" placeholder="Search by Arve nr" autofocus>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3">
                            <input type="submit" name="search" class="btn btn-outline-secondary w-100" value="Search">
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-md-3 mt-3">
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
                    <div class="col-6   col-sm-6    col-md-4 col-lg-4 col-xl-4 m-auto d-flex justify-content-center">{include file='cp/POS/sales/badges.tpl'}</div>
                    <div class="col-6   col-sm-6    col-md-4 col-lg-4 col-xl-4 m-auto d-flex justify-content-center"><a class="btn btn-outline-primary" href="/cp/POS/sales/index.php?view={$item.id}" >{$item.arveNr}</a></div>
                    <div class="col-6   col-sm-6    col-md-4 col-lg-4 col-xl-4 m-auto d-flex justify-content-center"><span>{$item.date}</span></div>
                    <div class="col-6   col-sm-6    col-md-3 col-lg-3 col-xl-3 m-auto d-flex justify-content-center"><span>{$item.status}</span></div>
                    <div class="col-6   col-sm-6    col-md-3 col-lg-3 col-xl-3 m-auto d-flex justify-content-center"><span>{$item.ostja}</span></div>
                    <div class="col-6   col-sm-6    col-md-3 col-lg-3 col-xl-3 m-auto d-flex justify-content-center"><span>{$item.sum}€</span></div>
                    <div class="col-12  col-sm-12   col-md-3 col-lg-3 col-xl-3 m-auto d-flex justify-content-center">
                        <a class="btn btn-outline-primary" href="/cp/POS/sales/index.php?view={$item.id}" >
                            <i class="fas fa-link"></i>
                            View
                        </a>
                        <a class="btn btn-outline-danger" href="/cp/POS/sales/index.php?{$item.tagastusFull}">
                            <i class="fas fa-frown"></i>
                            Tagastus
                        </a>
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