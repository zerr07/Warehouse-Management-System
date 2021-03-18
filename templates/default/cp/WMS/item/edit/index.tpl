{include file='header.tpl'}
<link rel="stylesheet" href="/templates/default/assets/css/editor.css?t=16102020T165448">

<div class="row mt-3">
    <div class="col-md-12">
        <h1>Edit item</h1>
        {include file='cp/WMS/item/EANModal.tpl'}
        {include file='cp/WMS/item/customPriceModal.tpl'}
        <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="row">

                <div class="col-4 offset-8 mt-1">
                    <button type="submit" class="btn btn-primary float-right ml-2 w-100"><i class="fas fa-save"></i> Submit</button>
                </div>
            </div>
            <div class="row mt-3">
                <ul class="nav nav-pills mx-3" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" data-target="#home" href="">Main</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" data-target="#parser" href="">Parser</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
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
                                                <input class="form-check-input" type="checkbox" value="Yes" id="override" onchange="applyPrices()" name="override" {if $item.override == 1}checked{/if}>
                                                <label class="form-check-label" for="override">
                                                    Use custom margin?
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-3 offset-0 offset-md-3 mt-3">
                                            <button type="button" class="btn btn-primary float-right ml-2 w-100" onclick="getCodes({$item.id})"
                                                    data-toggle="modal" data-target="#linkEANModalBody">
                                                <i class="fas fa-edit"></i> EAN Codes
                                            </button>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-3 mt-3">
                                            <button type="button" class="btn btn-primary float-right ml-2 w-100" data-toggle="modal"
                                                    data-target="#customPriceBody">
                                                <i class="fas fa-hand-holding-usd"></i> Set custom price
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="idEdit" value="{$item.id}" hidden>
                                        <div class="row mt-3">
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label for="SKU">SKU</label>
                                                <input type="text" class="form-control w-100" name="itemTagID"
                                                       value="{$item.tag}" id="SKU" placeholder="Item ID" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label for="actPrice">Actual price</label>
                                                    <input type="number" step="0.01" class="form-control" onchange="changeNumber();applyPrices()"
                                                           name="itemActPrice" id="actPrice" placeholder="Actual price" value="{$item.actPrice}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="itemMarginNumber">Margin €</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                           onchange="changePercent();applyPrices()" name="itemMarginNumber"
                                                           id="itemMarginNumber" placeholder="Margin number"
                                                           value="{$item.def_margin_number}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label for="itemMarginPercent">Margin percent %</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                           onchange="changeNumber();applyPrices()" name="itemMarginPercent"
                                                           id="itemMarginPercent" placeholder="Margin percent"
                                                           value="{$item.def_margin_percent}">
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
                                                        <input type="text" class="form-control" name="itemNameET" value="{$item.name.et|escape}" id="itemNameET" placeholder="Product name ET">
                                                    </div>
                                                    <div class="tab-pane fade" id="tabtitle-RU" role="tabpanel">
                                                        <label for="itemNameLT">Product name RU</label>
                                                        <input type="text" class="form-control" name="itemNameRU" value="{if isset($item.name.ru)}{$item.name.ru|escape}{/if}" id="itemNameRU" placeholder="Product name RU">
                                                    </div>
                                                    <div class="tab-pane fade" id="tabtitle-EN" role="tabpanel">
                                                        <label for="itemNameLT">Product name EN</label>
                                                        <input type="text" class="form-control" name="itemNameEN" value="{if isset($item.name.en)}{$item.name.en|escape}{/if}" id="itemNameEN" placeholder="Product name EN">
                                                    </div>
                                                    <div class="tab-pane fade" id="tabtitle-LV" role="tabpanel">
                                                        <label for="itemNameLT">Product name LV</label>
                                                        <input type="text" class="form-control" name="itemNameLV" value="{if isset($item.name.lv)}{$item.name.lv|escape}{/if}" id="itemNameLV" placeholder="Product name LV">
                                                    </div>
                                                    <div class="tab-pane fade" id="tabtitle-LT" role="tabpanel">
                                                        <label for="itemNameLT">Product name LT</label>
                                                        <input type="text" class="form-control" name="itemNameLT" value="{if isset($item.name['lt'])}{$item.name['lt']|escape}{/if}" id="itemNameLT" placeholder="Product name LT">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <hr style="border-color: #4c4c4c;">


                                        <label class="mt-3">Supplier data </label>
                                        <div id="listURL">
                                            {foreach $item.suppliers as $supp}
                                                <div class="row mt-3 border border-secondary p-2">
                                                    <input type="text" name="itemSupplierID[]" value="{$supp.id}" hidden>
                                                    <div class="col-sm-12 col-md-3">
                                                        <label for="itemSupplierName{$supp.id}">Supplier Name</label>
                                                        <input type="text" class="form-control" id="itemSupplierName{$supp.id}"
                                                               name="itemSupplierName[]" value="{if isset($supp.supplierName)}{$supp.supplierName}{/if}"
                                                               placeholder="Supplier Name">
                                                    </div>
                                                    <div class="col-sm-12 col-md-3">
                                                        <label for="itemUR{$supp.id}L">Supplier URL</label>
                                                        <input type="text" class="form-control" id="itemURL{$supp.id}" name="itemURL[]"
                                                               value="{if isset($supp.URL)}{$supp.URL}{/if}" placeholder="Supplier URL">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2">
                                                        <label for="itemPriceVAT{$supp.id}">Supplier price zł</label>
                                                        <input type="number" step="0.01" class="form-control"
                                                               name="itemPrice[]" id="itemPrice{$supp.id}" value="{if isset($supp.price)}{$supp.price}{/if}"
                                                               placeholder="Supplier price zł">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2">
                                                        <label for="itemPrice{$supp.id}">Supplier price €</label>
                                                        <input type="number" step="0.01" class="form-control"
                                                               name="itemPriceVAT[]" id="itemPriceVAT{$supp.id}" value="{if isset($supp.priceVAT)}{$supp.priceVAT}{/if}"
                                                               placeholder="Supplier price €">
                                                    </div>
                                                    <div class="col-sm-12 col-md-2">
                                                        <label for="itemSKU{$supp.id}">Supplier SKU</label>
                                                        <input type="text" class="form-control"
                                                               name="itemSKU[]" id="itemSKU{$supp.id}" value="{if isset($supp.SKU)}{$supp.SKU}{/if}"
                                                               placeholder="Supplier SKU">
                                                    </div>
                                                </div>
                                            {/foreach}
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
                                                        <div class="col-6 order-0 mt-2 col-sm-6 col-md-3 col-lg-2 mt-lg-0">
                                                            <span>{$platform.name}</span>
                                                        </div>
                                                        <div class="col-6 order-1 mt-2 col-sm-6 col-md-3 col-lg-1 mt-lg-0">
                                                            <div class="form-check" style="display: inline-flex">
                                                                <input class="form-check-input" type="checkbox" value="Yes"
                                                                       id="export{$platform.id}"
                                                                       name="export[{$platform.id}]"
                                                                        {if isset($item.platforms.$PLid.export) && $item.platforms.$PLid.export == True}
                                                                checked
                                                                        {/if}>
                                                                <label class="form-check-label" for="export{$platform.id}">
                                                                    Export?
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 order-2 mt-2 col-sm-6 col-md-6 col-lg-3 mt-lg-0">
                                                            <input type="text" class="form-control"
                                                                   name="platformURL[{$platform.id}]" placeholder="URL"
                                                                   value="{if isset($item.platforms.$PLid.URL)}{$item.platforms.$PLid.URL}{/if}" id="form17">
                                                        </div>
                                                        <div class="col-6 mt-2 col-sm-6 col-md-6 order-3 order-md-12 order-lg-3 col-lg-2 mt-lg-0">
                                                            <input type="number" step="0.01"  class="form-control" onchange="applyPrices()"
                                                                   name="platformPrice[{$platform.id}]" value="{if isset($item.platforms.$PLid.price)}{$item.platforms.$PLid.price}{/if}"
                                                                   id="platform{$platform.id}" placeholder="Price €">
                                                        </div>
                                                        <div class="col-6 order-4 mt-2 col-sm-6 col-md-3 col-lg-2 mt-lg-0">
                                                            <div class="form-check">
                                                                <input class="form-check-input" onclick="applyPrices()" type="checkbox" value="Yes"
                                                                       id="platformCustom{$platform.id}"
                                                                       name="platformCustom[{$platform.id}]"
                                                                        {if isset($item.platforms.$PLid.custom) && $item.platforms.$PLid.custom == True}
                                                                checked
                                                                        {/if}>
                                                                <label class="form-check-label" for="platformCustom{$platform.id}">
                                                                    Use custom?
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 order-5 mt-2 col-sm-6 col-md-3 col-lg-2 mt-lg-0">
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
                                                <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-ET" class="nav-link active show" onclick="toggleTabs(['#TabBtnDesc-ET', '#TabBtnTitle-ET'], ['#tabET', '#tabtitle-ET'])">ET</a> </li>
                                                <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-RU" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-RU', '#TabBtnTitle-RU'], ['#tabRU', '#tabtitle-RU'])">RU</a> </li>
                                                <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-EN" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-EN', '#TabBtnTitle-EN'], ['#tabEN', '#tabtitle-EN'])">EN</a> </li>
                                                <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-LV" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LV', '#TabBtnTitle-LV'], ['#tabLV', '#tabtitle-LV'])">LV</a> </li>
                                                <li class="nav-item"> <a href="javascript:void(0)" id="TabBtnDesc-LT" class="nav-link" onclick="toggleTabs(['#TabBtnDesc-LT', '#TabBtnTitle-LT'], ['#tabLT', '#tabtitle-LT'])">LT</a> </li>
                                            </ul>
                                            <div class="tab-content mt-2">
                                                <div class="tab-pane fade active show" id="tabET" role="tabpanel">
                                                    <textarea name="EST" id="etText">{if isset($item.descriptions.et)}{$item.descriptions.et}{/if}</textarea>
                                                </div>
                                                <div class="tab-pane fade" id="tabRU" role="tabpanel">
                                                    <textarea name="RUS" id="ruText">{if isset($item.descriptions.ru)}{$item.descriptions.ru}{/if}</textarea>
                                                </div>
                                                <div class="tab-pane fade" id="tabEN" role="tabpanel">
                                                    <textarea name="ENG" id="enText">{if isset($item.descriptions.en)}{$item.descriptions.en}{/if}</textarea>
                                                </div>
                                                <div class="tab-pane fade" id="tabLV" role="tabpanel">
                                                    <textarea name="LV" id="lvText">{if isset($item.descriptions.lv)}{$item.descriptions.lv}{/if}</textarea>
                                                </div>
                                                <div class="tab-pane fade" id="tabLT" role="tabpanel">
                                                    <textarea name="LT" id="ltText">{if isset($item.descriptions['lt'])}{$item.descriptions['lt']}{/if}</textarea>
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
                                <div class="tab-pane fade ml-20" id="tabwar" role="tabpanel">
                                    <div id="listWarehouse">
                                        {assign var="counter" value=0}
                                        {foreach $item.locationList as $loc}
                                            <div class="row mt-3 border border-secondary p-2">
                                                <div class="col-12 col-md-4">
                                                    <input type="text" class="form-control w-100 d-flex"
                                                           name="itemQuantity[{$loc.id}]" value="{$loc.quantity}" id="form17"  placeholder="Quanitity">
                                                </div>
                                                <div class="col-12 col-md-5">
                                                    <input type="text" class="form-control w-100 d-flex"
                                                           name="itemLocation[{$loc.id}]" value="{$loc.location}" id="form17" placeholder="Location">
                                                </div>
                                                <div class="col-12 col-md-3">
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
                                    {foreach $item.carrier as $carrier}
                                        <div class="row mt-3 border border-secondary p-2 mb-3">
                                            <div class="col-6 col-sm-6 col-md-3 m-auto order-0 order-md-0">
                                                <span>{$carrier.name}</span>
                                            </div>
                                            <div class="col-6 col-sm-6 col-md-3 m-auto order-2 order-md-1">
                                                <div class="custom-control custom-switch" style="display: inline-flex">
                                                    <input type="checkbox" class="custom-control-input" id="carrierEnabled{$carrier.id}"
                                                           name="carrierEnabled[{$carrier.id}]" value="Yes"
                                                           {if $carrier.enabled or !isset($carrier.enabled)}checked{/if}>
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
                                                            {if $carrier.custom}
                                                                checked
                                                            {/if}
                                                    >
                                                    <label class="form-check-label" for="customCarrier{$carrier.id}">
                                                        Custom price?
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
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
                                <div class="tab-pane fade ml-20 mb-3" id="tabFB" role="tabpanel">
                                    <a href="https://fbicons.net/" target="_blank">For list of emojis go here. (an integrated panel to be implemented)</a>
                                    <textarea name="FB" id="FBText">{if isset($item.FB_description) && $item.FB_description !== ""}{$item.FB_description}{/if}</textarea>

                                </div>
                                <div class="tab-pane fade ml-20 mb-3" id="tabProp" role="tabpanel">
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
    </div>
    <div class="tab-pane fade" id="parser" role="tabpanel">
        <div class="row">
            <div class="col-2">
                <img src="/uploads/images/products/{$item.mainImage}" class="thumbnail-img">
            </div>
            <div class="col-4">
                <span class="d-block">Title: {if isset($item.name.en)}{$item.name.en|escape}{/if}</span>
                <span>Price: {if isset($item.platforms["1"].price)}{$item.platforms["1"].price}{/if}</span>
            </div>
            <div class="col-6">

            </div>
        </div>
        <ul class="nav nav-pills mb-3 mt-3" id="parser-pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="by-images-tab" data-toggle="pill" data-target="#by-images" href="" role="tab" aria-controls="by-images" aria-selected="true">By images</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="by-text-tab" data-toggle="pill" data-target="#by-text" href="" role="tab" aria-controls="by-text" aria-selected="false">By text</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="by-sku-tab" data-toggle="pill" data-target="#by-sku" href="" role="tab" aria-controls="by-sku" aria-selected="false">By sku</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="by-manual-tab" data-toggle="pill" data-target="#by-manual" href="" role="tab" aria-controls="by-manual" aria-selected="false">Manual match</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="parse-data-tab" data-toggle="pill" data-target="#parse-data" href="" role="tab" aria-controls="parse-data" aria-selected="false">Parse data</a>
            </li>
        </ul>
        <div class="tab-content mb-3" id="pills-tabContent">
            <div class="tab-pane fade show active" id="by-images" role="tabpanel" aria-labelledby="by-images-tab">
                <div class="row" id="by-images-block">

                </div>
            </div>
            <div class="tab-pane fade" id="by-text" role="tabpanel" aria-labelledby="by-text-tab">
                <div class="form-group">
                    <label for="siteParserSelect">Source</label>
                    <select class="form-control" id="siteParserSelect"
                            onchange='document.getElementById("by-text-block").innerHTML = "";getParsedByText("{$item.id}", "{$item.name.en}", 0, this.value)'>
                        <option value="all" selected>All</option>
                    </select>
                </div>
                <div class="row" id="by-text-block">

                </div>
                <button type="button" class="btn btn-info" onclick="loadMoreTextParser()">Load more</button>
            </div>
            <div class="tab-pane fade" id="by-sku" role="tabpanel" aria-labelledby="by-sku-tab">
                <div class="form-group">
                    <label for="siteParserSelectSku">Source</label>
                    <select class="form-control" id="siteParserSelectSku" onchange='getBySKU()'></select>
                </div>
                <div class="row" id="by-sku-block">

                </div>
            </div>
            <div class="tab-pane fade" id="by-manual" role="tabpanel" aria-labelledby="by-manual-tab">
                <div class="row">
                    <div class="col-8">
                        <input type="text" class="form-control" id="addManualUrl" placeholder="URL">
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-info w-100" onclick="MarkAsMatch('{$item.id}', document.getElementById('addManualUrl').value)">Add</button>
                    </div>
                </div>


            </div>
            <div class="tab-pane fade" id="parse-data" role="tabpanel" aria-labelledby="parse-data-tab">
                <div class="row" id="parse-block">

                </div>
                <div class="modal fade" id="PreviewParserModal" tabindex="-1" aria-labelledby="PreviewParserModalLabel" aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="PreviewParserModalLabel">Parser data preview</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="PreviewParserModalData">
                                <div class="row">
                                    <div class="col-12" id="PreviewParserModalDataTitle">

                                    </div>
                                    <hr class="w-100">
                                    <div class="col-12" id="PreviewParserModalDataDesc">

                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $('a#by-images-tab').on('show.bs.tab', function (event) {
        getParsedImages("{$item.id}")
    })
    $('a#by-text-tab').on('show.bs.tab', function (event) {
        if (document.getElementById("by-text-block").children.length === 0){
            getParsedByText("{$item.id}", "{$item.name.en}", 0, document.getElementById("siteParserSelect").value)
        }
    })
    function getBySKU(){
        document.querySelectorAll("input[id*='itemSKU']").forEach(el => {
            getParsedBySku("{$item.id}", el.value, document.getElementById("siteParserSelectSku").value)
        })
    }
    $('a#by-sku-tab').on('show.bs.tab', function (event) {
        getBySKU();
    })
    $('a#parse-data-tab').on('show.bs.tab', function (event) {
        getParseBlock("{$item.id}")
    })
    function loadMoreTextParser(){
        getParsedByText("{$item.id}", "{$item.name.en}", document.getElementById("by-text-block").children.length, document.getElementById("siteParserSelect").value)
    }
