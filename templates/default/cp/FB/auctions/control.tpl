{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12 mt-2">
        Photos uploaded
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="PhotoProgress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>
    <div class="col-12 mt-2">
        Scheduled posts
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="ScheduleProgress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 mt-2">

        <div class="form-row">
            <div class="col" id="OutputProductForm">
                <input type="text" class="form-control w-100" aria-describedby="Product tag" list="tags" id="OutputProductInput">
                <div class="" id="OutputProductFeedback"></div>
                <datalist id="tags"></datalist>
            </div>
            <div class="col">
                <button type="button" class="btn btn-info w-100" id="addToPosting">Add</button>
            </div>
        </div>

    </div>
    <div class="col-12 col-sm-12 col-md-6  mt-2">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control w-100" aria-describedby="AlbumId" id="AlbumId">
            </div>
            <div class="col">
                <button type="button" class="btn btn-info w-100" id="postToPage">Post to page</button>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 mt-2 mt-sm-2 mt-md-2">
        <div class="accordion" id="OutputAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#OutputCollapse" aria-expanded="true" aria-controls="OutputCollapse">
                            Output products
                        </button>
                    </h2>
                </div>

                <div id="OutputCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#OutputAccordion">
                    <div class="card-body" id="OutputProducts">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-3">
        <button onclick="getAlbumsFB()" class="btn btn-info d-block w-100 mt-2">Get Albums</button>
        <button onclick="setCronFB()" class="btn btn-info d-block w-100 mt-2">Reset cron</button>
        <button onclick="getCronFB()" class="btn btn-info d-block w-100 mt-2">Get cron</button>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div id="info-box">

        </div>
    </div>
</div>
<script src="/templates/default/assets/js/auction_charts_init.js?d=20201112T103709"></script>
<script src="/templates/default/assets/js/d3.min.js?t=16102020T165340"></script>
<script src="/templates/default/assets/js/c3.min.js?t=16102020T165341"></script>
<link href="/templates/default/assets/css/c3.min.css?t=16102020T165344" rel="stylesheet" />

<script src="/templates/default/assets/js/moment.js"></script>
<script src="/templates/default/assets/js/FB_auctions_controls.js?d=20201111T161317"></script>
<script>
    let feedback = document.getElementById("OutputProductFeedback");
    let input = document.getElementById("OutputProductInput");
    let output = [];
    $(window).on('load', (function (){
        getProductsToDataList();
        getOutputProduct();
    }));
    $("#addToPosting").on("click", function (){
        insertOutputProduct();
    })
    $("#postToPage").on("click", function (){
        batchPost();
    })
    document.addEventListener('FB_a_insert_success', function (e) {
        input.setAttribute("class", "form-control is-valid");
        feedback.setAttribute("class", "valid-feedback");
        feedback.innerText = "Success!";
    }, false);
    document.addEventListener('FB_a_insert_empty', function (e) {
        input.setAttribute("class", "form-control is-invalid");
        feedback.setAttribute("class", "invalid-feedback");
        feedback.innerText = "Please specify output product!";
    }, false);
    document.addEventListener('FB_a_insert_used', function (e) {
        input.setAttribute("class", "form-control is-invalid");
        feedback.setAttribute("class", "invalid-feedback");
        feedback.innerText = "Specified product is already in the list!";
    }, false);
</script>
{include file='footer.tpl'}