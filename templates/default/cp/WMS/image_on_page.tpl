<div class="row w-100">
    <div class="col-9">
        <a href="/cp/WMS/view/?view={$item.id}">
            <div id="carousel{$item.id}" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    {if $item.mainImage != null}
                        <div class="carousel-item active">
                            <img data-src="/uploads/images/products/{$item.mainImage}"  class="d-block img_home_fit w-100 m-auto lazyload" alt="..."
                                 onerror="this.src='/templates/default/assets/unable-to-load-img.svg'">
                        </div>
                    {else}
                        <div class="carousel-item active">
                            <img data-src="/templates/default/assets/img-placeholder-1.svg" class="d-block img_home_fit w-100 m-auto lazyload" alt="...">
                        </div>
                    {/if}
                    {if $item.images|@count > 1}
                        {foreach $item.images as $img}
                            {if $img.position != 1}
                                <div class="carousel-item">
                                    <img data-src="/uploads/images/products/{$img.image}" class="d-block img_home_fit w-100 m-auto lazyload" alt="..."
                                         onerror="this.src='/templates/default/assets/unable-to-load-img.svg'">
                                </div>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            </div>
        </a>
    </div>
    <div class="col-3 p-0">

        {if $item.exportStatus == "Full"}
            <div class="w-100 status_box" style="border-color: #58FF6A;background-color: #58FF6A"></div>
        {elseif $item.exportStatus == "Partly"}
            <div class="w-100 status_box" style="border-color: #F9FD79;background-color: #F9FD79"></div>
        {else}
            <div class="w-100 status_box" style="border-color: #FF5858;background-color: #FF5858"></div>
        {/if}
    </div>

</div>