</script>
            <button type="submit" style="display: inline-block; float:right;" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
            <a class="btn btn-primary"  href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
        </form>
    </div>
</div>
<script src="/templates/default/assets/js/DragDropTouch.js?t=16102020T165458"></script>
<script src="/templates/default/assets/js/editor.js?t=09032021T122256"></script>
<script src="/templates/default/assets/js/priceCalc.js?t=20210104T143914"></script>
<script src="/cp/WMS/item/edit/editEAN.js?t=16102020T165502"></script>
<script src="/templates/default/assets/js/getParserData.js?t=05032021T153915"></script>

<link rel="stylesheet" href="/templates/default/assets/css/image-uploader.css?t=16102020T165503">
<script src="/templates/default/assets/js/image-uploader.js?t=16102020T165504"></script>
<script src="/templates/default/assets/js/parameters.js?t=04022021T103520"></script>
<script>
    {literal}let parser_matches = {};{/literal}
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
    async function loadParserMatches(){
        await fetch("/controllers/parser/getData.php?inserted&id_product={$item.id}").then(response => response.json()).then(d => {
            parser_matches = d
        })
    }
    function checkCats(cats){
        let isChecked = false;
        cats.forEach(cat => {
            if (cat.checked)
                isChecked = true;return;
        });
        if (isChecked){
            cats.forEach(cat => {
                cat.required = false
            });
        } else {
            document.querySelector("input[name='cat[]'][id='none']").required = true
        }
    }
    $(window).on('load', async function(){
        setPageTitle("Edit {$item.tag}");
        ImageUploader_displayImagePreview("");
        ImageUploader_displayImagePreview("_live");
        loadParamsEdit('PropBlock','{$item.id}');
        await loadParserMatches();
        await fetch("/controllers/parser/getData.php?profiles").then(response => response.json()).then(d => {
            for (let id in d){
                if (d[id] !== "active"){
                    console.log(d)
                    let opt = document.createElement("option");
                    opt.setAttribute("value", d[id]);
                    opt.innerText = d[id];
                    document.getElementById("siteParserSelect").append(opt.cloneNode(true))

                }
            }
        })
        await fetch("/controllers/parser/getData.php?profiles_sku").then(response => response.json()).then(d => {
            for (let id in d){
                let opt = document.createElement("option");
                opt.setAttribute("value", id);
                opt.innerText = id;
                document.getElementById("siteParserSelectSku").append(opt.cloneNode(true))
            }
        })
        getParsedImages("{$item.id}")
        checkCats(cats);
    });
    document.querySelector("input[name='cat[]']").addEventListener("invalid", function (e) {
        displayAlert("Please select category", 2000, "error");
    });
    let cats = document.querySelectorAll("input[name='cat[]']");
    cats.forEach(el => {
        el.addEventListener("change", function (e) {
            checkCats(cats);
        });
    });

