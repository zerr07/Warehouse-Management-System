{include file='header.tpl'}
{assign var="SHOP" value=1}
<form action="/cp/WMS/" class="text-left" method="GET"  id="SearchForm">

            <div class="row">
                <div class="col-md-4 col-sm-12 mt-3">
                    <a class="btn btn-primary w-100" href="/cp/WMS/item/add/"><i class="fas fa-plus"></i>&nbsp;Add item</a>
                </div>
                <div class="col-md-4 col-sm-12 mt-3">
                    <a class="btn btn-primary w-100" href="/cp/WMS/priceRule/"><i class="fas fa-money-bill-wave"></i>&nbsp;Add price rule</a>
                </div>
                <div class="col-md-4 col-sm-12 mt-3">
                    <a class="btn btn-primary w-100"  href="/cp/WMS/category/"><i class="fas fa-ellipsis-v"></i>&nbsp;Add/Edit category</a><br>
                </div>
                <div class="col-sm-12 mt-3">
                    <a class="btn btn-primary w-100" href="#" data-toggle="collapse" data-target="#moresettings" aria-expanded="false" aria-controls="multiCollapseExample2">
                        <i class="fas fa-filter"></i>&nbsp;Filter by category
                    </a>
                    <div class="collapse multi-collapse" id="moresettings" style="margin-top: 4px;">
                        <div class="card card-body" >
                            <div class="ml-2">
                                {include file='cp/WMS/category/tree/radio/tree.tpl'}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-2 mt-2">
                        <input type="text" class="form-control w-100" name="searchTagID" id="searchTagID" placeholder="Search by ID" autofocus>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mt-2">
                        <input type="text" class="form-control w-100" name="searchName" id="searchName" placeholder="Search by name or SKU" list="productDataList"
                                {if isset($searchName) && $searchName != ""}value="{$searchName}"{/if}>
                        <template id="productDataListTemplate"></template>
                        <datalist id="productDataList"></datalist>
                    </div>
                    <div class="col-sm-12 col-md-4 col-lg-3 text-center mt-2">
                        <label class="d-inline-flex">
                            <input type="radio" class="only" name="only" value="Full" {if isset($onlyFilter) && $onlyFilter == "Full"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/icons/p-GREEN.svg" data-toggle="tooltip" data-placement="top" title="Only green">
                        </label>
                        <label class="d-inline-flex">
                            <input type="radio" class="only" name="only" value="Partly" {if isset($onlyFilter) && $onlyFilter == "Partly"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/icons/p-YELLOW.svg" data-toggle="tooltip" data-placement="top" title="Only yellow">
                        </label>
                        <label class="d-inline-flex">
                            <input type="radio" class="only" name="only" value="No" {if isset($onlyFilter) && $onlyFilter == "No"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/icons/p-RED.svg" data-toggle="tooltip" data-placement="top" title="Only red">
                        </label>
                        <label class="d-inline-flex">
                            <input type="radio" class="only" name="only" value="NoFilter" {if isset($onlyFilter) && ($onlyFilter == "NoFilter" || $onlyfilter === "")}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/icons/p-WHITE.svg" data-toggle="tooltip" data-placement="top" title="No filter">
                        </label>
                    </div>
                    <div class="col-md-6 col-lg-2 mt-2 ">
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#moreOptions" aria-expanded="false" aria-controls="moreOptions">
                            More options
                        </button>
                    </div>
                    <div class="col-md-6 col-lg-2 mt-2 ">
                        <button type="button" name="search" class="btn btn-outline-secondary inline-items w-100" value="Search" id="SearchBtn">Search</button>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="collapse" id="moreOptions">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-4">
                                        <input type="text" class="form-control w-100" name="searchSupplierName" id="searchSupplierName" placeholder="Search by supplier name or domain"
                                               {if isset($searchSupplierName) && $searchSupplierName != ""}value="{$searchSupplierName}"{/if}
                                        >
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="quantitySearch" name="quantitySearch" {if isset($quantitySearch) && $quantitySearch == "on"}checked{/if}>
                                            <label class="custom-control-label d-flex justify-content-start" for="quantitySearch">Show only positive quantity</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-5">
                                        <div class="row">
                                            {foreach $platforms as $key => $value}
                                                <div class="col-5">
                                                    {$value.name}
                                                </div>
                                                <div class="col-7">
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="platformSearchOff{$key}" name="platformSearchOff[{$key}]"
                                                               {if isset($platformSearchOff)}{if array_key_exists($key, $platformSearchOff)}checked{/if}{/if}>
                                                        <label class="custom-control-label" for="platformSearchOff{$key}">Off</label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input"
                                                               id="platformSearchOn{$key}" name="platformSearchOn[{$key}]"
                                                               {if isset($platformSearchOff)}{if array_key_exists($key, $platformSearchOn)}checked{/if}{/if}>
                                                        <label class="custom-control-label" for="platformSearchOn{$key}">On</label>
                                                    </div>
                                                </div>

                                            {/foreach}
                                        </div>


                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
            </div>
</form>

<div class="row">
                <div class="col-md-12">

                    {if $products|@count == 0}
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center" style="margin-top: 50px;">
                                <p>Nothing Found</p>
                                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                            </div>
                        </div>
                    {else}


                        <div class="row mt-3">

                            {foreach $products as $item}
                                <div class="col-12 col-sm-12 col-lg-12 mt-3" style="min-height: 210px;">
                                    <div class="card mb-3 p-2 h-100">
                                        <div class="row no-gutters h-100">
                                            <div class="col-sm-3 d-flex align-items-center">
                                                {include file="cp/WMS/image_on_page.tpl"}
                                                {*<img src="..." class="card-img" alt="...">*}
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="card-body">
                                                    <h5 class="card-title text-truncate"><a style="text-overflow: ellipsis; " title="{$item.name.et|escape}" href="/cp/WMS/view/?view={$item.id}">{$item.name.et}</a>
                                                    </h5>
                                                    <a style="text-overflow: ellipsis; " href="/cp/WMS/view/?view={$item.id}">{$item.tag}</a>

                                                    <p class="card-text m-0">Shop price:
                                                        {if !isset($item.platforms.$SHOP.price) || $item.platforms.$SHOP.price == ""}Not set{else}{$item.platforms.$SHOP.price}{/if}</p>
                                                    <p class="card-text m-0">Quantity: {if $item.home_qty == ""}Not set{else}{$item.home_qty}{/if} (+{if $item.supp_qty == ""}Not set{else}{$item.supp_qty}{/if})</p>
                                                    <p class="card-text m-0">Locations: {if $item.locations|replace:" ":"" == ""}Not set{else}{$item.locations}{/if}</p>



                                                </div>
                                            </div>
                                            <div class="col-sm-3 btn-group-vertical">
                                                <a class="btn btn-outline-primary d-table" href="/cp/WMS/view/?view={$item.id}" ><i class="fas fa-link"></i>View</a>
                                                <a class="btn btn-outline-primary d-table" href="/cp/WMS/item/edit/?edit={$item.id}" ><i class="fas fa-edit"></i>Edit</a>
                                                {if $item.tag == ""}
                                                    <a class="btn btn-primary disabled d-table" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>
                                                {else}
                                                    <a class="btn btn-primary d-table" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>
                                                {/if}
                                                <button type="button" class="btn btn-outline-danger d-table p-0" onclick="deleteProduct('{$item.id}')"><i class="fas fa-trash"></i> Delete</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            {/foreach}
                        </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
<script>
    document.getElementById("SearchBtn").addEventListener("click", function () {
        let nameSearch = document.getElementById("searchName");
        let nameID = document.querySelector("datalist[id='productDataList'] > option[value='"+nameSearch.value+"']");
        if (nameID){
            window.location.href = "/cp/WMS/view/?view="+nameID.getAttribute("data-id");
        } else {
            document.getElementById("SearchForm").submit();
        }
    });
    $(function() {
        $("#searchName").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('#SearchBtn').click();
                return false;
            }
        });
    });
    $(function() {
        $("#searchTagID").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                $('#SearchBtn').click();
                return false;
            }
        });
    });
    let timeout = null;
    document.getElementById("searchName").addEventListener('keyup', function (e) {
        let datalist = document.getElementById("productDataList");
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            fetch("/controllers/cache.php?getProductDataList=1&getDataListStr="+document.getElementById("searchName").value)
                .then(response => response.json())
                .then((d) =>  {
                    console.log(d)
                    datalist.innerHTML = "";
                    Object.keys(d).forEach(k => {
                        let el = document.createElement("option");
                        el.setAttribute("value", d[k].trim());
                        el.setAttribute("data-id", k);
                        el.innerText = d[k].trim();
                        datalist.appendChild(el);
                    })
                });
        }, 100);
    });

    $('input[name=cat]').on('change', function() {
        $(this).closest("form").submit();
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    function copyURL(element) {
        $(element+"link").tooltip();
        setTimeout(function() {
            $(element+"link").tooltip('hide');
        }, 1000);
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).html()).select();
        document.execCommand("copy");
        $temp.remove();
    }

</script>
{include file='pagination.tpl'}
{include file='footer.tpl'}