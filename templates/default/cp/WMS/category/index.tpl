{include file='header.tpl'}

<div class="row">
    <div class="col-12 mt-3">
        <a class="btn btn-primary w-100" href="/cp/WMS/category/add"><i class="fas fa-plus"></i>&nbsp;Add category</a>
    </div>
    <div class="col-12 mt-3">
        <a class="btn btn-primary w-100" href="#" data-toggle="collapse" data-target="#moresettings" aria-expanded="false" aria-controls="multiCollapseExample2">
            <i class="fas fa-filter"></i>&nbsp;Empty categories
        </a>
        <div class="collapse multi-collapse mt-3" id="moresettings">
            <div class="card card-body" >
                <div class="ml-3">
                    {foreach $emptyCategoies|array_filter as $key => $value}
                        <label class='form-check-label label-cat'>{$value.id}</label>
                        <label class='form-check-label text-white label-cat'>{$value.name}</label>
                        {if $value.enabled == "0"}
                            <a class="btn-cat label-cat" style="width: 44.2px;color: red"><i class="fas fa-times"></i></a>
                        {else}
                            <a class="btn-cat label-cat" style=";color: lawngreen"><i class="fas fa-check"></i></a>
                        {/if}
                        <br>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">



        <div>
            {include file='cp/WMS/category/linkModal.tpl'}
            {function name=cat_tree margin=1}
                {foreach $data as $key => $value}
                    {if is_null($value.child)}


                        <div class="row mt-3 border border-secondary p-1">
                            <div class="col-2">
                                <label class='form-check-label'>{$value.id}</label>
                            </div>
                            <div class="col-7 col-sm-7 col-md-8">
                                <label class='form-check-label' id="catName{$key}">{$value.name}</label>

                            </div>
                            <div class="col-1">
                                {if $value.enabled == "0"}
                                    <span style="color: red"><i class="svg_cat fas fa-times"></i></span>
                                {else}
                                    <span style="color: lawngreen"><i class="svg_cat fas fa-check"></i></span>
                                {/if}
                            </div>
                            <div class="col-1 d-flex justify-content-end">
                                <a href="javascript:void(0);" data-toggle="popover" class="catInfo" id="catInfo{$value.id}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                            </div>
                        </div>

                    {else}

                        <div class="row mt-3 border border-secondary p-1">
                            <div class="col-2">
                                <label class='form-check-label'>{$value.id}</label>
                            </div>
                            <div class="col-7 col-sm-7 col-md-8">
                                <label class='form-check-label' id="catName{$key}" data-toggle='collapse' data-target="#catlistk{$key}"
                                       aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                                    {$value.name}+</label>
                            </div>
                            <div class="col-1">
                                {if $value.enabled == "0"}
                                    <span style="color: red"><i class="svg_cat fas fa-times"></i></span>
                                {else}
                                    <span style="color: lawngreen"><i class="svg_cat fas fa-check"></i></span>
                                {/if}
                            </div>
                            <div class="col-1 d-flex justify-content-end">
                                <a href="javascript:void(0);" data-toggle="popover" class="catInfo" id="catInfo{$value.id}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                            </div>
                        </div>
                        <div  class='collapse collapseDiv' id='catlistk{$key}' style="margin: 0 0 0 {$margin*10}px">
                            {if !is_null($value.child)}
                                {cat_tree data=$value.child margin=$margin+1}
                            {/if}
                        </div>
                    {/if}
                {/foreach}
            {/function}
            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
                    data-target=".collapseDiv" aria-expanded="false" style="margin-top: 1rem"
                    aria-controls='collapseExample'>Expand all</button>
            <br>
            {cat_tree data=$cat_tree}
        </div>
        <div class="row mt-3">

            <div class="col-12 d-flex justify-content-end">
                <a class="btn btn-primary d-inline-flex ml-2" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>
<script>

    $(window).on("load", function (){
        Array.prototype.forEach.call($(".catInfo"), function(el) {
            let div;
            let id = el.id.replace("catInfo", "");
            let nameId = $("#catName"+id);
            console.log(nameId)
            if(nameId.length && nameId[0].innerHTML !== undefined){
                let name = nameId[0].innerHTML
                let btn1 = document.createElement("button");
                btn1.setAttribute('onclick', "deleteSMTcategory("+id+")");
                btn1.setAttribute('type', "button");
                btn1.setAttribute('style', "color:#e54747;")
                btn1.setAttribute('class', "btn btn-link");
                btn1.innerHTML = "<i class=\"fas fa-trash\"></i> Delete";
                let btn2 = document.createElement("a");
                btn2.setAttribute('href', "/cp/WMS/category/edit/?edit="+id);
                btn2.setAttribute('class', "btn btn-link");
                btn2.innerHTML = "<i class=\"fas fa-edit\"></i> Edit";
                let btn3 = document.createElement("button");
                btn3.setAttribute('onclick', "getLinks('"+id+"', '"+name.trim()+"')");
                btn3.setAttribute('data-toggle', "modal");
                btn3.setAttribute('data-target', "#linkExportModalBody")
                btn3.setAttribute('class', "btn btn-link");
                btn3.innerHTML = "<i class=\"fas fa-edit\"></i> Link</button>";
                div = document.createElement("div");
                div.appendChild(btn2);
                div.appendChild(btn3);
                div.appendChild(btn1);
            } else {
                div = document.createElement("div");
                div.innerText = "Load error"
            }

            $("#"+el.id).popover({
                html : true,
                title: 'Controls',
                content: div,
                placement: 'left'
            })
        });
    });

    function deleteSMTcategory(id){
        var r = confirm("Do you really want to delete item?");
        if (r === true) {
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/products/delete.php?deleteSMTcategory="+id
            });
            location.reload();
        } else {
            return;
        }

    }
</script>
<script src="/cp/WMS/category/editLink.js?t=16102020T165517"></script>

{include file='footer.tpl'}