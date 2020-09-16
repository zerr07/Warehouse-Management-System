{if $item.images_live|@count == 0}
    <p>No live images</p>
{else}
    <style>
        .carousel-control-next-icon, .carousel-control-prev-icon{
            cursor: pointer;
        }
        .carousel-item > a {
            cursor: zoom-in;
        }
    </style>
    <div id="carousel_live{$item.id}" class="row align-items-center carousel slide" data-ride="carousel" style="width: 300px;height: 300px;margin: auto;">
        <div class="carousel-inner">
            {if $item.mainImage_live != null}
                <div class="carousel-item active">
                    <a data-toggle="modal" data-target="#image_modal_main_live">
                        <img src="/uploads/images/products/{$item.mainImage_live}" class="img-fluid" alt="..."></a>
                </div>
                {include file='cp/WMS/view/image_live_zoom.tpl'}
            {else}
                <div class="carousel-item active">
                    <img src="https://static.pingendo.com/img-placeholder-1.svg" class="img-fluid" alt="...">
                </div>
            {/if}
            {if $item.images_live|@count > 1}
                {foreach $item.images_live as $img}
                    {if $img.primary != True}
                        <div class="carousel-item">
                            <a data-toggle="modal" data-target="#image_modal_live{$img.id}">
                                <img src="/uploads/images/products/{$img.image}" class="img-fluid" alt="...">
                            </a>
                        </div>
                        {include file='cp/WMS/view/image_live_zoom.tpl'}
                    {/if}
                {/foreach}
            {/if}
        </div>
        {if $item.images_live|@count > 1}
            <a class="carousel-control-prev" data-target="#carousel_live{$item.id}" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" data-target="#carousel_live{$item.id}" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        {/if}
    </div>
{/if}
