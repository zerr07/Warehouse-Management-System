{include file='header.tpl'}
<script src="/cp/POS/updateCart.js?t=20102020T145725"></script>

            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    <form action="/cp/POS/search.php" class="text-left" style="padding-top: 10px;" method="POST" id="POScartForm">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 mt-2">
                                <input type="text" class="form-control" name="searchTagIDPOS" id="searchtagid" placeholder="Search by ID" autofocus>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <input type="text" class="form-control" name="searchNamePOS" id="searchname" placeholder="Search by name" list="productDataList">
                                <datalist id="productDataList"></datalist>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <input type="submit" formaction="/cp/POS/search.php" id='search' name="search" class="btn btn-outline-secondary w-100" value="Search">
                            </div>
                        </div>
                    </form>
                    {foreach $items as $item}
                        <div class="row mt-3 border border-secondary p-1">
                            <div class="col-4 m-auto">
                                {if $item.mainImage!=null}
                                    <img class="img-fluid" src="/uploads/images/products/{$item.mainImage}" width="70px" >
                                {else}
                                    <img class="img-fluid itemSMimg" src="https://static.pingendo.com/img-placeholder-1.svg" width="70px" >
                                {/if}
                            </div>
                            <div class="col-5 m-auto">{$item.name}</div>
                            <div class="col-3 m-auto"><a href="/cp/POS/search.php?addID={$item.id}" class="btn btn-info w-100">Add to cart</a></div>
                        </div>
                    {/foreach}
                </div>
            </div>
<script>
    $(window).on("load", function () {
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
    document.getElementById("search").addEventListener('click', function () {
        let nameSearch   = document.getElementById("searchname");
        let nameID = document.querySelector("datalist[id='productDataList'] > option[value='"+nameSearch.value+"']");
        if (nameID){
            updateByID(nameID.getAttribute("data-id"));
            window.location.href = "/cp/POS/";
        } else {
            let form = document.getElementById("POScartForm");
            form.action = "/cp/POS/search.php";
            form.submit();
            searchByName(nameSearch.value);
        }
    });
</script>
{include file='footer.tpl'}