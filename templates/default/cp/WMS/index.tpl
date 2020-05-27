{include file='header.tpl'}
{assign var="SHOP" value=1}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    <a class="btn btn-primary inline-items" style="width: 33.2%;" href="/cp/WMS/item/add/"><i class="fas fa-plus"></i>&nbsp;Add item</a>
                    <a class="btn btn-primary inline-items" style="width: 33.2%;" href="/cp/WMS/priceRule/"><i class="fas fa-money-bill-wave"></i>&nbsp;Add price rule</a>
                    <a class="btn btn-primary inline-items" style="width: 33.2%;" href="/cp/WMS/category/"><i class="fas fa-ellipsis-v"></i>&nbsp;Add/Edit category</a><br>
                    <a class="btn btn-primary" style="width: 100%;margin-top: 4px;" href="#" data-toggle="collapse" data-target="#moresettings" aria-expanded="false" aria-controls="multiCollapseExample2">
                        <i class="fas fa-filter"></i>&nbsp;Filter by category
                    </a>
                    <div class="collapse multi-collapse" id="moresettings" style="margin-top: 4px;">
                        <div class="card card-body" >
                            <form action="/cp/WMS/" class="text-left" style="margin-left: 10px;" method="POST">
                                {include file='cp/WMS/category/tree.tpl'}
                            </form>
                        </div>
                    </div>
                    <form action="/cp/WMS/" class="text-left" style="padding-top: 10px;" method="GET">
                        <input type="text" class="form-control inline-items" style="width: 20%; height: 42px;" name="searchTagID" id="form17" placeholder="Search by ID" autofocus>
                        <input type="text" class="form-control inline-items" style="width: 46.7%; height: 42px;" name="searchName" id="form17" placeholder="Search by name"
                        {if isset($searchName) && $searchName != ""}
                            value="{$searchName}"
                        {/if}>

                        <label>
                            <input type="radio" class="only" name="only" value="Full" {if $onlyFilter == "Full"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/p-GREEN.svg" data-toggle="tooltip" data-placement="top" title="Only green">
                        </label>
                        <label>
                            <input type="radio" class="only" name="only" value="Partly" {if $onlyFilter == "Partly"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/p-YELLOW.svg" data-toggle="tooltip" data-placement="top" title="Only yellow">
                        </label>
                        <label>
                            <input type="radio" class="only" name="only" value="No" {if $onlyFilter == "No"}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/p-RED.svg" data-toggle="tooltip" data-placement="top" title="Only red">
                        </label>
                        <label>
                            <input type="radio" class="only" name="only" value="NoFilter" {if $onlyFilter == "NoFilter" || $onlyfilter === ""}checked{/if}>
                            <img width="32px" height="32px" src="/templates/default/assets/p-WHITE.svg" data-toggle="tooltip" data-placement="top" title="No filter">
                        </label>
                        <input type="submit" name="search" class="btn btn-outline-secondary inline-items" style="width: 20%; height: 42px;margin-bottom: 2px;" value="Search">
                    </form>
                    {if $products|@count == 0}
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 50px;">
                                <p>Nothing Found</p>
                                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                            </div>
                        </div>
                    {else}

                    <div class="table-responsive" >
                        <table class="table table-sm table-borderless">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Tag</th>
                                <th>Export</th>
                                <th>Name</th>
                                <th>SHOP</th>
                                <th>Quantity</th>

                                <th>Location</th>
                                {*<th>Supplier price</th>*}
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $products as $item}
                                <tr>
                                    <td><a href="/cp/WMS/view/?view={$item.id}">
                                        {if $item.mainImage != null}
                                            <img class="img-fluid catalog-img" src="/uploads/images/products/{$item.mainImage}" width="50px" height="50px">
                                        {else}
                                            <img class="img-fluid catalog-img d-block" src="https://static.pingendo.com/img-placeholder-1.svg" width="50px" height="50px" >
                                        {/if}
                                        </a>
                                    </td>
                                    <td><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$item.id}">{$item.tag}</a></td>

                                    <td>
                                        {if $item.exportStatus == "Full"}
                                            <img class="p-Ico" width="32px" height="32px" src="/templates/default/assets/p-GREEN.svg" />
                                        {elseif $item.exportStatus == "Partly"}
                                            <img class="p-Ico" width="32px" height="32px" src="/templates/default/assets/p-YELLOW.svg" />
                                        {else}
                                            <img class="p-Ico" width="32px" height="32px" src="/templates/default/assets/p-RED.svg" />
                                        {/if}
                                    </td>

                                    <td class="formattedCell">
                                        <span class="d-inline-block text-truncate" style="max-width: 286px;">
                                            <a style="color: white;text-overflow: ellipsis; " title="{$item.name.et}" href="/cp/WMS/view/?view={$item.id}">{$item.name.et}</a>
                                        </span>
                                    </td>
                                    <td>{$item.platforms.$SHOP.price}</td>
                                    <td>{$item.quantity}</td>
                                    <td>{$item.locations}</td>
                                    {*<td>{$item.actPrice}</td>*}
                                    <td>
                                        {*<a class="btn btn-primary tooltip_copy" id='{$item.id}link' onclick="copyURL('#{$item.id}')" href="#" data-toggle="{$item.id}" title="Copied!">
                                            <i class="fas fa-copy" ></i>
                                            <p id='{$item.id}' hidden>{$item.URL}</p>
                                        </a>
                                        <a class="btn btn-outline-primary" href="{$item.URL}" >
                                            <i class="fas fa-link"></i>
                                            Go to
                                        </a>*}
                                        <a class="btn btn-sm btn-outline-primary" href="/cp/WMS/view/?view={$item.id}" >
                                            <i class="fas fa-link"></i>
                                            View
                                        </a>
                                        <a class="btn btn-sm btn-outline-primary" href="/cp/WMS/item/edit/?edit={$item.id}" >
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        {if $item.tag == ""}
                                            <a class="btn btn-sm btn-primary disabled" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                                        {else}
                                            <a class="btn btn-sm btn-primary" target="_blank" rel="noopener noreferrer" href="/bar.php?name={$item.name.et|escape}&tag={$item.tag}">Print label</a>

                                        {/if}
                                        <form method="POST" action="/controllers/products/delete.php" onsubmit="return confirm('Do you really want to delete item?');" style="display: inline-block;">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" name="delete" value="{$item.id}"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $('input[name=cat]').on('change', function() {
        $(this).closest("form").submit();
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
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
{include file='pagination.tpl'}
{include file='footer.tpl'}