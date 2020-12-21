{include file='header.tpl'}
<div class="row mt-3">
    <div class="col-sm-2 col-md-2 offset-md-2 mt-2">
        <select id="platformSelect" class="form-control" onchange="DrawList();">
            {foreach $platforms as $key => $value}
                <option value="{$value.id}">{$value.name}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-sm-8 col-md-4 mt-2">
        <input type="text" class="form-control w-100" name="searchName" id="searchName" placeholder="Search by name" list="productDataList"><div class='' id='searchNameFeedback'></div>
        <template id="productDataListTemplate"></template>
        <datalist id="productDataList"></datalist>
    </div>
    <div class="col-sm-2 col-md-2 mt-2">
        <button type="button" name="search" class="btn btn-info inline-items w-100" value="Search" id="SearchBtn">Add</button>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12" id="dataBox">

    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-3 col-md-3 offset-md-2 mt-3">
        <button type="button" id="getXML" class="btn btn-success w-100">Get XML</button>
    </div>
    <div class="col-sm-9 col-md-5 mt-3">
        <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/WMS"><i class="fas fa-undo-alt"></i> Back</a>
    </div>
</div>
<script>
    document.getElementById("getXML").addEventListener("click", function (){
        let platform = document.querySelector("select[id='platformSelect']").value;
        let win = window.open("/cp/WMS/XML/generateXML.php?id="+platform, '_blank');
        win.focus();
    });
    async function DrawList(){
        document.querySelector("[id='dataBox']").innerHTML = "";

        let platform = document.querySelector("select[id='platformSelect']").value;
        let box = document.getElementById("dataBox");
        let count = 0;
        turnOnPreloader();

        await fetch("/cp/WMS/XML/ListController.php?get&id_platform="+platform)
        .then(response => response.json())
        .then((d) => {
            count = Object.keys(d).length;
            Object.keys(d).forEach(el => {
                turnOnPreloader();
                 fetch("/controllers/products/get_products.php?getSingleProduct="+d[el]['id_product'])
                    .then(response => response.json())
                    .then((prod_d) => {

                        let row = document.createElement("div");
                        row.setAttribute("class", "row mt-2 border border-secondary p-2");
                        row.setAttribute("id", d[el]['id_product']);

                        let productBox = document.createElement("div");
                        productBox.setAttribute("class", "col-sm-8 col-md-6 offset-md-2 text-truncate my-auto");
                        productBox.innerText = prod_d.tag + " | " + prod_d.name.et;

                        let deleteBox = document.createElement("div");
                        deleteBox.setAttribute("class", "col-sm-2 text-truncate");
                        deleteBox.innerHTML = "<button type='button' class='btn btn-outline-danger' onclick='DeleteRow(this);'><i class='fas fa-trash'></i></button>";

                        row.appendChild(productBox);
                        row.appendChild(deleteBox);
                        box.appendChild(row);

                    });

            });
        });
        let timer = setInterval(function(){
            if(box.childElementCount === count){
                turnOffPreloader();
                clearInterval(timer);
            }
            }, 500);
    }

    function DeleteRow(el){
        let element = el.parentNode.parentNode;
        let id = element.id;
        let platform = document.querySelector("select[id='platformSelect']").value;
        fetch("/cp/WMS/XML/ListController.php?remove&id="+id+"&id_platform="+platform)
        .finally(function () {
            if (element && element.parentNode){
                element.parentNode.removeChild(element);
            }
            DrawList();
        });

    }

    window.addEventListener("load", function (){
        setPageTitle("XML Generator");
        fetch("/controllers/products/get_products.php?getDataList=true")
            .then(response => response.json())
            .then((d) => {
                let datalist = document.getElementById("productDataListTemplate");
                Object.keys(d).forEach(k => {
                    let el = document.createElement("option");
                    el.setAttribute("value", d[k]);
                    el.setAttribute("data-id", k);
                    el.innerText = d[k];
                    datalist.appendChild(el);
                })
            }).finally(function () {
            LimitDataList(document.getElementById("searchName"),
                document.getElementById("productDataList"),
                document.getElementById("productDataListTemplate"), 5);
        });
        DrawList();
    });
    document.getElementById("SearchBtn").addEventListener("click", function () {
        let nameFeedback = document.getElementById("searchNameFeedback");
        let nameSearch = document.getElementById("searchName");
        nameSearch.setAttribute("class", "form-control w-100");
        nameFeedback.setAttribute("class", "");
        nameFeedback.innerText = "";
        let nameID = document.querySelector("datalist[id='productDataList'] > option[value='"+nameSearch.value+"']");
        let platform = document.querySelector("select[id='platformSelect']").value;
        if (nameID){
            if (document.getElementById(nameID.getAttribute("data-id"))){
                nameSearch.setAttribute("class", "form-control w-100 is-invalid");
                nameFeedback.setAttribute("class", "invalid-feedback");
                nameFeedback.innerText = "Product already exists in this list!";
                return false;
            } else {
                fetch("/cp/WMS/XML/ListController.php?post&id="+nameID.getAttribute("data-id")+"&id_platform="+platform)
                    .finally(function () {
                        DrawList();
                        nameSearch.value = "";
                    });
            }
        }
    });
</script>
{include file='footer.tpl'}