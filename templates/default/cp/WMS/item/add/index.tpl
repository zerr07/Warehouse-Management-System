{include file='header.tpl'}
<link rel="stylesheet" href="/templates/default/assets/css/editor.css?t=16102020T165430">

<div class="row mt-3">
    <div class="col-md-12">
        <h1>Add item</h1>
        {include file='cp/WMS/item/EANModal.tpl'}
        {include file='cp/WMS/item/customPriceModal.tpl'}
        <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="row">

                <div class="col-4 offset-8 mt-1">
                    <button type="submit" class="btn btn-primary float-right ml-2 w-100"><i class="fas fa-save"></i> Submit</button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabdata">Data<span style="color: red;">*</span></a> </li>
                        <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabcat">Category<span style="color: red;">*</span></a> </li>
                        <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabwar">Warehouse<span style="color: red;">*</span></a> </li>
                        <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabimg">Images</a> </li>
                        <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabFB">Facebook data</a> </li>
                        <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabProp">Properties</a> </li>
                    </ul>
                    <div class="tab-content mt-2">
                        <div class="tab-pane fade active show" id="tabdata" role="tabpanel">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Yes" id="override" onchange="applyPrices()" name="override">
                                        <label class="form-check-label" for="override">
                                            Use custom margin?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 offset-0 offset-md-3 mt-3">
                                    <input type="number" step="1" class="form-control"
                                           name="itemEAN" id="itemEAN" placeholder="EAN">
                                </div>
                                <div class="col-12 col-sm-12 col-md-3 mt-3">
                                    <button type="button" class="btn btn-primary float-right ml-2 w-100" data-toggle="modal"
                                            data-target="#customPriceBody">
                                        <i class="fas fa-hand-holding-usd"></i> Set custom price
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row mt-3">
                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label for="SKU">SKU</label>
                                            <input type="text" class="form-control w-100" name="itemTagID"
                                                   value="{$inputTag}" id="SKU" placeholder="Item ID" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="actPrice">Actual price</label>
                                            <input type="number" step="0.01" class="form-control" onchange="changeNumber();applyPrices()"
                                                   name="itemActPrice" id="actPrice" placeholder="Actual price">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="itemMarginNumber">Margin €</label>
                                            <input type="number" step="0.01" class="form-control"
                                                   onchange="changePercent();applyPrices()" name="itemMarginNumber"
                                                   id="itemMarginNumber" placeholder="Margin number"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="itemMarginPercent">Margin percent %</label>
                                            <input type="number" step="0.01" class="form-control"
                                                   onchange="changeNumber();applyPrices()" name="itemMarginPercent"
                                                   id="itemMarginPercent" placeholder="Margin percent"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <hr style="border-color: #4c4c4c;">
                                <div class="row my-4">
                                    <div class="w-100">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnTitle-ET" class="nav-link active show" onclick="toggleTabs(['#TabBtnDesc-ET', '#TabBtnTitle-ET'], ['#tabET', '#tabtitle-ET'])">ET</a> </li>
                                            <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnTitle-RU" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-RU', '#TabBtnTitle-RU'], ['#tabRU', '#tabtitle-RU'])">RU</a> </li>
                                            <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnTitle-EN" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-EN', '#TabBtnTitle-EN'], ['#tabEN', '#tabtitle-EN'])">EN</a> </li>
                                            <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnTitle-LV" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LV', '#TabBtnTitle-LV'], ['#tabLV', '#tabtitle-LV'])">LV</a> </li>
                                            <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnTitle-LT" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LT', '#TabBtnTitle-LT'], ['#tabLT', '#tabtitle-LT'])">LT</a> </li>
                                        </ul>
                                        <div class="tab-content mt-2">
                                            <div class="tab-pane fade active show" id="tabtitle-ET" role="tabpanel">
                                                <label for="itemNameET">Product name ET</label>
                                                <input type="text" class="form-control" name="itemNameET" id="itemNameET" placeholder="Product name ET">
                                            </div>
                                            <div class="tab-pane fade" id="tabtitle-RU" role="tabpanel">
                                                <label for="itemNameLT">Product name RU</label>
                                                <input type="text" class="form-control" name="itemNameRU" id="itemNameRU" placeholder="Product name RU">
                                            </div>
                                            <div class="tab-pane fade" id="tabtitle-EN" role="tabpanel">
                                                <label for="itemNameLT">Product name EN</label>
                                                <input type="text" class="form-control" name="itemNameEN" id="itemNameEN" placeholder="Product name EN">
                                            </div>
                                            <div class="tab-pane fade" id="tabtitle-LV" role="tabpanel">
                                                <label for="itemNameLT">Product name LV</label>
                                                <input type="text" class="form-control" name="itemNameLV" id="itemNameLV" placeholder="Product name LV">
                                            </div>
                                            <div class="tab-pane fade" id="tabtitle-LT" role="tabpanel">
                                                <label for="itemNameLT">Product name LT</label>
                                                <input type="text" class="form-control" name="itemNameLT" id="itemNameLT" placeholder="Product name LT">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <hr style="border-color: #4c4c4c;">

                                <label class="mt-3">Supplier data </label>
                                <div id="listURL">
                                    <div class="row mt-3 border border-secondary p-2">
                                        <div class="col-sm-12 col-md-3">
                                            <label for="itemSupplierName">Supplier Name</label>
                                            <input type="text" class="form-control" id="itemSupplierName"
                                                   name="itemSupplierName[]"
                                                   placeholder="Supplier Name">
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <label for="itemURL">Supplier URL</label>
                                            <input type="text" class="form-control" id="itemURL" name="itemURL[]"
                                                   placeholder="Item url">
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="itemPriceVAT">Supplier price zł</label>
                                            <input type="number" step="0.01" class="form-control"
                                                   name="itemPrice[]" id="itemPrice"
                                                   placeholder="Item price">
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="itemPrice">Supplier price €</label>
                                            <input type="number" step="0.01" class="form-control"
                                                   name="itemPriceVAT[]" id="itemPriceVAT"
                                                   placeholder="Item price">
                                        </div>
                                        <div class="col-sm-12 col-md-2">
                                            <label for="itemSKU">Supplier SKU</label>
                                            <input type="text" class="form-control"
                                                   name="itemSKU[]" id="itemSKU"
                                                   placeholder="Supplier SKU">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary w-100 mt-3" onclick="addExtra()">Add extra</button>

                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <button type="button" class="btn btn-success w-100" onclick="exportAll()">Export all</button>
                                    </div>
                                    <div class="col-sm-12 col-md-6 mt-3">
                                        <button type="button" class="btn btn-info w-100" onclick="unsetCustomAll()">Unset custom</button>
                                    </div>
                                    <div class="col-12 mt-3">
                                        {foreach $platforms as $platform}
                                            {assign var="PLid" value=$platform.id}
                                            <input type="text" name="platformID[{$platform.id}]" value="{$platform.id}" hidden>

                                            <div class="row mt-3 border border-secondary p-2" style="border-radius: 2px">
                                                <div class="col-6 order-0 mt-2 col-sm-6 col-md-3 col-lg-2 m-auto">
                                                    <span>{$platform.name}</span>
                                                </div>
                                                <div class="col-6 order-1 mt-2 col-sm-6 col-md-3 col-lg-1 m-auto">
                                                    <div class="form-check" style="display: inline-flex">
                                                        <input class="form-check-input" type="checkbox" value="Yes"
                                                               id="export{$platform.id}"
                                                               name="export[{$platform.id}]">
                                                        <label class="form-check-label" for="export{$platform.id}">
                                                            Export?
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-6 order-2 mt-2 col-sm-6 col-md-6 col-lg-3 m-auto">
                                                    <input type="text" class="form-control"
                                                           name="platformURL[{$platform.id}]" placeholder="URL"
                                                           id="form17">
                                                </div>
                                                <div class="col-6 mt-2 col-sm-6 col-md-6 order-3 order-md-12 order-lg-3 col-lg-2 m-auto">
                                                    <input type="number" step="0.01"  class="form-control" onchange="applyPrices()"
                                                           name="platformPrice[{$platform.id}]"
                                                           id="platform{$platform.id}" placeholder="Price €">
                                                </div>
                                                <div class="col-6 order-4 mt-2 col-sm-6 col-md-3 col-lg-2 m-auto">
                                                    <div class="form-check">
                                                        <input class="form-check-input" onclick="applyPrices()" type="checkbox" value="Yes"
                                                               id="platformCustom{$platform.id}"
                                                               name="platformCustom[{$platform.id}]">
                                                        <label class="form-check-label" for="platformCustom{$platform.id}">
                                                            Use custom?
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-6 order-5 mt-2 col-sm-6 col-md-3 col-lg-2 m-auto">
                                                    <div style="padding-left: 20px">Profit:
                                                        <a id="profit{$platform.id}">0</a>
                                                        <i class="fas fa-euro-sign"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                                <div style="padding-top: 20px;">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-ET" class="nav-link active shows" onclick="toggleTabs(['#TabBtnDesc-ET', '#TabBtnTitle-ET'], ['#tabET', '#tabtitle-ET'])">ET</a> </li>
                                        <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-RU" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-RU', '#TabBtnTitle-RU'], ['#tabRU', '#tabtitle-RU'])">RU</a> </li>
                                        <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-EN" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-EN', '#TabBtnTitle-EN'], ['#tabEN', '#tabtitle-EN'])">EN</a> </li>
                                        <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-LV" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LV', '#TabBtnTitle-LV'], ['#tabLV', '#tabtitle-LV'])">LV</a> </li>
                                        <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-LT" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LT', '#TabBtnTitle-LT'], ['#tabLT', '#tabtitle-LT'])">LT</a> </li>
                                    </ul>
                                    <div class="tab-content mt-2">
                                        <div class="tab-pane fade active show" id="tabET" role="tabpanel">
                                            <textarea name="EST" id="etText"></textarea>
                                        </div>
                                        <div class="tab-pane fade" id="tabRU" role="tabpanel">
                                            <textarea name="RUS" id="ruText"></textarea>
                                        </div>
                                        <div class="tab-pane fade" id="tabEN" role="tabpanel">
                                            <textarea name="ENG" id="enText"></textarea>
                                        </div>
                                        <div class="tab-pane fade" id="tabLV" role="tabpanel">
                                            <textarea name="LV" id="lvText"></textarea>
                                        </div>
                                        <div class="tab-pane fade" id="tabLT" role="tabpanel">
                                            <textarea name="LT" id="ltText"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-20" id="tabcat" role="tabpanel">
                            <div>
                                {include file='cp/WMS/category/tree/checkbox/tree.tpl'}
                                <br>
                            </div>
                        </div>
                        <div class="tab-pane fade ml-20" id="tabimg" role="tabpanel">

                            {* IMAGES *}
                            <div class="form-group"> <label>Images (In order not to loose image quality file size
                                    should not exceed 1MB)</label>
                                <div class="row pb-5">
                                    <div id="ImageUploader_previewImages" class="d-flex flex-wrap align-items-center col-auto h-100 w-100"></div>
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
                                <div class="row mt-3 border border-secondary p-2">
                                    <div class="col-12 col-md-4"">
                                    <input type="text" class="form-control w-100 d-flex"
                                           name="itemQuantityNew[]" id="form17"  placeholder="Quanitity">
                                </div>
                                <div class="col-12 col-md-5"">
                                <input type="text" class="form-control w-100 d-flex"
                                       name="itemLocationNew[]" id="form17" placeholder="Location">
                            </div>
                            <div class="col-12 col-md-3">
                                <select class="custom-select" name="loc_type_new[]">
                                    {foreach $location_types as $loc_typ}
                                        <option value="{$loc_typ.id}"
                                                {if $loc_typ.id==$default_location_type} selected{/if}>
                                            {$loc_typ.name}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" style="width: 100%; margin: 10px 0 10px 0;" class="btn btn-primary" onclick="addExtraLoc()">Add extra</button>
                    {foreach $carriers as $carrier}
                        <div class="row mt-3 border border-secondary p-2 mb-3">
                            <div class="col-6 col-sm-6 col-md-3 m-auto order-0 order-md-0">
                                <span>{$carrier.name}</span>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3 m-auto order-2 order-md-1">
                                <div class="custom-control custom-switch" style="display: inline-flex">
                                    <input type="checkbox" class="custom-control-input" id="carrierEnabled{$carrier.id}"
                                           name="carrierEnabled[{$carrier.id}]" value="Yes" checked>
                                    <label class="custom-control-label" for="carrierEnabled{$carrier.id}">Enabled</label>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3 order-1 order-md-2">
                                <input type="number" step="0.01"  class="form-control"
                                       name="carrierPrice[{$carrier.id}]"
                                       id="carrierPrice{$carrier.id}" placeholder="Price €" value="{$carrier.price}">
                            </div>
                            <div class="col-6 col-sm-6 col-md-3 m-auto order-3 order-md-3">
                                <div class="form-check" style="display: inline-flex">
                                    <input class="form-check-input" type="checkbox" value="Yes"
                                           id="customCarrier{$carrier.id}"
                                           name="customCarrier[{$carrier.id}]"
                                    >
                                    <label class="form-check-label" for="customCarrier{$carrier.id}">
                                        Custom price?
                                    </label>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="tab-pane fade ml-20 mb-3" id="tabFB" role="tabpanel">
                    <a href="https://fbicons.net/" target="_blank">For list of emojis go here. (an integrated panel to be implemented)</a>

                    <textarea name="FB" id="FBText"></textarea>
                </div>
                <div class="tab-pane fade ml-20 mb-3" id="tabProp" role="tabpanel">
                    <div class="row my-3">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="Width">Width (mm)</label>
                                <input type="number" class="form-control w-100" name="width"
                                       id="Width" placeholder="0.00 mm" step="0.01">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="Height">Height (mm)</label>
                                <input type="number" class="form-control w-100" name="height"
                                       id="Height" placeholder="0.00 mm" step="0.01">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="Depth">Depth (mm)</label>
                                <input type="number" class="form-control w-100" name="depth"
                                       id="Depth" placeholder="0.00 mm" step="0.01">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="Weight">Weight (g)</label>
                                <input type="number" class="form-control w-100" name="weight"
                                       id="Weight" placeholder="0 g" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="PropBlock">

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="loadParamsEditField('PropBlock');">Add extra property</button>
                        </div>
                    </div>
                </div>

            </div>
    </div>

