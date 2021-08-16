{include file='header.tpl'}
<form action="#" method="get" id="searchForm">
    <div class="row mt-4">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 mt-3 mt-lg-0">
            <input type="text" class="form-control w-100" name="searchIDorBarcode" id="searchIDorBarcode" list="searchIDorBarcodeList" placeholder="Search by ID or Barcode" {if isset($searchIDorBarcode)}value="{$searchIDorBarcode}" {/if} autofocus>
            <template id="searchIDorBarcodeListTemplate"></template>
            <datalist id="searchIDorBarcodeList"></datalist>
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
            <button type="button" class="btn btn-info w-100" value="Search" name="searchShippings" id="searchShippings">Search</button>
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

            <div class="row mt-3 border border-secondary p-1" id="{$item.id}">
                <div class="col-2 col-sm-2    m-auto   col-md-2     col-lg-2   col-xl-1">{$item.id}</div>
                <div class="col-10 col-sm-10  m-auto   col-md-6     col-lg-3   col-xl-3 text-truncate">{$item.comment}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-4     col-lg-3   col-xl-2">{$item.date}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-2   col-xl-1 text-truncate" title="{$item.status}">{$item.status}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-2   col-xl-1 text-truncate" title="{$item.type}">{$item.type}</div>
                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-12  col-xl-4 d-flex justify-content-center">
                    <button type="button" class="btn btn-link" style="color: gray; opacity: 0.1" onclick="setWarning('{$item.id}')"><i class="fas fa-exclamation-triangle"></i></button>
                    <button type="button" class="btn btn-link" style="color: gray; opacity: 0.1;" onclick="setPaid('{$item.id}')"><i class="fas fa-wallet"></i></button>

                    <a class="btn btn-outline-primary w-100" href="/cp/POS/shipping/index.php?view={$item.id}" >
                        <i class="fas fa-link"></i>
                        View
                    </a>
                    <a class="btn btn-outline-info w-100 ml-2 mr-2" href="/cp/POS/reserve/loadReservationInCart.php?id={$item.id}" >
                        <i class="fas fa-link"></i>
                        Load in POS
                    </a>
                    <a class="btn btn-outline-danger w-100" href="/cp/POS/reserve/index.php?cancelFullShip={$item.id}">
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
<script src="/templates/default/assets/js/warning.js?d=20210114T155449"></script>

<script>

    window.addEventListener("load", function () {
        setPageTitle("Shippling list");

        fetch("/cp/POS/reserve/reserve.php?getReservationDataList=2")
            .then(response => response.json())
            .then((d) => {
                let datalist = document.getElementById("searchIDorBarcodeListTemplate");
                Object.keys(d).forEach(k => {
                    let el = document.createElement("option");
                    el.setAttribute("value", d[k]);
                    el.setAttribute("data-id", k);
                    el.innerText = d[k];
                    datalist.appendChild(el);
                })
            }).finally(function () {
            LimitDataList(document.getElementById("searchIDorBarcode"),
                document.getElementById("searchIDorBarcodeList"),
                document.getElementById("searchIDorBarcodeListTemplate"), 5);
        });

        const requestParams = {
            method: "POST",
            headers: new Headers({
                "Content-Type": "application/json"
            }),
            body: JSON.stringify({
                get: "2",
            })
        };
        const requestParams1 = {
            method: "POST",
            headers: new Headers({
                "Content-Type": "application/json"
            }),
            body: JSON.stringify({
                get_paid: "2",
            })
        };
        fetch("/cp/POS/reserve/notifications.php", requestParams1)
        .then(response => response.json())
        .then((d) => {

            Object.keys(d).forEach(el => {
                if(document.querySelector("button[onclick=\"setPaid('"+el+"')\"]")){
                    enableWarning(document.querySelector("button[onclick=\"setPaid('"+el+"')\"]"), d[el].comment, d[el].user, "green")
                }
            });
        });
        fetch("/cp/POS/reserve/notifications.php", requestParams)
            .then(response => response.json())
            .then((d) => {

                Object.keys(d).forEach(el => {
                    if(document.querySelector("button[onclick=\"setWarning('"+el+"')\"]")){
                        enableWarning(document.querySelector("button[onclick=\"setWarning('"+el+"')\"]"), d[el].comment, d[el].user, "red")
                    }
                });
            });
    });
    document.getElementById("searchShippings").addEventListener("click", function () {
        let nameSearch = document.getElementById("searchIDorBarcode");
        let nameID = document.querySelector("datalist[id='searchIDorBarcodeList'] > option[value='"+nameSearch.value+"']");
        if (nameID){
            window.location.href = "/cp/POS/shipping/index.php?view="+nameID.getAttribute("data-id");
        } else {
            document.getElementById("searchForm").submit();
        }
    });
</script>
{include file='pagination.tpl'}
{include file='footer.tpl'}