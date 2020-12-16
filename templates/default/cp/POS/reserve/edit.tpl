{include file='header.tpl'}
<script src="/templates/default/assets/js/moment.js"></script>
<form action="/cp/POS/reserve/update.php" method="POST">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-9 col-lg-10">
                    <div class="row">
                        <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">ID: </div>
                        <div class="col-8 col-sm-9 col-md-9 col-lg-10 col-xl-5">{$reservation.id}</div>
                        <input type="text" name="id" value="{$reservation.id}" hidden>
                    </div>
                    <div class="row">
                        <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">Date: </div>
                        <div class="col-8 col-sm-9 col-md-9 col-lg-10 col-xl-5">{$reservation.date}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">Type: </div>
                        <div class="col-8 col-sm-9 col-md-9 col-lg-10 col-xl-5">{$reservation.type_name}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1">Comment: </div>
                        <div class="col-8 col-sm-9 col-md-9 col-lg-10 col-xl-5">
                            <input type="text" maxlength="240" name="comment" value="{$reservation.comment}" class="form-control">
                            <small>Max length: 240 characters</small>
                        </div>
                    </div>
                </div>
            </div>

            {foreach $reservation.products as $prod}
                <div class="row mt-3 border border-secondary p-1" id="row{$prod.id}">
                    <input type="text" name="id" value="{$reservation.id}" hidden>

                    {if $prod.tag == "Buffertoode"}
                        <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; ">{$prod.tag}</a>    </div>
                        <div class="col-8 col-sm-8 col-lg-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; ">{$prod.name}</a>   </div>
                    {else}
                        <div class="col-2 col-sm-2 col-lg-1 m-auto"><a style="color: white;text-overflow: ellipsis; ">{$prod.tag}</a>       </div>
                        <div class="col-8 col-sm-8 col-lg-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis; ">{$prod.name.et}</a>   </div>
                    {/if}
                    <div class="col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center">
                        <input type="number" step="1" class="form-control" onchange="UpdatePrice(this, '{$prod.quantity}' , 'exist')" name="quantity[{$prod.id}]" value="{$prod.quantity}">
                    </div>
                    <div class="col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center text-truncate">
                        <input type="number" step="0.01" class="form-control" name="price[{$prod.id}]" value="{$prod.price}">
                    </div>
                    <div class="col-4 col-sm-4 col-lg-3 m-auto d-flex justify-content-center text-truncate" title="{$prod.location}">Loc: {$prod.location}</div>
                    <div class="col-1 m-auto d-flex justify-content-center">
                        <button type="button" class="btn btn-link" style="color: #cd6464" onclick="deleteRow(this)"><i class='fas fa-trash'></i></button>
                    </div>

                </div>
            {/foreach}{debug}
            <div class="row mt-5">
                <div class="col-12" id="extra">

                </div>
            </div>
            <div class="row mt-3">

                <div class="col-2 d-flex justify-content-start mt-3">
                    <button type="submit" class="btn btn-secondary">
                        <i class="far fa-save"></i> Save
                    </button>
                </div>
                <div class="col-8 mt-3">
                    <div class="row">
                        <div class="col-4">
                            <input type="text" class="form-control" id="searchName" placeholder="Search by name" list="productDataList">
                            <div class="" id="searchNameFeedback"></div>
                            <datalist id="productDataList"></datalist>
                        </div>
                        <div class="col-4">
                            <button type="button" id="addExtraItem" class="btn btn-info w-100">Add extra</button>
                        </div>
                        <div class="col-4">
                            <button type="button" onclick="addExtraBuffer()" class="btn btn-info w-100">Add buffer</button>
                        </div>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-end mt-3">

                    <a class="btn btn-primary" href="/cp/POS/reserve/index.php?view={$reservation.id}">
                        <i class="fas fa-undo-alt"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    document.getElementById("addExtraItem").addEventListener("click", function () {
        document.getElementById("searchName").setAttribute("class", "form-control");
        document.getElementById("searchNameFeedback").setAttribute("class", "");
        document.getElementById("searchNameFeedback").innerText = "";
        let nameSearch = document.getElementById("searchName");
        let nameID = document.querySelector("datalist[id='productDataList'] > option[value='"+nameSearch.value+"']");
        if (nameID){
            fetch("/controllers/products/get_products.php?getSingleProduct="+nameID.getAttribute("data-id"))
            .then(response => response.json())
            .then((d)=>{
                addExtraItem(d);
                nameSearch.value = "";
            });
        } else {
            document.getElementById("searchName").setAttribute("class", "form-control is-invalid");
            document.getElementById("searchNameFeedback").setAttribute("class", "invalid-feedback");
            document.getElementById("searchNameFeedback").innerText = "Please specify output product!";
        }
    });
    window.addEventListener("load", function () {
        setPageTitle("Edit reservation {$reservation.id}");
        fetch("/controllers/products/get_products.php?getDataList=true")
            .then(response => response.json())
            .then((d) => {
                let datalist = document.getElementById("productDataList");
                Object.keys(d).forEach(k => {
                    let el = document.createElement("option");
                    el.setAttribute("value", d[k]);
                    el.setAttribute("data-id", k);
                    el.innerText = d[k];
                    datalist.appendChild(el);
                })
            })
    });

    function addExtraBuffer(){
        let itemBox = document.getElementById("extra");

        let itemRow = createProductRow(moment().unix());

        let quantityBox = document.createElement("div");
        quantityBox.setAttribute("class", "col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center");
        quantityBox.appendChild(createQuantityInput("1", "quantityNewBuffer", "buffer", ""));

        let priceBox = document.createElement("div");
        priceBox.setAttribute("class", "col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center text-truncate");
        priceBox.appendChild(createPriceInput("0.00", "priceNewBuffer", ""));

        let locationBox = document.createElement("div");
        locationBox.setAttribute("class", "col-4 col-sm-4 col-lg-3 m-auto d-flex justify-content-center text-truncate");

        let deleteBox = document.createElement("div");
        deleteBox.setAttribute("class", "col-1 m-auto d-flex justify-content-center");
        deleteBox.appendChild(createDeleteBtn());

        let nameBoxInput = document.createElement("input");
        nameBoxInput.setAttribute("type", "text");
        nameBoxInput.setAttribute("class", "form-control");
        nameBoxInput.setAttribute("name", "nameNewBuffer[]");
        nameBoxInput.setAttribute("placeholder", "Buffertoode name");
        nameBoxInput.setAttribute("value", "Buffertoode");

        let nameBox = document.createElement("div");
        nameBox.setAttribute("class", "col-8 col-sm-8 col-lg-3 m-auto text-truncate");
        nameBox.appendChild(nameBoxInput);

        itemRow.appendChild(createProductTag("Buffertoode"));
        itemRow.appendChild(nameBox);
        itemRow.appendChild(quantityBox);
        itemRow.appendChild(priceBox);
        itemRow.appendChild(locationBox);
        itemRow.appendChild(deleteBox);

        itemBox.appendChild(itemRow)
    }

    function addExtraItem(resp){
        if (document.getElementById("prTag"+resp.tag)) {
            if (resp.tag === (document.getElementById("prTag" + resp.tag).id).replace("prTag", "")) {
                let prodRowID = document.getElementById("prTag"+resp.tag).parentNode.parentNode.id
                let selectorQ = document.querySelector("div[id='"+prodRowID+"'] > div > input[name='quantityNew[]']");
                let selectorP = document.querySelector("div[id='"+prodRowID+"'] > div > input[name='priceNew[]']");
                selectorP.value = parseFloat(selectorP.value)+parseFloat(resp.platforms[1].price);
                selectorQ.value = parseInt(selectorQ.value)+1;
                return;
            }
        }
        let itemBox = document.getElementById("extra");

        let itemRow = createProductRow(resp.id);

        let quantityBox = document.createElement("div");
        quantityBox.setAttribute("class", "col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center");
        quantityBox.appendChild(createQuantityInput("1", "quantityNew", "default", resp.id));

        let priceBox = document.createElement("div");
        priceBox.setAttribute("class", "col-4 col-sm-4 col-lg-2 m-auto d-flex justify-content-center text-truncate");
        priceBox.appendChild(createPriceInput(resp.platforms[1].price, "priceNew", resp.id));

        let locationBox = document.createElement("div");
        locationBox.setAttribute("class", "col-4 col-sm-4 col-lg-3 m-auto d-flex justify-content-center text-truncate");

        let deleteBox = document.createElement("div");
        deleteBox.setAttribute("class", "col-1 m-auto d-flex justify-content-center");
        deleteBox.appendChild(createDeleteBtn());

        let loc = "";
        if (resp.locationList !== null || resp.locations !== "") {
            loc += "<select class='custom-select' name='loc_select["+resp.id+"]'>";
            for (let place in resp.locationList) {
                loc += "<option value='" + place + "'>";
                loc += resp.locationList[place]['type_name'].toString() + " : "
                    + resp.locationList[place]['location'].toString() + " : "
                    + resp.locationList[place]['quantity'].toString()
                    + "</option>"
            }
            loc += "</select>";
        }
        locationBox.innerHTML = loc;

        itemRow.appendChild(createProductTag(resp.tag));
        itemRow.appendChild(createNameBox(resp.name.et));
        itemRow.appendChild(quantityBox);
        itemRow.appendChild(priceBox);
        itemRow.appendChild(locationBox);
        itemRow.appendChild(deleteBox);

        itemBox.appendChild(itemRow)

    }

    function createQuantityInput(value, name , type, id){
        let quantityBoxInput = document.createElement("input");
        quantityBoxInput.setAttribute("type", "number");
        quantityBoxInput.setAttribute("step", "1");
        quantityBoxInput.setAttribute("class", "form-control");
        quantityBoxInput.setAttribute("name", name+"["+id+"]");
        quantityBoxInput.setAttribute("onchange", "UpdatePrice(this, '"+value+"', '"+type+"');")
        quantityBoxInput.setAttribute("value", value);
        return quantityBoxInput;
    }

    function UpdatePrice(el, val, type){
        let rowID = el.parentElement.parentElement.id;
        let selectorQ;
        let selectorP;
        if (type === "buffer"){
            selectorQ = document.querySelector("div[id='"+rowID+"'] > div > input[name='quantityNewBuffer[]']");
            selectorP = document.querySelector("div[id='"+rowID+"'] > div > input[name='priceNewBuffer[]']");
        } else if(type === "exist"){
            selectorQ = document.querySelector("div[id='"+rowID+"'] > div > input[name^='quantity']");
            selectorP = document.querySelector("div[id='"+rowID+"'] > div > input[name^='price']");
            selectorQ.setAttribute("onchange", "UpdatePrice(this, "+el.value+" , 'exist')");
        } else {
            selectorQ = document.querySelector("div[id='"+rowID+"'] > div > input[name^='quantityNew']");
            selectorP = document.querySelector("div[id='"+rowID+"'] > div > input[name^='priceNew']");
            selectorQ.setAttribute("onchange", "UpdatePrice(this, "+el.value+" , 'default')");
        }
        console.log("div[id='"+rowID+"'] > div > input[name='priceNew[]");
        let ppp = parseFloat(selectorP.value)/parseFloat(val);
        selectorP.value = ppp*parseFloat(selectorQ.value);
    }

    function createPriceInput(value, name, id){
        let priceBoxInput = document.createElement("input");
        priceBoxInput.setAttribute("type", "number");
        priceBoxInput.setAttribute("step", "0.01");
        priceBoxInput.setAttribute("class", "form-control");
        priceBoxInput.setAttribute("name", name+"["+id+"]");
        priceBoxInput.setAttribute("value", value);
        return priceBoxInput;
    }

    function createDeleteBtn(){
        let btn = document.createElement("button");
        btn.setAttribute("type", "button");
        btn.setAttribute("class", "btn btn-link");
        btn.setAttribute("style", "color: #cd6464");
        btn.setAttribute("onclick", "deleteRow(this)");
        btn.innerHTML = "<i class='fas fa-trash'></i>";
        return btn;
    }

    function deleteRow(el){
        let node = el.parentNode.parentNode;
        node.parentNode.removeChild(node);
    }

    function createProductRow(id){
        let itemRow = document.createElement("div");
        itemRow.setAttribute("class", "row p-1");
        itemRow.setAttribute("id", "prodRow"+id);
        return itemRow;
    }
    function createProductTag(tag){
        let tagBox = document.createElement("div");
        tagBox.setAttribute("class", "col-2 col-sm-2 col-lg-1 m-auto");
        let tagBoxSpan = document.createElement("span");
        tagBoxSpan.setAttribute("style", "color: white;text-overflow: ellipsis;");
        tagBoxSpan.setAttribute("id", "prTag"+tag);
        tagBoxSpan.innerText = tag;
        tagBox.appendChild(tagBoxSpan);
        return tagBox;
    }
    function createNameBox(name){
        let nameBox = document.createElement("div");
        nameBox.setAttribute("class", "col-8 col-sm-8 col-lg-3 m-auto text-truncate");
        let nameBoxSpan = document.createElement("span");
        nameBoxSpan.setAttribute("style", "color: white;text-overflow: ellipsis;");
        nameBox.appendChild(nameBoxSpan);
        nameBoxSpan.innerText = name;
        return nameBox;
    }
</script>
{include file='footer.tpl'}