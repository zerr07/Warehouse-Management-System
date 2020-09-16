{if !isset($img)}
    <div class="modal fade" id="image_modal_main" tabindex="-1" role="dialog" aria-labelledby="image_modal_main" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="/uploads/images/products/{$item.mainImage}" class="img-fluid" alt="{$item.mainImage}">
                </div>
            </div>
        </div>
    </div>
{else}
    <div class="modal fade " id="image_modal{$img.id}" tabindex="-1" role="dialog" aria-labelledby="image_modal{$img.id}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="/uploads/images/products/{$img.image}" class="img-fluid" alt="{$img.image}">
                </div>
            </div>
        </div>
    </div>
{/if}