</script>

<script>

    function addExtraLoc() {
        let input = "<div class=\"row mt-3 border border-secondary p-2\">\n" +
                "<div class=\"col-12 col-md-4\"\">\n" +
                    "<input type=\"text\" class=\"form-control w-100 d-flex\"\n" +
                        "name=\"itemQuantityNew[]\" id=\"form17\"  placeholder=\"Quanitity\">\n" +
                "</div>\n" +
                "<div class=\"col-12 col-md-5\"\">\n" +
                    "<input type=\"text\" class=\"form-control w-100 d-flex\"\n" +
                        "name=\"itemLocationNew[]\" id=\"form17\" placeholder=\"Location\">\n" +
                "</div>\n" +
                "<div class=\"col-12 col-md-3\">\n" +
                    "<select class=\"custom-select\" name=\"loc_type_new[]\">\n" +
                    "{foreach $location_types as $loc_typ}\n" +
                        "<option value=\"{$loc_typ.id}\"\n" +
                                "{if $loc_typ.id==$loc.id_type} selected{/if}>\n" +
                            "{$loc_typ.name}\n" +
                        "</option>\n" +
                    "{/foreach}\n" +
                    "</select>\n" +
                "</div>\n" +
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
                "           name=\"itemPrice[]\"\n" +
                "           placeholder=\"Item price\">\n" +
                "</div>\n" +
                "<div class=\"col-sm-12 col-md-2\">\n" +
                "    <input type=\"number\" step=\"0.01\" class=\"form-control\"\n" +
                "           name=\"itemPriceVAT[]\"\n" +
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
