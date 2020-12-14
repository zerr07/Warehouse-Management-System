{include file='header.tpl'}

            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    {if $reservedList|@count == 0}
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 50px;">
                                <p>Nothing Found</p>
                                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                            </div>
                        </div>
                    {else}
                    {include file='cp/POS/reserve/mergeToolModal.tpl'}
                        <div class="row mt-4">
                            <div class="col-10 col-sm-10 col-md-3">
                                <input type="text" class="form-control" id="reservationsSearch" list="reservationsSearchList" placeholder="Reservation comment or ID"><div class='' id='reservationsSearchFeedback'></div>
                                <datalist id="reservationsSearchList">
                                    {foreach $reservationsDatalist as $key => $value}
                                        <option value="{$value}" data-id="{$key}">{$value}</option>
                                    {/foreach}
                                </datalist>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-primary" onclick="goToReservation()">Go to</button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-3 offset-md-4">
                                <button type="button" class="btn btn-secondary w-100" data-toggle="modal" data-target="#mergeToolModal">Merge tool</button>
                            </div>
                        </div>
                        {foreach $reservedList as $item}
                            <div class="row mt-3 border border-secondary p-1">
                                <div class="col-2 col-sm-2    m-auto   col-md-2     col-lg-2   col-xl-1">{$item.id}</div>
                                <div class="col-10 col-sm-10  m-auto   col-md-6     col-lg-6   col-xl-4 text-truncate">{$item.comment}</div>
                                <div class="col-12 col-sm-12  m-auto   col-md-4     col-lg-4   col-xl-3">{$item.date}</div>
                                <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-12  col-xl-4 d-flex justify-content-center">
                                    <button type="button" class="btn btn-link" style="color: gray; opacity: 0.1" onclick="setWarning('{$item.id}')"><i class="fas fa-exclamation-triangle"></i></button>

                                    <a class="btn btn-outline-primary w-100" href="/cp/POS/reserve/index.php?view={$item.id}" >
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
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
<script src="/templates/default/assets/js/warning.js?d=20201214T162620"></script>
<script>
    window.addEventListener("load", function () {
        const requestParams = {
            method: "POST",
            headers: new Headers({
                "Content-Type": "application/json"
            }),
            body: JSON.stringify({
                get: "1",
            })
        };
        fetch("/cp/POS/reserve/addWarning.php", requestParams)
            .then(response => response.json())
            .then((d) => {

                Object.keys(d).forEach(el => {
                    console.log("button[onclick=\"setWarning('"+el+"')\"]");
                    enableWarning(document.querySelector("button[onclick=\"setWarning('"+el+"')\"]"), d[el].comment, d[el].user)
                });
            });
    });
    function goToReservation(){
        let reservationsSearch   = document.getElementById("reservationsSearch");
        reservationsSearch.setAttribute("class", "form-control");
        let reservationsSearchFeedback   = document.getElementById("reservationsSearchFeedback");
        let reservationID = document.querySelector("datalist[id='reservationsSearchList'] > option[value='"+reservationsSearch.value+"']");
        if (reservationID){
            window.location.href = "/cp/POS/reserve/index.php?view="+reservationID.getAttribute("data-id");
        } else {
            reservationsSearch.setAttribute("class", "form-control is-invalid");
            reservationsSearchFeedback.setAttribute("class", "invalid-feedback");
            reservationsSearchFeedback.innerText = "Invalid data provided!";
        }
    }
</script>
{include file='pagination.tpl'}
{include file='footer.tpl'}