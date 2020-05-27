{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-primary" style="width: 100%;" href="/cp/WMS/category/add"><i class="fas fa-plus"></i>&nbsp;Add category</a>
                    <a class="btn btn-primary" style="width: 100%;margin-top: 4px;" href="#" data-toggle="collapse" data-target="#moresettings" aria-expanded="false" aria-controls="multiCollapseExample2">
                        <i class="fas fa-filter"></i>&nbsp;Empty categories
                    </a>
                    <div class="collapse multi-collapse" id="moresettings" style="margin-top: 4px;">
                        <div class="card card-body" >
                            <div class="text-left" style="margin-left: 10px;">
                                {foreach $emptyCategoies as $key => $value}
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
                    <div class="text-left" style="margin-left: 10px;">
                        {include file='cp/WMS/category/linkModal.tpl'}
                        {function name=cat_tree margin=1}
                            {foreach $data as $key => $value}
                                {if is_null($value.child)}
                                    <label class='form-check-label label-cat'>{$value.id}</label>
                                    <label class='form-check-label text-white label-cat'>{$value.name}</label>
                                    <form method="POST" action="/controllers/products/delete.php" class="btn-cat"
                                          onsubmit="return confirm('Do you really want to delete category? This will not remove sub categories.');">
                                        <button type="submit" class="btn btn-link" name="deleteSMTcategory" value="{$value.id}"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                    <a class="btn btn-link btn-cat" href="/cp/WMS/category/edit/?edit={$value.id}" >
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-link btn-cat" onclick="getLinks({$value.id}, '{$value.name}')"
                                            data-toggle="modal" data-target="#linkExportModalBody"><i class="fas fa-edit"></i> Link</button>
                                    {if $value.enabled == "0"}
                                        <a class="btn-cat label-cat" style="width: 44.2px;color: red"><i class="fas fa-times"></i></a>
                                    {else}
                                        <a class="btn-cat label-cat" style=";color: lawngreen"><i class="fas fa-check"></i></a>
                                    {/if}

                                    <hr class="hr-cat">
                                {else}
                                    <label class='form-check-label label-cat'>{$value.id}</label>
                                    <label class='form-check-label text-white label-cat' data-toggle='collapse' data-target="#catlistk{$key}"
                                           aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                                        {$value.name}+</label>

                                    <form method="POST" action="/controllers/products/delete.php" class="btn-cat"
                                          onsubmit="return confirm('Do you really want to delete category? This will not remove sub categories.');">
                                        <button type="submit" class="btn btn-link" name="deleteSMTcategory" value="{$value.id}"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                    <a class="btn btn-link btn-cat" href="/cp/WMS/category/edit/?edit={$value.id}" >
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-link btn-cat" onclick="getLinks({$value.id}, '{$value.name}')"
                                            data-toggle="modal" data-target="#linkExportModalBody"><i class="fas fa-edit"></i> Link</button>
                                    {if $value.enabled == "0"}
                                        <a class="btn-cat label-cat" style="width: 44.2px;color: red"><i class="fas fa-times"></i></a>
                                    {else}
                                        <a class="btn-cat label-cat" style=";color: lawngreen"><i class="fas fa-check"></i></a>
                                    {/if}
                                    <hr class="hr-cat">
                                    <div  class='collapse collapseDiv' id='catlistk{$key}' style="margin: 0 0 0 {$margin*20}px">
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
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>

{include file='footer.tpl'}