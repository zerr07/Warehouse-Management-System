let eventInsertSuccess = document.createEvent('Event');
eventInsertSuccess.initEvent('FB_a_insert_success', true, true);
let eventInsertEmpty = document.createEvent('Event');
eventInsertEmpty.initEvent('FB_a_insert_empty', true, true);
let eventInsertUsed = document.createEvent('Event');
eventInsertUsed.initEvent('FB_a_insert_used', true, true);
//let domain = "http://95.217.217.222:8080";
let domain = "http://localhost:8080";
function getProductsToDataList(){

    fetch("/api/FB/getProductsJson.php?username=aztrade&password=Zajev123")
        .then(response => response.json())
        .then((d) => {
            d.tags.forEach(element => {
                var e = document.createElement("option");
                e.value = element;
                document.getElementById("tags").appendChild(e);
            });
        });
}
function insertOutputProduct(list){
    let feedback = document.getElementById("OutputProductFeedback");
    let input = document.getElementById("OutputProductInput");

    input.setAttribute("class", "form-control");
    feedback.setAttribute("class", "");
    feedback.innerText = "";

    let v = document.getElementById("OutputProductInput").value;
    let nameSearch = document.getElementById("OutputProductInput");
    let nameID = document.querySelector("datalist[id='tags'] > option[value='"+nameSearch.value+"']");
    if (nameID){
        input.setAttribute("class", "form-control is-valid");
        feedback.setAttribute("class", "valid-feedback");
        feedback.innerText = "Success!";
    } else {
        input.setAttribute("class", "form-control is-invalid");
        feedback.setAttribute("class", "invalid-feedback");
        feedback.innerText = "Invalid tag!";
        return;
    }
    document.getElementById("OutputProductInput").value = "";

    if (v !== ""){
        fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&insert="+v+"&id="+list)
            .then(response => response.json())
            .then((d) => {
                if (d.resp === "keyExists"){
                    document.dispatchEvent(eventInsertUsed);
                } else {
                    document.dispatchEvent(eventInsertSuccess);
                }
            })
            .finally(function () {
                let list = document.getElementById("list_select").value;

                getOutputProduct(list);
            });
    } else {
        document.dispatchEvent(eventInsertEmpty);
    }
}

function deleteOutputProduct(tag){
    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&delete=" + tag)
        .finally(function () {
            let list = document.getElementById("list_select").value;

            getOutputProduct(list);
        });
}

function getOutputProduct(list_id){
    document.getElementById("OutputProducts").innerHTML = "";

    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&get="+list_id)
        .then(response => response.json())
        .then((d) => {
            console.log(d.tags);

            d.tags.forEach(element => {
                let div = document.createElement("div");
                div.setAttribute("class", "row border border-secondary p-2");
                let img_div = document.createElement("div");
                img_div.setAttribute("class", "col-2 mt-auto mb-auto");
                let img = document.createElement("img");
                img.setAttribute("src", "/uploads/images/products/"+element.image);
                img.setAttribute("style", "max-height: 32px;width: auto;");
                img.setAttribute("class", "mr-auto ml-auto d-flex");

                let e = document.createElement("div");
                e.setAttribute("class", "col-2 mt-auto mb-auto");
                let e1 = document.createElement("div");
                e1.setAttribute("class", "col-2 mt-auto mb-auto");
                e.innerText = element.tag;
                e1.innerText = "Available : " + element.quantity;
                img_div.appendChild(img);
                div.appendChild(img_div);
                div.appendChild(e);
                div.appendChild(e1);
                document.getElementById("OutputProducts").appendChild(div);
                div.innerHTML +=
                    "<div class='col-4'> " +
                    "<button type='button' class='btn btn-link' onclick='loadAuctionCharts(\""+element.tag+"\")'><i class='fas fa-ad'></i> View auction charts</button>" +
                    "<div id='auction_charts'></div>" +
                    "</div>"+
                    "<div class='col-1 mt-auto mb-auto' id='warning"+element.tag+"'> " +
                    "</div>" +
                    "<div class='col-1'> " +
                    "<button type='button' class='btn btn-link' onclick='deleteOutputProduct(\""+element.tag+"\")'><i class='fas fa-trash'></i></button>" +
                    "</div>";
                if (element.desc === ""){
                    document.getElementById("warning"+element.tag).innerHTML = "<i class='fas fa-exclamation' data-toggle='tooltip' data-placement='top' style='color: #ff0000' title='Empty description'></i>";
                }
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
                document.querySelectorAll("svg[data-toggle='tooltip'] > title").forEach(el=>{
                    el.parentNode.removeChild(el);
                });
            })
        });
}

