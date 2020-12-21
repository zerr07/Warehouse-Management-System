{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12 mt-2" id="alertBox"></div>
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
        <input class="form-control w-100" type="datetime-local" id="FromTime"><div class="" id="FromTimeFeedback"></div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 mt-2">
        <input class="form-control w-100" type="datetime-local" id="TillTime"><div class="" id="TillTimeFeedback"></div>
    </div>
    <div class="col-12 col-sm-12 mt-2">
        <select class="custom-select" onchange="setList(this)" id="list_select">
            {foreach $FB_list as $key => $value}
                <option value="{$key}">{$value}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-12 col-sm-12 col-md-6 mt-2">

        <div class="form-row">
            <div class="col" id="OutputProductForm">
                <input type="text" class="form-control w-100" aria-describedby="Product tag" list="tags" id="OutputProductInput" placeholder="Product tag">
                <div class="" id="OutputProductFeedback"></div>
                <template id="tagsTemplate"></template>
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
                <input type="text" class="form-control w-100" aria-describedby="AlbumId" id="AlbumId" placeholder="Album ID">
                <div class="" id="AlbumIdFeedback"></div>
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
        <button onclick="getAlbumsFB()" class="btn btn-info d-block w-100 mt-2">Get albums</button>
        <button onclick="setCronFB()" class="btn btn-info d-block w-100 mt-2">Reset cron</button>
        <button onclick="getCronFB()" class="btn btn-info d-block w-100 mt-2">Get cron</button>
        <button onclick="getFinishedAuctions()" class="btn btn-info d-block w-100 mt-2">Get finished auctions</button>
        <button onclick="getUserByComment()" class="btn btn-info d-block w-100 mt-2">Get user ID by comment ID</button>
        <button onclick="getServerStatus()" class="btn btn-info d-block w-100 mt-2">Get server status</button>
        <button onclick="process()" class="btn btn-info d-block w-100 mt-2">Force process requests</button>
        <button onclick="forceRemove()" class="btn btn-info d-block w-100 mt-2">Force remove requests</button>
        <button onclick="getQuota()" class="btn btn-info d-block w-100 mt-2">Get quota</button>

    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div id="info-box">

        </div>
    </div>
</div>
<script src="/templates/default/assets/js/auction_charts_init.js?d=20201117T170243"></script>
<script src="/templates/default/assets/js/d3.min.js?t=16102020T165340"></script>
<script src="/templates/default/assets/js/c3.min.js?t=16102020T165341"></script>
<link href="/templates/default/assets/css/c3.min.css?t=16102020T165344" rel="stylesheet" />

<script src="/templates/default/assets/js/moment.js"></script>
<script src="/templates/default/assets/js/FB_auctions_controls.js?d=20201218T125921"></script>
<script>
    let feedback = document.getElementById("OutputProductFeedback");
    let input = document.getElementById("OutputProductInput");
    let output = [];
    $(window).on('load', (function (){
        setPageTitle("FB control panel");
        let list = document.getElementById("list_select").value;
        getProductsToDataList();
        getOutputProduct(list);
        document.getElementById("FromTime").value = moment().add(35, "Minutes").format("YYYY-MM-DD[T]HH:mm:ss");
        document.getElementById("TillTime").value = moment().add(1, "Days").format("YYYY-MM-DD[T]18:00:00");
    }));
    function setList(el){
        getOutputProduct(el.value);
    }
    $("#addToPosting").on("click", function (){
        let list = document.getElementById("list_select").value;
        insertOutputProduct(list);
    })
    $("#postToPage").on("click", function (){
        let list = document.getElementById("list_select").value;

        let FromTime = document.getElementById("FromTime");
        let TillTime = document.getElementById("TillTime");
        let AlbumIdInput = document.getElementById("AlbumId");
        let FromTimeFeedback = document.getElementById("FromTimeFeedback");
        let TillTimeFeedback = document.getElementById("TillTimeFeedback");
        let AlbumIdFeedback = document.getElementById("AlbumIdFeedback");

        FromTime.setAttribute("class", "form-control");
        FromTimeFeedback.setAttribute("class", "");
        FromTimeFeedback.innerText = "";
        TillTime.setAttribute("class", "form-control");
        TillTimeFeedback.setAttribute("class", "");
        TillTimeFeedback.innerText = "";
        AlbumIdInput.setAttribute("class", "form-control");
        AlbumIdFeedback.setAttribute("class", "");
        AlbumIdFeedback.innerText = "";

        if (moment(FromTime.value, "YYYY-MM-DD[T]HH:mm:ss").diff(moment(), "Minutes") <= 30){
            FromTime.setAttribute("class", "form-control is-invalid");
            FromTimeFeedback.setAttribute("class", "invalid-feedback");
            FromTimeFeedback.innerText = "Start time must be no less than 30 minutes from now.";
            return ;
        }
        if (moment(TillTime.value, "YYYY-MM-DD[T]HH:mm:ss").diff(moment(), "Minutes") <= 60){
            TillTime.setAttribute("class", "form-control is-invalid");
            TillTimeFeedback.setAttribute("class", "invalid-feedback");
            TillTimeFeedback.innerText = "End time must be no less than 60 minutes from now.";
            return ;
        }
        if (AlbumIdInput.value === ""){
            AlbumIdInput.setAttribute("class", "form-control is-invalid");
            AlbumIdFeedback.setAttribute("class", "invalid-feedback");
            AlbumIdFeedback.innerText = "Empty album id";
            return ;
        }
        document.getElementById("postToPage").disabled = true;
        batchPost(list);
    })

    function getUserByComment(){
        let commentID = prompt("Enter comment ID: ");
        if (commentID){
            getCommentDetails(commentID);
        }
    }
    function getFinishedAuctions(){
        document.getElementById("info-box").innerHTML = "";
        fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&getAuctions")
            .then(response => response.json())
            .then((d) => {
                d.auctions.forEach(auc => {
                    document.getElementById("info-box").innerHTML += ""+
                        "<div class='row'>" +
                        "<div class='col-4'>" +
                        "ID: " + auc['id'] +
                        "</div> " +
                        "<div class='col-4'>" +
                        "PhotoID: " + auc['PhotoID'] +
                        "</div> " +
                        "<div class='col-4'>" +
                        "CommentID: " + auc['CommentID'] +
                        "</div> " +
                        "</div><hr>";
                });
            });
    }
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
        feedback.innerText = "Specified product is already in one of the lists!";
    }, false);
</script>
{include file='footer.tpl'}