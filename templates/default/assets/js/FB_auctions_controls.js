let eventInsertSuccess = document.createEvent('Event');
eventInsertSuccess.initEvent('FB_a_insert_success', true, true);
let eventInsertEmpty = document.createEvent('Event');
eventInsertEmpty.initEvent('FB_a_insert_empty', true, true);
let eventInsertUsed = document.createEvent('Event');
eventInsertUsed.initEvent('FB_a_insert_used', true, true);
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
function insertOutputProduct(){
    let v = document.getElementById("OutputProductInput").value;
    document.getElementById("OutputProductInput").value = "";

    if (v !== ""){
        fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&insert="+v)
            .then(response => response.json())
            .then((d) => {
                if (d.resp === "keyExists"){
                    document.dispatchEvent(eventInsertUsed);
                } else {
                    document.dispatchEvent(eventInsertSuccess);
                }
            })
            .finally(function () {
                getOutputProduct();
            });
    } else {
        document.dispatchEvent(eventInsertEmpty);
    }
}

function deleteOutputProduct(tag){
    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&delete=" + tag)
        .finally(function () {
            getOutputProduct();
        });
}

function getOutputProduct(){
    document.getElementById("OutputProducts").innerHTML = "";

    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&get")
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
                    "<div class='col-2'> " +
                    "<button type='button' class='btn btn-link' onclick='deleteOutputProduct(\""+element.tag+"\")'><i class='fas fa-trash'></i> Delete</button>" +
                    "</div>"
            });
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
function batchPost(){
    let v = document.getElementById("AlbumId").value;
    fetch("/api/FB/outputProducts.php?username=aztrade&password=Zajev123&get")
        .then(response => response.json())
        .then(async (d) => {
            count = 0;
            setPhotoProgress(count);
            setScheduleProgress(count);
            incrementor = 100/d.tags.length;
            for (const element of d.tags){
                count += incrementor;
                await postPhotoToFB(v, element.image, element.tag+" Auction");
            }
        });
}
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
async function postPhotoToFB(AlbumID, PhotoURL, Caption){
    const requestOptions = {
        method: "POST",
        headers:  new Headers({
            'Content-Type': 'application/json'
        }),
        body: JSON.stringify({
            AlbumID: AlbumID,
            ImgUrl: "http://cp.azdev.eu/uploads/images/products/"+PhotoURL,
            Caption: Caption
        })
    };
    return fetch(domain+"/postPhotoToAlbum", requestOptions)
        .then(response => response.json())
        .then(async (d) => {
            setPhotoProgress(count);
            console.log("Posted...maybe...see response");
            await schedulePost(d.id, Caption, AlbumID)
        });
}
async function schedulePost(PhotoID, Message, AlbumID){
    const requestOptions = {
        method: "POST",
        headers:  new Headers({
            'Content-Type': 'application/json'
        }),
        body: JSON.stringify({
            Message: Message,
            PhotoID: PhotoID,
            AlbumID: AlbumID,
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