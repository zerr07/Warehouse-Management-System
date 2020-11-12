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
    <div class="row">
        {if $item.images|@count > 1}
            <div class="col-2">
                <a class="" data-target="#carousel{$item.id}" role="button" data-slide="prev" style="float: left">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="margin-top: 150px;"></span>
                    <span class="sr-only">Previous</span>
                </a>
            </div>

        {/if}
        <div class="col-8">
            <div id="carousel{$item.id}" class="row align-items-center carousel slide">
                <div class="carousel-inner">
                    {if $item.mainImage != null}
                        <div class="carousel-item active">
                            <a data-toggle="modal" data-target="#image_modal">
                                <img src="/uploads/images/products/{$item.mainImage}" class="img-fluid img_view_fit" alt="..."
                                     onerror="this.src='/templates/default/assets/unable-to-load-img.svg'"></a>
                        </div>
                    {else}
                        <div class="carousel-item active">
                            <img src="/templates/default/assets/img-placeholder-1.svg" class="img-fluid img_view_fit" alt="...">
                        </div>
                    {/if}
                    {if $item.images|@count > 1}
                        {foreach $item.images as $img}
                            {if $img.position != 1}
                                <div class="carousel-item">
                                    <a data-toggle="modal" data-target="#image_modal">
                                        <img src="/uploads/images/products/{$img.image}" class="img-fluid img_view_fit" alt="..."
                                             onerror="this.src='/templates/default/assets/unable-to-load-img.svg'">
                                    </a>
                                </div>
                            {/if}
                        {/foreach}
                    {/if}
                </div>

            </div>
        </div>


        {if $item.images|@count > 1}
            <div class="col-2">
                <a class="" data-target="#carousel{$item.id}" role="button" data-slide="next" style="float: right">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="margin-top: 150px;"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        {/if}
    </div>

{/if}