function setScheduleProgress(v){
    document.getElementById("ScheduleProgress").setAttribute("aria-valuenow", v);
    document.getElementById("ScheduleProgress").setAttribute("style", "width: "+v+"%");
}
function setPhotoProgress(v){
    document.getElementById("PhotoProgress").setAttribute("aria-valuenow", v);
    document.getElementById("PhotoProgress").setAttribute("style", "width: "+v+"%");
}
var count, incrementor;
function batchPost(list){
    let v = document.getElementById("AlbumId").value;
    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&get="+list)
        .then(response => response.json())
        .then(async (d) => {
            count = 0;
            setPhotoProgress(count);
            setScheduleProgress(count);

            incrementor = 100/d.tags.length;
            let SubmittedStartDate      = moment(document.getElementById("FromTime").value, "YYYY-MM-DD[T]HH:mm:ss");
            let SubmittedEndDate        = moment(document.getElementById("TillTime").value, "YYYY-MM-DD[T]HH:mm:ss");
            console.log(SubmittedStartDate);
            console.log(SubmittedEndDate);
            let AucCount                = d.tags.length;
            let FromTime                = 6;    // Hour of day
            let TillTime                = 24;   // Hour of day
            let MinTimePeriod           = 15;   // Minutes
            let ActualStartDate;
            let ActualEndDate;

            let CurrDate            = moment();
            let TempDate            = moment(SubmittedStartDate     , "YYYY-MM-DD HH:mm:ss");
            SubmittedStartDate      = moment(SubmittedStartDate     , "YYYY-MM-DD HH:mm:ss");
            SubmittedEndDate        = moment(SubmittedEndDate       , "YYYY-MM-DD HH:mm:ss");
            console.log("End and start difference:" , SubmittedEndDate.diff(SubmittedStartDate, "minutes"), "minutes");
            // A difference between start date and end date should be not more that 1 hour
            // A difference between start date and current date should be more that 30 minutes
            // Time between posts should be more than 10 minutes
            // If all above conditions apply then start posting at specified time.
            // If time is between 06:00:00 and 24:00:00 (example)
            let LowerBoundMonth = parseInt(SubmittedStartDate.month())+1;
            let UpperBoundMonth = parseInt(SubmittedEndDate.month())+1;
            console.log(SubmittedStartDate.year()+"-"+LowerBoundMonth+"-"+SubmittedStartDate.date()+" "+"00:00:00");

            let FirstBoundCheckLower    = moment(SubmittedStartDate.year()+"-"+LowerBoundMonth+"-"+SubmittedStartDate.date()+" "+"00:00:00", "YYYY-MM-DD HH:mm:ss").add(-1, "second");
            let FirstBoundCheckUpper    = moment(SubmittedStartDate.year()+"-"+LowerBoundMonth+"-"+SubmittedStartDate.date()+" "+FromTime+":00:00", "YYYY-MM-DD HH:mm:ss");
            let SecondBoundCheckLower   = moment(SubmittedEndDate.year()+"-"+UpperBoundMonth+"-"+SubmittedEndDate.date()+" "+TillTime+":00:00", "YYYY-MM-DD HH:mm:ss");
            let SecondBoundCheckUpper   = moment(SubmittedEndDate.year()+"-"+UpperBoundMonth+"-"+SubmittedEndDate.date()+" "+"24:00:00", "YYYY-MM-DD HH:mm:ss");
            console.log("End and start difference:" , FirstBoundCheckLower.diff(SubmittedStartDate, "minutes"), "minutes");

            if (SubmittedStartDate.isBetween(FirstBoundCheckLower,FirstBoundCheckUpper)){
                ActualStartDate = moment(SubmittedStartDate.year()+"-"+LowerBoundMonth+"-"+SubmittedStartDate.date()+" "+FromTime+":00:00", "YYYY-MM-DD HH:mm:ss");
            } else {
                ActualStartDate = SubmittedStartDate;
            }

            if (SubmittedEndDate.isBetween(SecondBoundCheckLower,SecondBoundCheckUpper)){
                ActualEndDate = moment(SubmittedEndDate.year()+"-"+LowerBoundMonth+"-"+SubmittedEndDate.date()+" "+TillTime+":00:00", "YYYY-MM-DD HH:mm:ss");
            } else {
                ActualEndDate = SubmittedEndDate;
            }
            let DaysDiff = ActualEndDate.diff(ActualStartDate, "days");
            let hours = (TillTime-ActualStartDate.hour())+(ActualEndDate.hour()-FromTime);
            if (DaysDiff >= 2){
                for(let i = 2; i <= DaysDiff; i++){
                    hours += TillTime - FromTime
                }
            }

            if(ActualStartDate.diff(CurrDate, "minutes") >= 30 && ActualEndDate.diff(ActualStartDate, "minutes") >= 60){
                ActualEndDate = ActualEndDate.add(-60, "minutes");
                let TimeBetween = hours*60/AucCount;
                console.log("Time between:" , TimeBetween , "minutes");
                if (TimeBetween >= MinTimePeriod){
                    for (const element of d.tags){
                        TempDate = TempDate.add(TimeBetween, "minutes");
                        if (TempDate.isBetween(
                            moment(TempDate.year()+"-"+(parseInt(TempDate.month())+1)+"-"+TempDate.date()+" 00:00:00", "YYYY-MM-DD HH:mm:ss"),
                            moment(TempDate.year()+"-"+(parseInt(TempDate.month())+1)+"-"+TempDate.date()+" "+FromTime+":00:00", "YYYY-MM-DD HH:mm:ss")
                        )){
                            TempDate.hour(FromTime);
                            TempDate.minute("00");
                            TempDate.second("00");
                        }
                        if (TempDate.isBetween(
                            moment(TempDate.year()+"-"+(parseInt(TempDate.month())+1)+"-"+TempDate.date()+" "+TillTime+":00:00", "YYYY-MM-DD HH:mm:ss"),
                            moment(TempDate.year()+"-"+(parseInt(TempDate.month())+1)+"-"+TempDate.date()+" 24:00:00", "YYYY-MM-DD HH:mm:ss")
                        )){
                            TempDate.add(1, "Days");
                            TempDate.hour(FromTime);
                            TempDate.minute("00");
                            TempDate.second("00");
                        }
                        count += incrementor;
                        console.log(TempDate.format("YYYY-MM-DD HH:mm:ss"))
                        console.log(element)
                        await postPhotoToFB(v, element.image, element.desc, TempDate.format("YYYY-MM-DD HH:mm:ss"), SubmittedEndDate.format("YYYY-MM-DD HH:mm:ss"));

                    }
                } else {
                    console.log("Too little time specified for this amount of auctions.")
                }
            } else {
                console.log("Time period invalid.");
            }
            ActualEndDate = ActualEndDate.add(60, "minutes");

            console.log("End and start difference:" , ActualStartDate.diff(ActualEndDate, "minutes"), "minutes");
        })
        .finally(function () {
            document.getElementById("postToPage").disabled = false;
        });
}
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
async function postPhotoToFB(AlbumID, PhotoURL, Caption, pubTime, EndTime){
    const requestOptions = {
        method: "POST",
        headers:  new Headers({
            'Content-Type': 'application/json'
        }),
        body: JSON.stringify({
            AlbumID: AlbumID,
            ImgUrl: "http://cp.azdev.eu/uploads/images/products/"+PhotoURL,
            Caption: Caption,
            FinishTime: pubTime,
            EndTime: EndTime
        })
    };
    return fetch(domain+"/postPhotoToAlbum", requestOptions)
        .then(response => response.json())
        .then(async (d) => {
            setPhotoProgress(count);
            console.log("Posted...maybe...see response");
            await schedulePost(d.id, Caption, AlbumID, pubTime)
        });
}
async function schedulePost(PhotoID, Message, AlbumID, PubTime){
    const requestOptions = {
        method: "POST",
        headers:  new Headers({
            'Content-Type': 'application/json'
        }),
        body: JSON.stringify({
            Message: Message,
            PhotoID: PhotoID,
            AlbumID: AlbumID,
            PubTime: PubTime,
            Offset: 0

        })
    };

    return fetch(domain+"/publishPostScheduled", requestOptions)
        .then(response => response.json())
        .then((d) => {
            setScheduleProgress(count);
            console.log("Scheduled...again maybe lol...check!")
            console.log(d);
        });
}
function getAlbumsFB(){
    fetch(domain+"/getAlbums")
        .then(response => response.json())
        .then((d) => {
            document.getElementById("info-box").innerHTML = "";
            for (let i in d.data){
                document.getElementById("info-box").innerHTML += ""+
                    "<div class='row'>" +
                    "<div class='col-6 col-sm-6 col-md-4 col-lg-3'>" +
                    d.data[i]['name'] +
                    "</div> " +
                    "<div class='col-6 col-sm-6 col-md-4 col-lg-3'>" +
                    d.data[i]['id'] +
                    "</div> " +
                    "</div>";
            }
        });
}

