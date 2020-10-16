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
    <div id="carousel_live{$item.id}" class="row align-items-center carousel slide m-auto" data-ride="carousel">
        <div class="carousel-inner">
            {if $item.mainImage_live != null}
                <div class="carousel-item active">
                    <a data-toggle="modal" data-target="#image_modal">
                        <img src="/uploads/images/products/{$item.mainImage_live}" class="img-fluid" alt="..."
                             onerror="this.src='/templates/default/assets/unable-to-load-img.svg'"></a>
                </div>
            {else}
                <div class="carousel-item active">
                    <img src="/templates/default/assets/img-placeholder-1.svg" class="img-fluid" alt="...">
                </div>
            {/if}
            {if $item.images_live|@count > 1}
                {foreach $item.images_live as $img}
                    {if $img.position != 1}
                        <div class="carousel-item">
                            <a data-toggle="modal" data-target="#image_modal">
                                <img src="/uploads/images/products/{$img.image}" class="img-fluid" alt="..."
                                     onerror="this.src='/templates/default/assets/unable-to-load-img.svg'">
                            </a>
                        </div>
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
