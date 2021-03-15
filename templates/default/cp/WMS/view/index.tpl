{include file='header.tpl'}

{include file='cp/WMS/item/EANModal.tpl'}

        <div class="row">
            <form action="/cp/WMS/" class="text-left w-100 form-inline" style="padding-top: 10px;" method="GET">
                <div class="col-sm-12 col-md-2 mt-2">
                    <input type="text" class="form-control w-100" name="searchTagID" id="form17" placeholder="Search by ID" autofocus>
                </div>
                <div class="col-sm-12 col-md-6 mt-2">
                    <input type="text" class="form-control w-100" name="searchName" id="form17" placeholder="Search by name"
                            {if isset($searchName) && $searchName != ""}
                        value="{$searchName}"
                            {/if}>
                </div>
                <div class="col-sm-12 col-md-4 mt-2 ">
                    <input type="submit" name="search" class="btn btn-outline-secondary inline-items w-100" value="Search">
                </div>
            </form>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12 col-md-4 col-lg-3">
                {include file='cp/WMS/view/image_section.tpl'}
                <a data-toggle="collapse" href="javascript:void(0);" role="button" data-target="#collapseImagesLive"
                   aria-expanded="false" aria-controls="collapseImagesLive" class="d-flex justify-content-center">
                    Live Images
                </a>
                <div class="collapse" id="collapseImagesLive">
                    {include file='cp/WMS/view/image_live_section.tpl'}
                </div>
                {include file='cp/WMS/view/image_zoom.tpl'}
                {if !empty($item.images) || !empty($item.images_live)}

                    <button type="button" class="btn btn-primary btn-block"
                            onclick="getImages({$item.id})"><i class="far fa-images"></i> Download all images</button>
                {/if}
            </div>
            <div class="col-sm-12 col-md-8 col-lg-6">
        <span id="SKU" class="d-flex justify-content-center">
            {$item.tag}
        </span>
                <h4>{$item.name.et}</h4>
                <h5>{$item.name.ru}</h5>
                <p>Category: {$item.category_name}</p>
                <p>Actual price - {$item.actPrice} <i class="fas fa-euro-sign"></i></p>
                <p>Quantity : {$item.quantity} (+ {$item.reservations.reserved_sum} from
                    <a data-toggle="collapse" href="#collapseReservations" role="button"
                       aria-expanded="false" aria-controls="collapseReservations">
                        reservations
                    </a>
                    )</p>
                <div class="collapse" id="collapseReservations">
                    <div class="card card-body">
                        {if isset($item.reservations.reserved_list)}
                            {foreach $item.reservations.reserved_list as $key => $value}
                                <a href="/cp/POS/reserve/index.php?view={$value.id_reserved}">
                                    {$value.id_reserved} - {$value.comment} - {$value.quantity} pcs.
                                </a>
                            {/foreach}
                        {/if}
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3">
                <hr class="d-none d-sm-flex d-md-flex d-lg-none">
                <h3 class="pt-2">Locations</h3>
                {foreach $item.locationList as $loc}
                    {assign var="id_type" value=$loc.id_type}
                    <div class="row">
                        <div class="col-3 m-auto">{$loc.location}</div>
                        <div class="col-2 m-auto">{$loc.quantity}</div>
                        <div class="col-3 m-auto">{$location_types.$id_type.name}</div>
                        <div class="col-4 m-auto">
                            <button type="button" class="btn btn-danger w-100" onclick="delete_loc({$loc.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                {/foreach}
                <hr class="d-none d-sm-flex d-md-flex d-lg-none">
            </div>
            <div class="col-sm-12 col-md-12 offset-lg-9 col-lg-3">
                <hr class="d-none d-sm-flex d-md-flex d-lg-none">
                <h3 class="pt-2">Properties</h3>
                {foreach $item.properties  as $prop}
                    <div class="row">
                        {assign var=key1 value = $prop.prop_name|@key}
                        {assign var=key2 value = $prop.value_name|@key}
                        <div class="col-auto m-auto">{$prop.prop_name.$key1.name}</div>
                        <div class="col-auto m-auto">{$prop.value_name.$key2.name}</div>
                    </div>
                {/foreach}
                <hr class="d-none d-sm-flex d-md-flex d-lg-none">
            </div>
            <div class="col-sm-12 mt-3">
                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                    {if $item.tag == ""}
                        <a class="btn btn-secondary disabled" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape:'url'}&tag={$item.tag}">Print label</a>
                    {else}
                        <a class="btn btn-secondary" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape:'url'}&tag={$item.tag}">Print label</a>
                    {/if}
                    <button type="button" class="btn btn-primary" onclick="getCodes({$item.id})"
                            data-toggle="modal" data-target="#linkEANModalBody"><i class="fas fa-edit"></i> EAN Codes</button>
                    <!-- Load c3.css -->
                    <script src="/templates/default/assets/js/d3.min.js?t=16102020T165340"></script>
                    <script src="/templates/default/assets/js/c3.min.js?t=16102020T165341"></script>
                    <link href="/templates/default/assets/css/c3.min.css?t=16102020T165344" rel="stylesheet" />

                    <script src="/templates/default/assets/js/moment.js"></script>
                    <button type="button" class="btn btn-info" onclick="loadAuctionCharts('{$item.tag}')"
                    ><i class="fas fa-ad"></i> View auction charts</button>
                    <div id="auction_charts"></div>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <form action="/cp/WMS/item/edit/addQuantity.php" method="GET">

                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                        <select class="custom-select w-25" name="location">
                            {foreach $item.locationList as $value}
                                <option value="{$value.id}"
                                        {if $default_location_type===$value.id_type} selected{/if}
                                >
                                    {$value.type_name} - {$value.location}
                                </option>
                            {/foreach}
                        </select>
                        <button type="submit" class="btn btn-success" name="amount" value="plus1">
                            +1
                        </button>
                        <button type="submit" class="btn btn-warning" name="amount" value="plus3">
                            +3
                        </button>
                        <button type="submit" class="btn btn-primary" name="amount" value="plus5">
                            +5
                        </button>
                        <button type="submit" class="btn btn-info" name="amount" value="plus10">
                            +10
                        </button>
                        <button type="submit" class="btn btn-secondary" name="amount" value="minus1">
                            -1
                        </button>
                    </div>
                    <input type="text" name="editSMT" value="{$item.id}" hidden>
                </form>
            </div>
            <div class="col-sm-12">
                <div class="accordion mt-2" id="accordion">
                    <div class="card bg-transparent">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                        data-target="#collapsePlatforms" aria-expanded="true" aria-controls="collapsePlatforms">
                                    Platforms
                                </button>
                            </h2>
                        </div>
                        <div id="collapsePlatforms" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="row mb-2">
                                <div class="col-12 col-sm-10 offset-0 offset-sm-1">
                                    <small>Suppliers</small>
                                    {foreach $item.suppliers as $item}
                                        <div class="row mt-3 border border-secondary p-2">
                                            <div class="col-4 col-lg-3 m-auto">
                                                {$item.supplierName}
                                            </div>
                                            <div class="col-4 col-lg-2 m-auto">
                                                {$item.price} zł
                                            </div>
                                            <div class="col-4 col-lg-2 m-auto">
                                                {$item.priceVAT} €
                                            </div>
                                            <div class="col-12 col-lg-5 pt-2 pt-lg-0">
                                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                                    <a class="btn btn-primary tooltip_copy" id='{$item.id}link'
                                                       onclick="copyURL('#{$item.id}')" href="#" data-toggle="{$item.id}" title="Copied!">
                                                        <i class="fas fa-copy" ></i>
                                                        <p id='{$item.id}' hidden>{$item.URL}</p>
                                                    </a>
                                                    <a class="btn btn-outline-primary" href="{$item.URL}" >
                                                        <i class="fas fa-link"></i>
                                                        Go to
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger d-table"
                                                            onclick="deleteSMTitemURL('{$item.id}')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                    <hr style="border-color: #516362;">
                                    <small>Platforms</small>
                                    {foreach $platforms as $key => $value}
                                        <div class="row mt-3 border border-secondary p-2">
                                            <div class="col-4 col-lg-3 m-auto">
                                                {if isset($item.platforms.$key.export) && ($item.platforms.$key.export=== "1")}
                                                    <i class="fas fa-file-export" style="color: green; font-size: 24px"></i>
                                                {else}
                                                    <i class="fas fa-file-export" style="color: red; font-size: 24px"></i>
                                                {/if}
                                            </div>
                                            <div class="col-4 col-lg-2 m-auto">
                                                {$value.name}
                                            </div>
                                            <div class="col-4 col-lg-2 m-auto">
                                                {if isset($item.platforms.$key.price)}
                                                    {$item.platforms.$key.price} €
                                                {/if}

                                            </div>
                                            <div class="col-12 col-lg-5 pt-2 pt-lg-0">
                                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">


                                                    {if isset($item.platforms.$key.id) && isset($item.platforms.$key.URL)}
                                                    <a class="btn btn-primary tooltip_copy"
                                                       id='{$item.platforms.$key.id}link' onclick="copyURL('#{$item.platforms.$key.id}');return false;" data-toggle="{$item.platforms.$key.id}" href="#"  title="Copied!">
                                                        <i class="fas fa-copy" ></i>
                                                        <p id='{$item.platforms.$key.id}' hidden>{$item.platforms.$key.URL}</p>
                                                        {else}
                                                        <a class="btn btn-primary tooltip_copy disabled"
                                                           href="#">
                                                            <i class="fas fa-copy" ></i>
                                                            {/if}


                                                        </a>
                                                        <a class="btn btn-outline-primary {if !isset($item.platforms.$key.URL)}disabled{/if}"
                                                           href="{if isset($item.platforms.$key.URL)}{$item.platforms.$key.URL}{/if}" target="_blank">
                                                            <i class="fas fa-link"></i>
                                                            Go to
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger d-table"
                                                                onclick="deleteSMTitemPlatform('{$item.platforms.$key.id}')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card bg-transparent">
                        <div class="card-header" id="headingSales">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapseSales" aria-expanded="false" aria-controls="collapseSales">
                                    Last 50 sales
                                </button>
                            </h2>
                        </div>
                        <div id="collapseSales" class="collapse" aria-labelledby="headingSales" data-parent="#accordion">
                            <div class="row">
                                <div class="col-12 m-2">
                                    {foreach $sales as $sale}
                                        <a href="/cp/POS/sales/index.php?view={$sale.id_sale}">
                                            {$sale.saleDate} - {$sale.quantity} pcs.<br/>
                                        </a>
                                    {/foreach}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row d-none d-sm-none d-md-flex mt-3">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabRUS">RUS</a>
                            <a href="" class="nav-link" data-toggle="tab" data-target="#tabEST">EST</a>
                            <a href="" class="nav-link" data-toggle="tab" data-target="#tabPL">PL</a>
                            <a href="" class="nav-link" data-toggle="tab" data-target="#tabENG">ENG</a>
                            <a href="" class="nav-link" data-toggle="tab" data-target="#tabLV">LV</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade active show tabTXT" id="tabRUS" role="tabpanel">
                                {if isset($item.descriptions.ru)}
                                    {$item.descriptions.ru}
                                {/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabEST" role="tabpanel">
                                {if isset($item.descriptions.et)}
                                    {$item.descriptions.et}
                                {/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabPL" role="tabpanel">
                                {if isset($item.descriptions.pl)}
                                    {$item.descriptions.pl}
                                {/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabENG" role="tabpanel">
                                {if isset($item.descriptions.en)}
                                    {$item.descriptions.en}
                                {/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabLV" role="tabpanel">
                                {if isset($item.descriptions.lv)}
                                    {$item.descriptions.lv}
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex d-md-none m-auto">
                    <div class="col-12 mt-3">
                        <ul class="nav nav-pills nav-fill">
                            <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabRUS">RUS</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabEST">EST</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabPL">PL</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabENG">ENG</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabLV">LV</a> </li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="tab-content mt-2 mb-5">
                            <div class="tab-pane fade active show tabTXT" id="tabRUS" role="tabpanel">
                                {if isset($item.descriptions.ru)}{$item.descriptions.ru}{/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabEST" role="tabpanel">
                                {if isset($item.descriptions.et)}{$item.descriptions.et}{/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabPL" role="tabpanel">
                                {if isset($item.descriptions.pl)}{$item.descriptions.pl}{/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabENG" role="tabpanel">
                                {if isset($item.descriptions.en)}{$item.descriptions.en}{/if}
                            </div>
                            <div class="tab-pane fade tabTXT" id="tabLV" role="tabpanel">
                                {if isset($item.descriptions.lv)}{$item.descriptions.lv}{/if}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 col-md-3 d-flex justify-content-start">
                        <a href="/cp/WMS/item/edit/?edit={$item.id}" class="btn btn-primary d-inline-flex"><i class="fas fa-edit"></i> Edit</a>
                        <button type="button" class="btn btn-secondary d-inline-flex ml-2" onclick="duplicate_product({$item.id})">Duplicate</button>
                    </div>
                    <div class="col-6 col-md-3 offset-md-6 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger d-inline-flex" onclick="deleteProduct('{$item.id}')"><i class="fas fa-trash"></i> Delete</button>
                        <a class="btn btn-primary d-inline-flex ml-2" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script src="/cp/WMS/item/edit/editEAN.js?t=16102020T165358"></script>
<script src="/templates/default/assets/js/auction_charts_init.js?d=20201112T103709"></script>

<script>


    function deleteSMTitemPlatform(id){
        var r = confirm("Do you really want to delete item?");
        if (r === true) {
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/products/delete.php?deleteSMTitemPlatform="+id
            });
            location.reload();
        } else {
            return;
        }

    }
    function deleteSMTitemURL(id){
        var r = confirm("Do you really want to delete item?");
        if (r === true) {
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/products/delete.php?deleteSMTitemURL="+id
            });
            location.reload();
        } else {
            return;
        }

    }
    function duplicate_product(index) {
        if (confirm('Do you really want to duplicate product?')){
            $.ajax({
                type: "GET",
                cache: false,
                url: "/controllers/products/duplicate_product.php?id=" + index
            });
            window.location = "/cp/WMS/";
        }
    }
    function getImages(index){
        window.location = "/controllers/products/getAllImages.php?id=" + index;
    }
    function delete_loc(index){
        if (confirm('Do you really want to delete location? Supplied quantity will be lost!')){
            $.ajax({
                type: "GET",
                cache: false,
                url: "/controllers/products/deleteLoc.php?id=" + index
            });
            location.reload();
        }
    }
    $('input[type=radio]').on('change', function() {
        $(this).closest("form").submit();
    });
    window.addEventListener("load", async function () {
        setPageTitle("{$item.tag}");

    })
    function copyURL(element) {
        var el = $(element+"link");
        el.tooltip();
        setTimeout(function() {
            el.tooltip('hide');
        }, 1000);
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).html()).select();
        document.execCommand("copy");
        $temp.remove();
    }
    $(".tooltip_copy").tooltip({
        trigger: 'click'
    });


    
</script>
{include file='footer.tpl'}