{include file='header.tpl'}
{assign var="SHOP" value=1}

<div class="row mt-3">
    <div class="col-md-12" style="white-space: nowrap;">
        {if $platforms|@count == 0}
            <div class="row">
                <div class="col-md-12" style="margin-top: 50px;">
                    <p>Nothing Found</p>
                    <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                </div>
            </div>
        {else}
            {foreach $platforms as $item}
                <div class="row mt-3 border border-secondary p-1">
                    <div class="col-1 col-sm-1 p-0 p-md-1 m-auto">
                        <a href="/cp/WMS/platforms/desc/?edit={$item.id}">{$item.id}</a>
                    </div>
                    <div class="col-3 col-sm-3 p-0 p-md-1 d-flex justify-content-center m-auto">
                        <a style="text-overflow: ellipsis;" href="/cp/WMS/platforms/desc/?edit={$item.id}">{$item.name}</a>
                    </div>
                    <div class="col-4 col-sm-4 p-0 p-md-1 d-flex justify-content-center m-auto">
                        {$item.margin}
                    </div>
                    <div class="col-4 col-sm-4 p-0 p-md-1 d-flex justify-content-end">
                        <a class="btn btn-sm btn-outline-primary" href="/cp/WMS/platforms/desc/?edit={$item.id}" >
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                    </div>
                </div>
            {/foreach}

        {/if}
        <div class="row mt-3">

            <div class="col-12 d-flex justify-content-end">
                <a class="btn btn-primary d-inline-flex ml-2" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Platform list");
    })
</script>
{include file='footer.tpl'}