{include file='header.tpl'}
<form action="#" method="get">
    <div class="row mt-4">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mt-3 mt-lg-0">
            <input type="text" class="form-control w-100" name="searchIDorBarcode" id="form17" placeholder="Search by ID or Barcode" autofocus>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2 mt-3 mt-lg-0">
            {if isset($onlyCheckedOut)}
                <input type="hidden" name="onlyCheckedOut" value="true">
                <button type="button" onclick="goToUrl('/cp/POS/shipping/')" class="btn btn-info w-100">Standart list</button>
            {else}
                <button type="button" onclick="goToUrl('/cp/POS/shipping/?onlyCheckedOut=true')" class="btn btn-info w-100">Only checked out</button>
            {/if}
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2 mt-3 mt-lg-0">
            <input type="submit" class="btn btn-info w-100" value="Search" name="searchShippings">
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-4 mt-3">
            {if !isset($onlyCheckedOut)}
                {foreach $statusList as $status}
                    {if $status.id !== "6"}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="statusSearch[{$status.id}]" value="{$status.id}" class="custom-control-input" id="customSwitchStatus{$status.id}" {if isset($statusToggled)}{if {$status.id}|in_array:$statusToggled}checked{/if}{/if}>
                            <label class="custom-control-label" for="customSwitchStatus{$status.id}">{$status.name}</label>
                        </div>
                    {/if}

                {/foreach}
            {/if}
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-4 mt-3">
            {if !isset($onlyCheckedOut)}
                {foreach $typeList as $type}
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="typeSearch[{$type.id}]" value="{$type.id}" class="custom-control-input" id="customSwitchType{$type.id}" {if isset($typeToggled)}{if {$type.id}|in_array:$typeToggled}checked{/if}{/if}>
                        <label class="custom-control-label" for="customSwitchType{$type.id}">{$type.name}</label>
                    </div>
                {/foreach}
            {/if}
        </div>

    </div>
</form>
<div class="row mt-3">
    <div class="col-md-12" style="white-space: nowrap;">
        {if $reservedList|@count == 0}
        <div class="row">
            <div class="col-auto mx-auto" style="margin-top: 50px;">
                <p>Nothing Found</p>
                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
            </div>
        </div>
        {else}


        {foreach $reservedList as $item}

            <div class="row mt-3 border border-secondary p-1">
                <div class="col-2 col-sm-2    m-auto   col-md-2     col-lg-2   col-xl-1">{$item.id}</div>
                <div class="col-10 col-sm-10  m-auto   col-md-6     col-lg-3   col-xl-3 text-truncate">{$item.comment}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-4     col-lg-3   col-xl-2">{$item.date}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-2   col-xl-1 text-truncate" title="{$item.status}">{$item.status}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-2   col-xl-1 text-truncate" title="{$item.type}">{$item.type}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-12  col-xl-4 d-flex justify-content-center">
                    <a class="btn btn-outline-primary w-100" href="/cp/POS/shipping/index.php?view={$item.id}" >
                        <i class="fas fa-link"></i>
                        View
                    </a>
                    <a class="btn btn-outline-info w-100 ml-2 mr-2" href="/cp/POS/reserve/loadReservationInCart.php?id={$item.id}" >
                        <i class="fas fa-link"></i>
                        Load in POS
                    </a>
                    <a class="btn btn-outline-danger w-100" href="/cp/POS/reserve/index.php?cancelFull={$item.id}">
                        <i class="fas fa-frown"></i>
                        Cancel
                    </a>
                </div>
            </div>
        {/foreach}
    </div>
    {/if}
    <a class="btn btn-primary mt-3" style="display: inline-block; float:right;" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
</div>
</div>

{include file='footer.tpl'}