{include file='header.tpl'}

{include file='cp/WMS/item/EANModal.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">
                    <form action="/cp/WMS/" class="text-left" style="padding-top: 10px;" method="GET">
                        <div class="row">

                            <div class="col-3 px-0 pl-3">
                                <input type="text" class="form-control inline-items w-100" style="height: 42px;"
                                       name="searchTagID" id="form17" placeholder="Search by ID" autofocus>
                            </div>
                            <div class="col-6 px-0">
                                <input type="text" class="form-control inline-items w-100 " style="height: 42px;"
                                       name="searchName" id="form17" placeholder="Search by name"
                                        {if isset($searchName) && $searchName != ""}
                                            value="{$searchName}"
                                        {/if}
                                >
                            </div>
                            <div class="col-3 px-0 pr-3">
                                <input type="submit" name="search" class="btn btn-outline-secondary inline-items w-100"
                                       style="height: 42px;margin-bottom: 2px;" value="Search">
                            </div>
                        </div>

                    </form>
                    <div class="col-12" style="display: inline-flex;">
                        <div class="col-8" style="width: 79.9%;">

                            {$item.tag}
                            <h3>{$item.name.et}</h3>
                            <h3>{$item.name.ru}</h3>
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
                                    {foreach $item.reservations.reserved_list as $key => $value}
                                        <a href="/cp/POS/reserve/index.php?view={$value.id_reserved}">
                                            {$value.id_reserved} - {$value.comment} - {$value.quantity} pcs.
                                        </a>
                                    {/foreach}
                                </div>
                            </div>
                            {include file='cp/WMS/view/image_section.tpl'}
                            <a data-toggle="collapse" href="javascript:void(0);" role="button" data-target="#collapseImagesLive"
                               aria-expanded="false" aria-controls="collapseImagesLive">
                                Live Images
                            </a>
                            <div class="collapse" id="collapseImagesLive">
                                {include file='cp/WMS/view/image_live_section.tpl'}
                            </div>
                            {include file='cp/WMS/view/image_zoom.tpl'}

                            {if !empty($item.images) || !empty($item.images_live)}
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="button" style="width: 30%" class="btn btn-primary"
                                                onclick="getImages({$item.id})"><i class="far fa-images"></i> Download all images</button>
                                    </div>
                                </div>
                            {/if}
                            <div class="row mt-3">
                                <div class="col-12">
                                    {if $item.tag == ""}
                                        <a class="btn btn-primary disabled" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                                    {else}
                                        <a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                                    {/if}
                                    <button type="button" style="width: 30%" class="btn btn-primary" onclick="getCodes({$item.id})"
                                            data-toggle="modal" data-target="#linkEANModalBody"><i class="fas fa-edit"></i> EAN Codes</button>

                                </div>
                                <div class="col-12">
                                    <!-- Load c3.css -->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel="stylesheet" />
                                    <!--<link href="/templates/default/assets/css/c3.min.css" rel="stylesheet">-->

                                    <!-- Load d3.js and c3.js -->
                                    <!--<script src="/templates/default/assets/js/d3.min.js" charset="utf-8"></script>-->
                                    <!--<script src="/templates/default/assets/js/c3.min.js"></script>-->
                                    <script src="/templates/default/assets/js/moment.js"></script>

                                    <button type="button" class="btn btn-info" onclick="loadAuctionCharts()"
                                        ><i class="fas fa-ad"></i> View auction charts</button>
                                    <div id="auction_charts"></div>
                                </div>
                            </div>

                            <div style="margin-top: 15px;">
                                <form action="/cp/WMS/item/edit/addQuantity.php" method="GET">
                                    <select class="custom-select w-25" name="location">
                                        {foreach $item.locationList as $value}
                                            <option value="{$value.id}"
                                            {if $default_location_type===$value.id_type} selected{/if}
                                            >
                                                {$value.type_name} - {$value.location}
                                            </option>
                                        {/foreach}
                                    </select>
                                    <button type="submit" class="btn btn-primary" name="amount" value="plus1">
                                        +1
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="amount" value="plus3">
                                        +3
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="amount" value="plus5">
                                        +5
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="amount" value="plus10">
                                        +10
                                    </button>
                                    <button type="submit" class="btn btn-secondary" name="amount" value="minus1">
                                        -1
                                    </button>
                                    <input type="text" name="editSMT" value="{$item.id}" hidden>
                                </form>
                            </div>
                        </div>
                        <div class="col-3">
                            <h3 class="pt-2">Locations</h3>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $item.locationList as $loc}
                                        {assign var="id_type" value=$loc.id_type}
                                        <tr>
                                            <td>{$loc.location}</td>
                                            <td>{$loc.quantity}</td>
                                            <td>{$location_types.$id_type.name}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger" onclick="delete_loc({$loc.id})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $item.suppliers as $item}
                                <tr>
                                    <td class="td-20">{$item.supplierName}</td>
                                    <td class="td-20">{$item.price} zł</td>
                                    <td class="td-20">{$item.priceVAT} €</td>
                                    <td style="float: right;">
                                        <a class="btn btn-primary tooltip_copy" id='{$item.id}link' onclick="copyURL('#{$item.id}')" href="#" data-toggle="{$item.id}" title="Copied!">
                                            <i class="fas fa-copy" ></i>
                                            <p id='{$item.id}' hidden>{$item.URL}</p>
                                        </a>
                                        <a class="btn btn-outline-primary" href="{$item.URL}" >
                                            <i class="fas fa-link"></i>
                                            Go to
                                        </a>

                                        <form method="POST" action="/controllers/products/delete.php" onsubmit="return confirm('Do you really want to delete item?');" style="display: inline-block;">
                                            <button type="submit" class="btn btn-outline-primary" name="deleteSMTitemURL" value="{$item.id}"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>

                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Platform Name</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $platforms as $key => $value}
                                    <tr>
                                        <td>
                                            {if ($item.platforms.$key.export=== "1")}
                                                <i class="fas fa-file-export" style="color: green; font-size: 24px"></i>
                                            {else}
                                                <i class="fas fa-file-export" style="color: red; font-size: 24px"></i>
                                            {/if}
                                        </td>
                                        <td class="td-20">{$value.name}</td>
                                        <td class="td-20">{$item.platforms.$key.price} €</td>
                                        <td style="float: right;">
                                            <a class="btn btn-primary tooltip_copy {if !isset($item.platforms.$key.URL)}disabled{/if}"
                                               id='{$item.platforms.$key.id}link' onclick="copyURL('#{$item.platforms.$key.id}')" href="#" data-toggle="{$item.platforms.$key.id}" title="Copied!">
                                                <i class="fas fa-copy" ></i>
                                                <p id='{$item.platforms.$key.id}' hidden>{$item.platforms.$key.URL}</p>
                                            </a>
                                            <a class="btn btn-outline-primary {if !isset($item.platforms.$key.URL)}disabled{/if}"
                                               href="{$item.platforms.$key.URL}" target="_blank">
                                                <i class="fas fa-link"></i>
                                                Go to
                                            </a>

                                            <form method="POST" action="/controllers/products/delete.php" onsubmit="return confirm('Do you really want to delete item?');" style="display: inline-block;">
                                                <button type="submit" class="btn btn-outline-primary" name="deleteSMTitemPlatform" value="{$item.platforms.$key.id}"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                        </table>
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
                                    {foreach $sales as $sale}
                                        <a href="/cp/POS/sales/index.php?view={$sale.id_sale}">
                                            {$sale.saleDate} - {$sale.quantity} pcs.<br/>
                                        </a>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                            <div style="padding-top: 20px;">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabRUS">RUS</a> </li>
                                    <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabEST">EST</a> </li>
                                    <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabPL">PL</a> </li>
                                    <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabENG">ENG</a> </li>
                                    <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabLV">LV</a> </li>

                                </ul>
                                <div class="tab-content mt-2 mb-5">
                                    <div class="tab-pane fade active show tabTXT" id="tabRUS" role="tabpanel">
                                        {$item.descriptions.ru}
                                    </div>
                                    <div class="tab-pane fade ml-20 tabTXT" id="tabEST" role="tabpanel">
                                        {$item.descriptions.et}
                                    </div>
                                    <div class="tab-pane fade ml-20 tabTXT" id="tabPL" role="tabpanel">
                                        {$item.descriptions.pl}
                                    </div>
                                    <div class="tab-pane fade ml-20 tabTXT" id="tabENG" role="tabpanel">
                                        {$item.descriptions.en}
                                    </div>
                                    <div class="tab-pane fade ml-20 tabTXT" id="tabLV" role="tabpanel">
                                        {$item.descriptions.lv}
                                    </div>
                                </div>
                            </div>
                            <a href="/cp/WMS/item/edit/?edit={$item.id}" style="float: left;" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        <button type="button" class="btn btn-secondary ml-2" style="float: left" onclick="duplicate_product({$item.id})">Duplicate</button>


                        <a class="btn btn-primary ml-2" style="display: inline-block; float:right;" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
                        <form method="POST" action="/controllers/products/delete.php" onsubmit="return confirm('Do you really want to delete item?');" style="display: inline-block; float: right">
                            <button type="submit" class="btn btn-danger" name="delete" value="{$item.id}"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
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

        /*$.ajax({
            type: "GET",
            cache: false,
            url: "/controllers/products/getAllImages.php?id=" + index
        });*/

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
    $(".tooltip_copy").tooltip({
        trigger: 'click'
    });
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
    function loadAuctionCharts(){

        $.ajax({
            url: "/auctions_charts.html",
            cache: false,
            dataType: "html",
            success: function(data) {
                $("#auction_charts").html(data);
                if (typeof(worker) == "undefined") {
                    let tag = "{$item.tag}";
                    worker = new Worker("/templates/default/assets/js/auction_charts.js");
                    {literal}worker.postMessage(`{
                    "type":"tag",
                    "data":"`+tag+`"
                    }`);{/literal}
                    worker.onmessage = function(event) {
                        let msg = JSON.parse(event.data);
                        console.log(msg);
                        if (msg['type'] === 'toggleModal'){
                            $("#auction_charts_modal").modal("toggle");
                        }
                        if (msg['type'] === 'DrawChart1'){
                            setTimeout(() => chart1(tag, msg['data']), 1000);
                        }
                        if (msg['type'] === 'noChart1'){
                            document.getElementById("chart1").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                        }
                        if (msg['type'] === 'DrawChart2'){
                            setTimeout(() => chart2(tag, msg['data']), 1000);
                        }
                        if (msg['type'] === 'noChart2'){
                            document.getElementById("chart2").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                        }
                        if (msg['type'] === 'DrawChart3'){
                            setTimeout(() => chart3(tag, msg['data']), 1000);
                        }
                        if (msg['type'] === 'noChart3'){
                            document.getElementById("chart3").innerHTML = "No data ᕕ( ᐛ )ᕗ";
                        }
                        {literal}
                        document.getElementById("chart2-tab").addEventListener("click", function (){
                            worker.postMessage(`{"type":"loadChart2Data"}`);

                        });
                        document.getElementById("chart3-tab").addEventListener("click", function () {
                            worker.postMessage(`{"type":"loadChart3Data"}`);
                        });
                        {/literal}

                    };
                } else {
                    console.log("Web Workers are not supported in your browser");
                }
            }
        });


    }

</script>
{include file='footer.tpl'}