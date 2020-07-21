{include file='header.tpl'}

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
                            <p>Quantity - {$item.quantity}</p>
                            {foreach $item.images as $image}
                                {if $image!=null}
                                    {if $image.primary == True}
                                        <img class="img-fluid" src="/uploads/images/products/{$image.image}" width="140px" >
                                    {else}
                                        <img class="img-fluid" src="/uploads/images/products/{$image.image}" width="70px" >
                                    {/if}
                                {/if}
                            {/foreach}
                            {if $item.tag == ""}
                                <a class="btn btn-primary disabled" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                            {else}
                                <a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                            {/if}

                            <div style="margin-top: 15px;">
                                <a href="/cp/WMS/item/edit/addQuantity.php?editSMT={$item.id}&ammount=plus1" class="btn btn-primary">+1</a>
                                <a href="/cp/WMS/item/edit/addQuantity.php?editSMT={$item.id}&ammount=plus3" class="btn btn-primary">+3</a>
                                <a href="/cp/WMS/item/edit/addQuantity.php?editSMT={$item.id}&ammount=plus5" class="btn btn-primary">+5</a>
                                <a href="/cp/WMS/item/edit/addQuantity.php?editSMT={$item.id}&ammount=plus10" class="btn btn-primary">+10</a>
                                <a href="/cp/WMS/item/edit/addQuantity.php?editSMT={$item.id}&ammount=minus1" class="btn btn-secondary">-1</a>
                            </div>
                        </div>
                        <div class="col-3">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th style="padding: 0 !important;"><h3>Locations</h3></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $item.locationList as $loc}
                                        <tr>
                                            <td>{$loc}</td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                </div>
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
                                    <th>Platform Name</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $platforms as $key => $value}
                                    <tr>
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
                            <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
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
</script>
{include file='footer.tpl'}