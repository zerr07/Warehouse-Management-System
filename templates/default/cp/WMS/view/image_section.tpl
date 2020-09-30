{if $item.images|@count == 0}
    <p>No images</p>
{else}
    <style>
        .carousel-control-next-icon, .carousel-control-prev-icon{
            cursor: pointer;
        }
        .carousel-item > a {
            cursor: zoom-in;
        }
    </style>
    <div id="carousel{$item.id}" class="row align-items-center carousel slide" data-ride="carousel" style="width: 300px;min-height:300px;height:300px;margin: auto;">
        <div class="carousel-inner">
            {if $item.mainImage != null}
                <div class="carousel-item active">
                    <a data-toggle="modal" data-target="#image_modal">
                        <img src="/uploads/images/products/{$item.mainImage}" class="img-fluid" alt="..."></a>
                </div>
            {else}
                <div class="carousel-item active">
                    <img src="https://static.pingendo.com/img-placeholder-1.svg" class="img-fluid" alt="...">
                </div>
            {/if}
            {if $item.images|@count > 1}
                {foreach $item.images as $img}
                    {if $img.position != 1}
                        <div class="carousel-item">
                            <a data-toggle="modal" data-target="#image_modal">
                                <img src="/uploads/images/products/{$img.image}" class="img-fluid" alt="...">
                            </a>
                        </div>
                    {/if}
                {/foreach}
            {/if}
        </div>
        {if $item.images|@count > 1}
            <a class="carousel-control-prev" data-target="#carousel{$item.id}" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" data-target="#carousel{$item.id}" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        {/if}
    </div>
{/if}