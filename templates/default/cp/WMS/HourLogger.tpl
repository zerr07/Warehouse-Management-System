{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="col-auto" id="sessionStatusBox"></div><div class="col-auto" id="timer"></div><div class="col-auto" id="warning"></div>
        </div>
    </div>
    {if $userID == 11}
        <div class="col-12">
            <div class="row">
                {foreach $users as $key => $value}
                    <div class="col-auto"><a href="?user_id={$key}">{$value}</a></div>
                {/foreach}
            </div>
        </div>
    {/if}

    <div class="col-6">
        <button type="button" class="btn btn-outline-info w-100" style="height: 100px" onclick="CheckIn()" id="CheckIn" disabled><i class="fas fa-sign-in-alt"></i> Check In</button>
    </div>
    <div class="col-6">
        <button type="button" class="btn btn-outline-info w-100" style="height: 100px" onclick="CheckOut()" id="CheckOut" disabled><i class="fas fa-sign-out-alt"></i> Check Out</button>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <p>Total for period: {$TotalForPeriod.hours}:{$TotalForPeriod.minutes}:{$TotalForPeriod.seconds}</p>
    </div>
    <div class="col-8 col-sm-8 col-md-10 p-0">
        <input type="text" name="dates" class="form-control" placeholder="Date range" value="{$date1} - {$date2}" >

    </div>
    <div class="col-4 col-sm-4 col-md-2 my-auto pr-0">
        <a class="btn btn-outline-danger w-100" href="/cp/WMS/HourLogger.php">Reset</a>
    </div>
</div>
<div class="row mt-3">
    <div class="accordion text-left  w-100" id="accordion">
    {foreach $HourLoggerSessions as $key => $value}
        <div class="card">
            <div class="card-header" id="heading{$key}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapse{$key}" aria-expanded="false" aria-controls="collapse{$key}">
                        {$value.date_check_in}
                    </button>
                </h2>
            </div>
            <div id="collapse{$key}" class="collapse" aria-labelledby="heading{$key}" data-parent="#accordion" data-load="{$key}" data-user-id="{$HourLoggerUserID}">
                <div class="card-body">
                    <div class="row"></div>
                </div>
            </div>
        </div>
    {/foreach}
    </div>
</div>
<script src="/templates/default/assets/js/moment.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
    let googleApi = "{$engine.google_api}";
    {literal}
    window.addEventListener("load", function () {
        setPageTitle("Hour Logger");
    });
    $('input[name="dates"]').daterangepicker();
    $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        window.location.href = "?user_id={/literal}{$HourLoggerUserID}{literal}&between=" + encodeURIComponent($('input[name="dates"]').val() + "");

    });
    $('.collapse').on('show.bs.collapse', function () {
        console.log("/controllers/HourLogger/HourLoggerController.php?getSession="+this.getAttribute("data-load")+"&getSessionUserID="+this.getAttribute("data-user-id"));
        fetch("/controllers/HourLogger/HourLoggerController.php?getSession="+this.getAttribute("data-load")+"&getSessionUserID="+this.getAttribute("data-user-id")).then(responce => responce.json())
        .then(d => {
            let dataBlock = this.firstElementChild.firstElementChild;
            let diff = moment(d.date_check_out,"YYYY-MM-DD HH:mm:ss").diff(moment(d.date_check_in,"YYYY-MM-DD HH:mm:ss"));
            let hours = Math.floor(diff/1000/60/60);
            let ip_in = JSON.parse(d.ip);
            let ip_out = JSON.parse(d.ip_out);
            let device;
            let device_out;
            if (d.mobile === "0"){
                device = "<i class='fas fa-desktop' style='height: 32px; width: auto'></i>";
            } else {
                device = "<i class='fas fa-mobile' style='height: 32px; width: auto'></i>";
            }
            if (d.mobile_out === "0"){
                device_out = "<i class='fas fa-desktop' style='height: 32px; width: auto'></i>";
            } else {
                device_out = "<i class='fas fa-mobile' style='height: 32px; width: auto'></i>";
            }
             dataBlock.innerHTML = "<div class='col-12 col-sm-12 col-md-6'>" +
                "<div class='row'>" +
                "<div class='col-4'>Checked in:  </div><div class='col-8'>" + moment(d.date_check_in,"YYYY-MM-DD HH:mm:ss").format("DD.MM.YYYY HH:mm:ss")+"</div>" +
                "<div class='col-4'>Accuracy in: </div><div class='col-8'>"+d.accuracy+"</div>" +
                "<div class='col-4'>IP in: </div><div class='col-8'>"+ip_in.REMOTE_ADDR+"</div>" +
                "<div class='col-4'>Device :</div><div class='col-8'>"+device+"</div>" +
                "</div>" +
                "</div>";
            dataBlock.innerHTML += "<div class='col-12 col-sm-12 col-md-6'>" +
                "<div class='row'>" +
                "<div class='col-4'>Checked out: </div><div class='col-8'>" + moment(d.date_check_out,"YYYY-MM-DD HH:mm:ss").format("DD.MM.YYYY HH:mm:ss")+"</div>" +
                "<div class='col-4'>Accuracy out: </div><div class='col-8'>"+d.accuracy_out+"</div>" +
                "<div class='col-4'>IP out: </div><div class='col-8'>"+ip_out.REMOTE_ADDR+"</div>" +
                "<div class='col-4'>Device :</div><div class='col-8'>"+device_out+"</div>" +
                "</div>" +
                "</div>";
            dataBlock.innerHTML += "<div class='col-12 col-sm-12 col-md-6'>" +
                "<div class='row'>" +
                "<div class='col-4'>Duration: </div><div class='col-8'>"+hours+":"+moment.utc(diff).format('mm:ss')+"</div>" +
                "</div>" +
                "</div>";
            dataBlock.innerHTML += "<div class='col-12 col-sm-12 col-md-6'>" +
                "</div>";
            dataBlock.innerHTML += "<div class='col-12 col-sm-12 col-md-6'>" +
                "<iframe  frameborder='0' style='border:0; width: 100%; height: 200px'" +
                "  src='https://www.google.com/maps/embed/v1/place?key="+googleApi+"&q="+d.latitude+","+d.longitude+"' allowfullscreen>" +
                "</iframe>" +
                "</div>" +
                "<div class='col-12 col-sm-12 col-md-6'>" +
                "<iframe  frameborder='0' style='border:0; width: 100%; height: 200px'" +
                "  src='https://www.google.com/maps/embed/v1/place?key="+googleApi+"&q="+d.latitude_out+","+d.longitude_out+"' allowfullscreen>" +
                "</iframe>" +
                "</div>"
           console.log(d);
        });
    })
    getHourLoggerSession().then(r => {
        if (r){
            if (r.hasOwnProperty("error")){
                document.getElementById("sessionStatusBox").innerText = "Error occurred: "+r.error;
                document.getElementById("CheckIn").disabled = true;
                document.getElementById("CheckOut").disabled = true;
            } else {
                document.getElementById("sessionStatusBox").innerText = "Active session found, started: "+r.date_check_in;
                document.getElementById("CheckIn").disabled = true;
                document.getElementById("CheckOut").disabled = false;
                let timerTick =function () {
                    let diff = moment().diff(moment(r.date_check_in,"YYYY-MM-DD HH:mm:ss"));
                    let hours = Math.floor(diff/1000/60/60)
                    if (hours >= 24){
                        document.getElementById("warning").innerText = "Whoah! Your shift is more that 24 hours! Mind take a break?";
                    }
                    document.getElementById("timer").innerText = "Time passed: "+hours+":"+moment.utc(diff).format('mm:ss');
                }
                setInterval(timerTick, 1000);
                timerTick();

            }

        } else {
            document.getElementById("sessionStatusBox").innerText = "No active sessions";
            document.getElementById("CheckIn").disabled = false;
            document.getElementById("CheckOut").disabled = true;
        }
    });
    function CheckIn(){
        if (navigator.geolocation) {
            let options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };

            function success(d) {
                let mobile = false;
                if('ontouchstart' in window){
                    mobile = true;
                }
                const raw = JSON.stringify(
                    {
                        "checkIn": true,
                        "latitude": d.coords.latitude,
                        "longitude": d.coords.longitude,
                        "user_id": getCookie("user_id"),
                        "mobile": mobile,
                        "accuracy": d.coords.accuracy
                    }
                );
                if (d.coords.accuracy > 5000) {
                    alert("Geolocation accuracy is more that 5km.")
                }
                const requestOptions = {
                    method: 'POST',
                    headers:  new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: raw
                };
                console.log(raw);
                fetch("/controllers/HourLogger/HourLoggerController.php", requestOptions)
                    .then(response => response.json())
                    .then(resp => {
                        if (resp.hasOwnProperty("error")){
                            alert(resp.error);
                        } else if (resp.hasOwnProperty("success")) {
                            document.getElementById("CheckIn").disabled = true;
                            document.getElementById("CheckOut").disabled = false;
                            console.log(d.coords.accuracy);
                            location.reload();
                        }
                    });
            }

            function error(err) {
                console.warn(`ERROR(${err.code}): ${err.message}`);
            }
            navigator.geolocation.getCurrentPosition(success, error, options);

        } else {
            alert("Could not get geolocation.");
        }
    }
    function CheckOut(){
        if (navigator.geolocation) {
            var options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };

            function success(d) {
                let mobile = false;
                if('ontouchstart' in window){
                    mobile = true;
                }
                const raw = JSON.stringify(
                    {
                        "checkOut": true,
                        "latitude": d.coords.latitude,
                        "longitude": d.coords.longitude,
                        "user_id": getCookie("user_id"),
                        "mobile": mobile,
                        "accuracy": d.coords.accuracy
                    }
                );
                const requestOptions = {
                    method: 'POST',
                    headers:  new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: raw
                };
                if (d.coords.accuracy > 5000) {
                    alert("Geolocation accuracy is more that 5km.")
                }
                console.log(raw);
                fetch("/controllers/HourLogger/HourLoggerController.php", requestOptions)
                    .then(response => response.json())
                    .then(resp => {
                        console.log(resp)
                        if (resp.hasOwnProperty("error")){
                            alert(resp.error);
                        } else if (resp.hasOwnProperty("success")) {
                            document.getElementById("CheckOut").disabled = true;
                            document.getElementById("CheckIn").disabled = false;
                            console.log(d.coords.accuracy);
                            location.reload();

                        }
                    });
            }

            function error(err) {
                console.warn(`ERROR(${err.code}): ${err.message}`);
            }

            navigator.geolocation.getCurrentPosition(success, error, options);
        } else {
            alert("Could not get geolocation.");
        }
    }
</script>{/literal}
{include file='footer.tpl'}
