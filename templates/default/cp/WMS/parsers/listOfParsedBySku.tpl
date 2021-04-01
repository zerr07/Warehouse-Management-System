{include file='header.tpl'}

<div class="row m-3">
    <div class="col-12">
        {assign var='count' value=0}
        {assign var='len' value=count($matches)}
        {foreach $matches as $key => $match}
            <div class="row mt-2">
                <div class="col-sm-3 col-md-2 my-auto">
                    <img class="thumbnail-img" src="/uploads/images/products/{$match.mainImage}">
                </div>
                <div class="col-sm-9 col-md-3 my-auto">
                    <span class="d-block">Title: {if isset($match.name.en)}{$match.name.en|escape}{/if}</span>
                </div>
                <div class="col-sm-4 col-md-2 my-auto">
                    <a class="btn btn-primary float-right w-100" href="/cp/WMS/item/edit/?edit={$key}">Edit</a>
                </div>
                <div class="col-sm-4 col-md-2 my-auto">
                    <a class="btn btn-info float-right w-100" href="/cp/WMS/view/?view={$key}">Go to</a>
                </div>
                <div class="col-sm-4 col-md-2 my-auto">
                    <button type="button" class="btn btn-warning float-right w-100" onclick="setFlag('{$key}', 'Parser_SKU_Exclude')">Exclude</button>
                </div>
            </div>

            {if ceil($len)/3 == $count}
                {assign var='count' value=0}
                <hr class="w-100" style="height: 10px;background: #e7305e6e;">
            {else}
                <hr class="w-100">
                {assign var='count' value=$count+1}
            {/if}
        {/foreach}
    </div>
</div>
<script>
    $(window).on('load', async function() {
        setPageTitle("Parser list by images");
    })

</script>

{include file='footer.tpl'}