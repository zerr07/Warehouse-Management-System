{include file='header.tpl'}
<div class="row mt-4 mb-3">
    <div class="col-12">{$platform}</div>
    <div class="col-2 col-md-3 col-lg-2 my-auto text-truncate">Product id</div>
    <div class="col-6 col-md-6 col-lg-8 my-auto text-truncate">Product name</div>
    <div class="col-2 col-md-3 col-lg-2 my-auto text-truncate">Quantity</div>
</div>
<hr>
    {foreach $products as $value}
        <div class="row">
            <div class="col-2 col-md-3 col-lg-2 my-auto text-truncate">
                <a href="/cp/WMS/view/?view={$value.id}" onclick="turnOnPreloader()">{$value.id}</a>
            </div>
            <div class="col-8 col-md-6 col-lg-8 my-auto text-truncate">
                <a href="/cp/WMS/view/?view={$value.id}" onclick="turnOnPreloader()">{$value.name}</a>
            </div>
            <div class="col-2 col-md-3 col-lg-2 my-auto text-truncate">{$value.qty}</div>
        </div>
        <hr>
    {/foreach}
<div class="row">
    <div class="d-flex justify-content-end mt-3">
        <a class="btn btn-primary" href="/cp/statistics/export" onclick="turnOnPreloader()">
            <i class="fas fa-undo-alt"></i> Back
        </a>
    </div>
</div>
{include file='footer.tpl'}