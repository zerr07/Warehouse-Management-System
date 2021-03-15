{include file='header.tpl'}

<div class="row m-3">
    <div class="col-12">
        {foreach $matches as $key => $match}
            <div class="row mt-2">
                <div class="col-2 my-auto">
                    <img class="thumbnail-img" src="/uploads/images/products/{$match.mainImage}">
                </div>
                <div class="col-8 my-auto">
                    <span class="d-block">Title: {if isset($match.name.en)}{$match.name.en|escape}{/if}</span>
                </div>
                <div class="col-2 my-auto">
                    <a class="btn btn-info float-right" href="/cp/WMS/view/?view={$key}">Go to</a>
                </div>
            </div>
        {/foreach}
    </div>
</div>
<script>
    $(window).on('load', async function() {
        setPageTitle("Parser list by images");
    })
</script>

{include file='footer.tpl'}