{include file='header.tpl'}
<div class="row mt-4 mb-3">
    <div class="col-4 col-md-3 col-lg-2 my-auto">Platform</div>
    <div class="col-4 col-md-3 col-lg-2 my-auto">For export</div>
    <div class="col-4 col-md-3 col-lg-2 my-auto">Out of stock</div>
    <div class="col-4 col-md-3 col-lg-2 my-auto">Errors</div>
</div>
<hr>
    {foreach $statistics as $key=>$value}
        <div class="row">
            <div class="col-4 col-md-3 col-lg-2 my-auto">
                {$value.name}
            </div>
            <div class="col-4 col-md-3 col-lg-2 my-auto">
                {$value.count}
            </div>
            <div class="col-4 col-md-3 col-lg-2 my-auto">
                <a href="?platform={$key}&platformName={$value.name}&action=outOfStock" onclick="turnOnPreloader()">{$value.outOfStock}</a>
            </div>
            <div class="col-4 col-md-3 col-lg-2 my-auto">
                <a href="/cp/WMS/logs/XML/" onclick="turnOnPreloader()">{$value.errors}</a>
            </div>
        </div>
        <hr>
    {/foreach}
{include file='footer.tpl'}