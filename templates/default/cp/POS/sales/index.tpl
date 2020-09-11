{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    <div class="row">
                        <div class="col-sm-12 col-md-9">
                            <form action="#" class="text-left" style="padding-top: 10px;" method="POST">
                                <input type="text" class="form-control inline-items w-75" style="width: 20%; height: 42px;" name="searchArve" id="form17" placeholder="Search by Arve nr" autofocus>
                                <input type="submit" name="search" class="btn btn-outline-secondary inline-items w-25" style="width: 20%; height: 42px;" value="Search">
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <form action="#" class="text-left" style="padding-top: 10px;" method="GET">
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

                        <div class="table-responsive" >
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>Arve Nr</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Ostja</th>
                                    <th>SUM</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $sales as $item}
                                    <tr>
                                        <td>{include file='cp/POS/sales/badges.tpl'}<a class="btn btn-outline-primary" href="/cp/POS/sales/index.php?view={$item.id}" >{$item.arveNr}</a></td>
                                        <td>{$item.date}</td>
                                        <td>{$item.status}</td>
                                        <td>{$item.ostja}</td>
                                        <td>{$item.sum}</td>
                                        <td>
                                            {*<a class="btn btn-primary tooltip_copy" id='{$item.id}link' onclick="copyURL('#{$item.id}')" href="#" data-toggle="{$item.id}" title="Copied!">
                                                <i class="fas fa-copy" ></i>
                                                <p id='{$item.id}' hidden>{$item.URL}</p>
                                            </a>
                                            <a class="btn btn-outline-primary" href="{$item.URL}" >
                                                <i class="fas fa-link"></i>
                                                Go to
                                            </a>*}
                                            <a class="btn btn-outline-primary" href="/cp/POS/sales/index.php?view={$item.id}" >
                                                <i class="fas fa-link"></i>
                                                View
                                            </a>
                                            <a class="btn btn-outline-danger" href="/cp/POS/sales/index.php?{$item.tagastusFull}">
                                                <i class="fas fa-frown"></i>
                                                Tagastus
                                            </a>

                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>
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