function setCronFB(){
    fetch(domain+"/setCron")
        .then(response => response.json())
        .then((d) => {
            console.log(d);
        });
}
function getCronFB(){
    fetch(domain+"/getCron")
        .then(response => response.json())
        .then((d) => {
            document.getElementById("info-box").innerHTML = "";
            if (d.hasOwnProperty("data")){
                for (let i in d.data){
                    document.getElementById("info-box").innerHTML += ""+
                        "<div class='row'>" +
                        "<div class='col-12'>" +
                        "Group: " + d.data[i]['group'] +
                        "</div> " +
                        "<div class='col-12'>" +
                        "Name: " + d.data[i]['name'] +
                        "</div> " +
                        "<div class='col-12'>" +
                        "Fire time: " + d.data[i]['nextFireTime'] +
                        "</div> " +
                        "</div><hr>";
                }
            } else {
                console.log("ERROR GETTING CRON JOBS")
                console.log(d);
            }

        });
}
function getCommentDetails(CommentID){
    const requestOptions = {
        method: "POST",
        headers:  new Headers({
            'Content-Type': 'application/json'
        }),
        body: JSON.stringify({
            CommentID: CommentID
        })
    };

    return fetch(domain+"/getWonUserIDFromCommentID", requestOptions)
        .then(response => response.json())
        .then((d) => {
            if (!d.hasOwnProperty("error")){
                alert(d.from.name + " | " + d.from.id);
            } else {
                alert("Error, unknown ID or not permitted by the API.")
            }
        });
}

async function getServerStatus() {
    try {
        await getServerStatusFetch();
        document.getElementById("info-box").innerHTML = "Status: UP";
    } catch (e) {
        document.getElementById("info-box").innerHTML = "Status: DOWN";
    }

}
async function getServerStatusFetch() {
    return fetch(domain+"/getServerStatus");
}