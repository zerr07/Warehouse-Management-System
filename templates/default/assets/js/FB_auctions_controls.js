let eventInsertSuccess = document.createEvent('Event');
eventInsertSuccess.initEvent('FB_a_insert_success', true, true);
let eventInsertEmpty = document.createEvent('Event');
eventInsertEmpty.initEvent('FB_a_insert_empty', true, true);
let eventInsertUsed = document.createEvent('Event');
eventInsertUsed.initEvent('FB_a_insert_used', true, true);
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
                let e = document.createElement("div");
                e.setAttribute("class", "col-2 mt-auto mb-auto");
                let e1 = document.createElement("div");
                e1.setAttribute("class", "col-2 mt-auto mb-auto");
                e.innerText = element.tag;
                e1.innerText = element.quantity;

                div.appendChild(e);
                div.appendChild(e1);
                document.getElementById("OutputProducts").appendChild(div);
                div.innerHTML +=
                    "<div class='col-4'> " +
                    "<button type='button' class='btn btn-link' onclick='loadAuctionCharts(\""+element.tag+"\")'><i class='fas fa-ad'></i> View auction charts</button>" +
                    "<div id='auction_charts'></div>" +
                    "</div>"+
                    "<div class='col-4'> " +
                    "<button type='button' class='btn btn-link' onclick='deleteOutputProduct(\""+element.tag+"\")'><i class='fas fa-trash'></i> Delete</button>" +
                    "</div>"
            });
        });
}