</div>

<button type="submit" style="display: inline-block; float:right;" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
<a class="btn btn-primary"  href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
</form>
</div>
</div>
<script src="/templates/default/assets/js/DragDropTouch.js?t=16102020T165444"></script>
<script src="/templates/default/assets/js/editor.js?t=09032021T122302"></script>
<script src="/templates/default/assets/js/priceCalc.js?t=20210104T143849"></script>
<script src="/cp/WMS/item/edit/editEAN.js?t=16102020T165440"></script>

<link rel="stylesheet" href="/templates/default/assets/css/image-uploader.css?t=16102020T165438">
<script src="/templates/default/assets/js/image-uploader.js?t=16102020T165436"></script>
<script src="/templates/default/assets/js/parameters.js?t=04022021T103520"></script>
<script src="/templates/default/assets/js/categories_main_select.js?t=16102020T165504"></script>


<script>
    $(window).on('load', function(){
        setPageTitle("Create product");
        init_image_uploader("");
        init_image_uploader("_live");
        loadParamsEditField('PropBlock');
        DisableNotSelectable();
    });
    document.querySelector("input[name='cat']").addEventListener("invalid", function (e) {
        displayAlert("Please select category", 2000, "error");
    });


</script>
<script>
    function addExtraLoc() {
        let input = "<div class=\"row mt-3 border border-secondary p-2\">" +
            "<div class=\"col-12 col-md-4\"\">" +
            "<input type=\"text\" class=\"form-control w-100 d-flex\"" +
            "name=\"itemQuantityNew[]\" id=\"form17\"  placeholder=\"Quanitity\">" +
            "</div>\n" +
            "<div class=\"col-12 col-md-5\"\">" +
            "<input type=\"text\" class=\"form-control w-100 d-flex\"" +
            "name=\"itemLocationNew[]\" id=\"form17\" placeholder=\"Location\">" +
            "</div>" +
            "<div class=\"col-12 col-md-3\">" +
            "<select class=\"custom-select\" name=\"loc_type_new[]\">" +
            "{foreach $location_types as $loc_typ}" +
            "<option value=\"{$loc_typ.id}\"" +
            "{if $loc_typ.id==$default_location_type} selected{/if}>" +
            "{$loc_typ.name}" +
            "</option>" +
            "{/foreach}" +
            "</select>" +
            "</div>" +
            "</div>";
        $("#listWarehouse").append(input);
    }
    function addExtra() {

        var input = "<div class=\"row mt-3 border border-secondary p-2\">\n" +
            "<input type=\"text\" name=\"itemSupplierID[]\" value=\"NONE\" hidden>\n" +
            "<div class=\"col-sm-12 col-md-3\">\n" +
            "    <input type=\"text\" class=\"form-control\"\n" +
            "           name=\"itemSupplierName[]\"\n" +
            "           placeholder=\"Supplier Name\">\n" +
            "</div>\n" +
            "<div class=\"col-sm-12 col-md-3\">\n" +
            "    <input type=\"text\" class=\"form-control\" name=\"itemURL[]\"\n" +
            "           placeholder=\"Item url\">\n" +
            "</div>\n" +
            "<div class=\"col-sm-12 col-md-2\">\n" +
            "    <input type=\"number\" step=\"0.01\" class=\"form-control\"\n" +
            "           name=\"itemPriceVAT[]\"\n" +
            "           placeholder=\"Item price\">\n" +
            "</div>\n" +
            "<div class=\"col-sm-12 col-md-2\">\n" +
            "    <input type=\"number\" step=\"0.01\" class=\"form-control\"\n" +
            "           name=\"itemPrice[]\"\n" +
            "           placeholder=\"Item price\">\n" +
            "</div>\n" +
            "<div class=\"col-sm-12 col-md-2\">" +
            "    <label for=\"itemSKU\">Supplier SKU</label>" +
            "    <input type=\"text\" class=\"form-control\"" +
            "        name=\"itemSKU[]\" id=\"itemSKU\"" +
            "        placeholder=\"Supplier SKU\">" +
            "</div>" +
            "</div>";
        $("#listURL").append(input);
    }
    $(window).on('load', function(){
        applyPrices();
        loadEditor('ltText', 'lt');
        loadEditor('lvText', 'lv');
        loadEditor('ruText', 'ru');
        loadEditor('etText', 'et');
        loadEditor('enText', 'en');
        loadEditor('FBText', 'et', 0);
    });
</script>
{include file='footer.tpl'}