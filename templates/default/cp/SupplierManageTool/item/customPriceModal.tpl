<div class="modal fade" id="customPriceBody" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customPriceTitle">Set custom price for all platforms</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customPriceNModal">
                <input type="number" step="0.01" class="form-control form-small d-inline-block" style="width: auto;" name="setCustomPriceForAll" id="setCustomPriceForAll" value="0.00">
                <button type="button" class="btn btn-primary d-inline-block" id="setCustomPriceForAllBtn" onclick="setCustomPriceForAll()">Set price</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<script>
    let platformIDs = [
        {foreach $platforms as $platform}
        {$platform.id},
        {/foreach}
    ];

    function setCustomPriceForAll() {
        for (let id in platformIDs){
            document.getElementById("platformCustom"+platformIDs[id]).checked = true;
            document.getElementById("platform"+platformIDs[id]).value = document.getElementById("setCustomPriceForAll").value;
            changeNumber();applyPrices();
        }
    }
</script>