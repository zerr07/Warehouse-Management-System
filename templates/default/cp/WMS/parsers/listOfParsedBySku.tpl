{include file='header.tpl'}

<div class="row m-3">
    <div class="col-12" id="sku_page_loader">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="col-12" id="sku_list">

    </div>
</div>
<script>
    const platform = "{$platform_sku}";
    let c = 0;
    $(window).on('load', async function() {
        setPageTitle("Parser list by images");
        loadPage('0');
    })
    function loadPage(start){
        fetch("/controllers/parser/bySKU/"+platform+".php?start="+start)
            .then(response => response.json())
            .then(d => {
                if (d.hasOwnProperty("success")){
                    load_Prod(d.success['id']);
                    console.log(d)
                    if (c <= 24){
                        c += 1;
                        loadPage(parseInt(d.success['start'])+1)
                    } else {
                        document.getElementById("sku_page_loader").innerHTML = "";
                        displayAlert("Finished", 15000, "success")
                    }

                }
                if (d.hasOwnProperty("fail"))
                    displayAlert(d.error, 2000, "error")
            })
    }
    function load_Prod(id){
        fetch("/controllers/products/get_products.php?getSingleProductNotFull="+id)
        .then(response => response.json())
        .then(d => {

            let row = document.createElement("div");
            row.setAttribute("class", "row mt-2");

            let col_img = document.createElement("div");
            col_img.setAttribute("class", "col-sm-3 col-md-2 my-auto");

            let col_name = document.createElement("div");
            col_name.setAttribute("class", "col-sm-9 col-md-3 my-auto");

            let col_edit = document.createElement("div");
            col_edit.setAttribute("class", "col-sm-4 col-md-2 my-auto");

            let col_goto = document.createElement("div");
            col_goto.setAttribute("class", "col-sm-4 col-md-2 my-auto");

            let col_exclude = document.createElement("div");
            col_exclude.setAttribute("class", "col-sm-4 col-md-2 my-auto");

            let img = document.createElement("img");
            img.setAttribute("class", "thumbnail-img");
            img.setAttribute("src", "/uploads/images/products/"+d.mainImage)

            let name = document.createElement("span");
            name.setAttribute("class", "d-block");
            name.innerHTML = "Title: "+ d.name['en'];

            let editBtn = document.createElement("a");
            editBtn.setAttribute("class", "btn btn-primary float-right w-100");
            editBtn.setAttribute("href", "/cp/WMS/item/edit/?edit="+id)
            editBtn.innerHTML = "Edit";

            let gotoBtn = document.createElement("a");
            gotoBtn.setAttribute("class", "btn btn-info float-right w-100");
            gotoBtn.setAttribute("href", "/cp/WMS/view/?view="+id)
            gotoBtn.innerHTML = "Go to";

            let exclBtn = document.createElement("button");
            exclBtn.setAttribute("class", "btn btn-warning float-right w-100")
            exclBtn.setAttribute("type", "button");
            exclBtn.setAttribute("onclick", "setFlag('"+id+"', 'Parser_SKU_Exclude')")
            exclBtn.innerHTML = "Exclude";

            col_img.appendChild(img);
            col_name.appendChild(name);
            col_edit.appendChild(editBtn);
            col_goto.appendChild(gotoBtn);
            col_exclude.appendChild(exclBtn);

            row.appendChild(col_img)
            row.appendChild(col_name)
            row.appendChild(col_edit)
            row.appendChild(col_goto)
            row.appendChild(col_exclude)

            document.getElementById("sku_list").appendChild(row);
        })
    }

</script>

{include file='footer.tpl'}