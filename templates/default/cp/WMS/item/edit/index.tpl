{include file='header.tpl'}

<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">
                    <h1>Edit item</h1>
                    {include file='cp/WMS/item/EANModal.tpl'}
                    {include file='cp/WMS/item/customPriceModal.tpl'}
                    <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
                        <button type="submit" style="display: inline-block; float:right;" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        <ul class="nav nav-tabs">
                            <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabdata">Data</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabcat">Category<span style="color: red;">*</span></a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabwar">Warehouse<span style="color: red;">*</span></a> </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade active show" id="tabdata" role="tabpanel">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Yes" id="override" onchange="applyPrices()" name="override" {if $item.override == 1}checked{/if}>
                                    <label class="form-check-label" for="override">
                                        Use custom margin?
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="form17">Item name<span style="color: red;">*</span> </label><br />
                                    <input type="text" name="idEdit" value="{$item.id}" hidden>
                                    <div>
                                        <input type="text" style="max-width: 19%;" class="form-control top-data form-small" name="itemTagID" value="{$item.tag}" id="form17" placeholder="Item ID" readonly="readonly">
                                        <button type="button" style="width: 30%;margin-bottom: 3px;" class="btn btn-primary" onclick="getCodes({$item.id})"
                                                data-toggle="modal" data-target="#linkEANModalBody"><i class="fas fa-edit"></i> EAN Codes</button>
                                        <button type="button" style="width: 30%;margin-bottom: 3px;" class="btn btn-primary"
                                                data-toggle="modal" data-target="#customPriceBody"><i class="fas fa-hand-holding-usd"></i> Set custom price</button>
                                        {*<input type="number" step="1" style="max-width: 60.3%;" class="form-control form-small top-data" name="itemEAN" value="{$item.ean}" id="form17" placeholder="EAN">*}
                                        <div class="input-group input-group-lg top-data" style="max-width: 19%;">
                                            <div class="input-group-prepend">
                                                            <span class="input-group-text form-small">
                                                                <i class="fas fa-euro-sign"></i>
                                                            </span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control form-small" onchange="changeNumber();applyPrices()"
                                                   name="itemActPrice" id="actPrice" placeholder="Actual price" value="{$item.actPrice}">
                                        </div>
                                    </div>
                                    <input type="text" style="max-width: 49.3%;" class="form-control form-small top-data" name="itemNameET" value="{$item.name.et|escape}" id="form17" placeholder="Toode nimi" required="required">
                                    <input type="text" style="max-width: 49.3%;" class="form-control form-small top-data" name="itemNameRU" value="{$item.name.ru|escape}" id="form17" placeholder="Название товара">
                                    <div>
                                        <div class="input-group input-group-lg top-data" style="max-width: 49.3%;">
                                            <div class="input-group-prepend">
                                                            <span class="input-group-text form-small">
                                                                <i class="fas fa-euro-sign"></i>
                                                            </span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control form-small" onchange="changePercent();applyPrices()"
                                                   name="itemMarginNumber" id="itemMarginNumber" placeholder="Margin number" value="{$item.def_margin_number}">
                                        </div>
                                        <div class="input-group input-group-lg top-data" style="max-width: 49.3%;">
                                            <div class="input-group-prepend">
                                                            <span class="input-group-text form-small">
                                                                <i class="fas fa-percentage"></i>
                                                            </span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control form-small" onchange="changeNumber();applyPrices()"
                                                   name="itemMarginPercent" id="itemMarginPercent" placeholder="Margin percent" value="{$item.def_margin_percent}">
                                        </div>
                                    </div>
                                    <label for="form17">Item URL<span style="color: red;">*</span> </label>

                                    <div id="listURL">
                                        {foreach $item.suppliers as $supp}
                                            <input type="text" name="itemSupplierID[]" value="{$supp.id}" hidden>
                                            <input type="text" class="form-control SMTitemsSM form-small" name="itemSupplierName[]" id="form17" value="{$supp.supplierName}" placeholder="Supplier Name">
                                            <input type="text" class="form-control SMTitemsSM form-small" name="itemURL[]" id="form17" value="{$supp.URL}" placeholder="Item url">
                                            <div class="input-group input-group-lg SMTitemsSM">
                                                <div class="input-group-prepend">
                                                            <span class="input-group-text form-small">
                                                            <i class="fas">zł</i>
                                                            </span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control SMTitemsSM form-small" name="itemPrice[]" id="form17" value="{$supp.price}" placeholder="Item price">
                                            </div>
                                            <div class="input-group input-group-lg SMTitemsSM">
                                                <div class="input-group-prepend">
                                                            <span class="input-group-text form-small">
                                                            <i class="fas fa-euro-sign"></i>
                                                            </span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control SMTitemsSM form-small" name="itemPriceVAT[]" id="form17" value="{$supp.priceVAT}" placeholder="Item price">
                                            </div>
                                        {/foreach}
                                    </div>
                                    <button type="button" style="width: 100%; margin-top: 10px;" class="btn btn-primary" onclick="addExtra()">Add extra</button>

                                    <div style="padding-top: 20px;">
                                        <button type="button" style="width: 100%; margin-top: 10px;" class="btn btn-success" onclick="exportAll()">Export all</button>
                                        <button type="button" style="width: 100%; margin-top: 10px;" class="btn btn-info" onclick="unsetCustomAll()">Unset custom</button>
                                        {foreach $platforms as $platform}
                                            {assign var="PLid" value=$platform.id}
                                            <input type="text" name="platformID[{$platform.id}]" value="{$platform.id}" hidden>
                                            <label for="form17" style="width: 15%;">{$platform.name}</label>
                                            <div class="form-check" style="display: inline-flex">
                                                <input class="form-check-input" type="checkbox" value="Yes"
                                                       id="export{$platform.id}"
                                                       name="export[{$platform.id}]"
                                                        {if $item.platforms.$PLid.export == True}
                                                checked
                                                        {/if}>
                                                <label class="form-check-label" for="export{$platform.id}">
                                                    Export?
                                                </label>
                                            </div>

                                            <input type="text" class="form-control SMTitemsSM form-small"
                                                   name="platformURL[{$platform.id}]" placeholder="URL"
                                                   value="{$item.platforms.$PLid.URL}" id="form17">
                                            <div class="input-group input-group-lg SMTitemsSM">
                                                <div class="input-group-prepend">
                                                        <span class="input-group-text form-small"><i class="fas fa-euro-sign"></i>
                                                        </span>
                                                </div>
                                                <input type="number" step="0.01"  class="form-control form-small" onchange="applyPrices()"
                                                       name="platformPrice[{$platform.id}]" value="{$item.platforms.$PLid.price}"
                                                       id="platform{$platform.id}" placeholder="Price €">
                                            </div>
                                            <div class="input-group input-group-lg SMTitemsSM">
                                                <div class="form-check">
                                                    <input class="form-check-input" onclick="applyPrices()" type="checkbox" value="Yes"
                                                           id="platformCustom{$platform.id}"
                                                           name="platformCustom[{$platform.id}]"
                                                            {if $item.platforms.$PLid.custom == True}
                                                    checked
                                                            {/if}>
                                                    <label class="form-check-label" for="platformCustom{$platform.id}">
                                                        Use custom?
                                                    </label>
                                                </div>

                                                <div style="padding-left: 20px">Profit:
                                                    <a id="profit{$platform.id}">0</a>
                                                    <i class="fas fa-euro-sign"></i>
                                                </div>
                                            </div>

                                        {/foreach}
                                    </div>
                                    <div style="padding-top: 20px;">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabRUS">RUS</a> </li>
                                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabEST">EST</a> </li>
                                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabPL">PL</a> </li>
                                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabENG">ENG</a> </li>
                                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabLV">LV</a> </li>

                                        </ul>
                                        <div class="tab-content mt-2">
                                            <div class="tab-pane fade active show ml-20" id="tabRUS" role="tabpanel">
                                                <textarea name="RUS" id="ruText">{$item.descriptions.ru}</textarea>
                                            </div>
                                            <div class="tab-pane fade ml-20" id="tabEST" role="tabpanel">
                                                <textarea name="EST" id="etText">{$item.descriptions.et}</textarea>
                                            </div>
                                            <div class="tab-pane fade ml-20" id="tabPL" role="tabpanel">
                                                <textarea name="PL" id="plText">{$item.descriptions.pl}</textarea>
                                            </div>
                                            <div class="tab-pane fade ml-20" id="tabENG" role="tabpanel">
                                                <textarea name="ENG" id="enText">{$item.descriptions.en}</textarea>
                                            </div>
                                            <div class="tab-pane fade ml-20" id="tabLV" role="tabpanel">
                                                <textarea name="LV" id="lvText">{$item.descriptions.lv}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade ml-20" id="tabcat" role="tabpanel">
                                <div>
                                    {include file='cp/WMS/category/tree.tpl'}
                                    <br>
                                </div>
                                {* IMAGES *}
                                <div class="form-group"> <label>Images (In order not to loose image quality file size
                                        should not exceed 1MB)</label>
                                    <div class="row pb-5">
                                        <div id="ImageUploader_previewImages" class="d-flex flex-wrap align-items-center"></div>
                                        <div id="ImageUploader_previewImagesFunc" class="d-flex flex-column col-auto pt-3"></div>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ImageUploader_imageInput" onchange="ImageUploader_previewImage(this, '')" accept="image/*" multiple>
                                        <label class="custom-file-label" for="ImageUploader_imageInput" data-browse="Browse">Choose file</label>
                                    </div>
                                    <input type="text" name="ImageUploader_imagesJSON" id="ImageUploader_imagesJSON" hidden>
                                </div>
                                {* LIVE IMAGES *}
                                <div class="form-group"> <label>Live Images (In order not to loose image quality file size
                                        should not exceed 1MB)</label>
                                    <div class="row pb-5">
                                        <div id="ImageUploader_previewImages_live" class="d-flex flex-wrap align-items-center"></div>
                                        <div id="ImageUploader_previewImagesFunc_live" class="d-flex flex-column col-auto pt-3"></div>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ImageUploader_imageInput_live" onchange="ImageUploader_previewImage(this, '_live')" accept="image/*" multiple>
                                        <label class="custom-file-label" for="ImageUploader_imageInput_live" data-browse="Browse">Choose file</label>
                                    </div>
                                    <input type="text" name="ImageUploader_imagesJSON_live" id="ImageUploader_imagesJSON_live" hidden>
                                </div>
                            </div>
                            <div class="tab-pane fade ml-20" id="tabwar" role="tabpanel">
                                <div id="listWarehouse">
                                    {assign var="counter" value=0}
                                    {foreach $item.locationList as $loc}
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <input type="text" class="form-control SMTitemsSM form-small w-100 d-flex"
                                                       name="itemQuantity[{$loc.id}]" value="{$loc.quantity}" id="form17"  placeholder="Quanitity">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control w-100 d-flex"
                                                       name="itemLocation[{$loc.id}]" value="{$loc.location}" id="form17" placeholder="Location">
                                            </div>
                                            <div class="col-3">
                                                <select class="custom-select" name="loc_type[{$loc.id}]">
                                                    {foreach $location_types as $loc_typ}
                                                        <option value="{$loc_typ.id}"
                                                                {if $loc_typ.id==$loc.id_type} selected{/if}>
                                                            {$loc_typ.name}
                                                        </option>
                                                    {/foreach}
                                                </select>
                                            </div>
                                        </div>
                                        {assign var="counter" value=$counter+1}
                                    {/foreach}
                                </div>
                                <button type="button" style="width: 100%; margin: 10px 0 10px 0;" class="btn btn-primary" onclick="addExtraLoc()">Add extra</button>
                                <br>
                                {foreach $item.carrier as $carrier}
                                    <label for="form17" style="width: 15%;">{$carrier.name}</label>
                                    <div class="custom-control custom-switch" style="display: inline-flex">
                                        <input type="checkbox" class="custom-control-input" id="carrierEnabled{$carrier.id}"
                                               name="carrierEnabled[{$carrier.id}]" value="Yes"
                                               {if $carrier.enabled or !isset($carrier.enabled)}checked{/if}>
                                        <label class="custom-control-label" for="carrierEnabled{$carrier.id}">Enabled</label>
                                    </div>
                                    <div class="input-group input-group-lg SMTitemsSM">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text form-small"><i class="fas fa-euro-sign"></i>
                                            </span>
                                        </div>
                                        <input type="number" step="0.01"  class="form-control form-small"
                                               name="carrierPrice[{$carrier.id}]"
                                               id="carrierPrice{$carrier.id}" placeholder="Price €" value="{$carrier.price}">
                                    </div>
                                    <div class="form-check" style="display: inline-flex">
                                        <input class="form-check-input" type="checkbox" value="Yes"
                                               id="customCarrier{$carrier.id}"
                                               name="customCarrier[{$carrier.id}]"
                                                {if $carrier.custom}
                                                    checked
                                                {/if}
                                        >
                                        <label class="form-check-label" for="customCarrier{$carrier.id}">
                                            Custom price?
                                        </label>
                                    </div>
                                    <br>
                                {/foreach}
                            </div>
                        </div>
                        <button type="submit" style="display: inline-block; float:right;" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        <a class="btn btn-primary"  href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<link rel="stylesheet" href="/templates/default/assets/css/image-uploader.css">
<script src="/controllers/JS/image-uploader.js"></script>
<script>
    init_image_uploader("");
    init_image_uploader("_live");
    ImageUploader_images = [
        {foreach $item.images as $key => $value}
        ['exist', "/uploads/images/products/{$value.image}"],
        {/foreach}
    ];
    ImageUploader_images_live = [
        {foreach $item.images_live as $key => $value}
        ['exist', "/uploads/images/products/{$value.image}"],
        {/foreach}
    ];
    $(window).on('load', function(){
        ImageUploader_displayImagePreview("");
        ImageUploader_displayImagePreview("_live");
    });
</script>

<script>

    function addExtraLoc() {
        var input = "<div class=\"row mb-2\">\n" +
            "<div class=\"col-3\">\n" +
            "<input type=\"text\" class=\"form-control SMTitemsSM form-small w-100 d-flex\" \n" +
            "   name=\"itemQuantityNew[]\" value=\"\" id=\"form17\"  placeholder=\"Quanitity\">\n" +
            "</div>\n" +
            "<div class=\"col-6\">\n" +
            "<input type=\"text\" class=\"form-control w-100 d-flex\" \n" +
            "   name=\"itemLocationNew[]\" value=\"\" id=\"form17\" placeholder=\"Location\">\n" +
            "</div>\n" +
            "<div class=\"col-3\">\n" +
            "<select class=\"custom-select\" name=\"loc_type_new[]\">\n" +
                {foreach $location_types as $loc_typ}
            "<option value=\"{$loc_typ.id}\"\n" +
                {if $loc_typ.id==$engine.locations.WMS} "selected"+{/if}
            ">{$loc_typ.name}\n" +
            "</option>\n" +
                {/foreach}
            "</select>\n" +
            "</div>"+
            "</div>";
        $("#listWarehouse").append(input);
    }
    function addExtra() {
        /*var input1 = "<input type=\"text\" style=\"width: 19%; display:inline-block;\"  class=\"form-control\" name=\"itemID[]\" id=\"form17\" placeholder=\"ID\" required=\"required\">";
        var input2 = "<input type=\"text\" style=\"width: 60%; display:inline-block;\" class=\"form-control\" name=\"itemURL[]\" id=\"form17\" placeholder=\"Item url\" required=\"required\">";
        var input3 = "<input type=\"text\" style=\"width: 19%; display:inline-block;\"  class=\"form-control\" name=\"itemPrice[]\" id=\"form17\" placeholder=\"Item price\" required=\"required\">";*/

        var input = "<input type=\"text\" name=\"itemSupplierID[]\" value=\"NONE\" hidden>\n" +
            "<input type=\"text\" class=\"form-control SMTitemsSM form-small\" name=\"itemSupplierName[]\" id=\"form17\" placeholder=\"Supplier Name\">\n" +
            "<input type=\"text\" class=\"form-control SMTitemsSM form-small\" name=\"itemURL[]\" id=\"form17\" placeholder=\"Item url\">\n" +
            "<div class=\"input-group input-group-lg SMTitemsSM\">\n" +
            "<div class=\"input-group-prepend\">\n" +
            "<span class=\"input-group-text form-small\">\n" +
            "<i class=\"fas\">zł</i>\n" +
            "</span>\n" +
            "</div>\n" +
            "<input type=\"number\" step=\"0.01\"  class=\"form-control SMTitemsSM form-small\" name=\"itemPrice[]\" id=\"form17\" placeholder=\"Item price\">\n" +
            "</div>\n" +
            "<div class=\"input-group input-group-lg SMTitemsSM\">\n" +
            "<div class=\"input-group-prepend\">\n" +
            "<span class=\"input-group-text form-small\">\n" +
            "<i class=\"fas fa-euro-sign\"></i>\n" +
            "</span>\n" +
            "</div>\n" +
            "<input type=\"number\" step=\"0.01\"  class=\"form-control SMTitemsSM form-small\" name=\"itemPriceVAT[]\" id=\"form17\" placeholder=\"Item price\">\n" +
            "</div>";
        $("#listURL").append(input);
    }
    $(window).on('load', function(){
        applyPrices();
        loadEditor('lvText', 'lv');
        loadEditor('plText', 'pl');
        loadEditor('ruText', 'ru');
        loadEditor('etText', 'et');
        loadEditor('enText', 'en');

    });
</script>
{include file='footer.tpl'}