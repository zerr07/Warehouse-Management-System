{include file='header.tpl'}

            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    <form action="/cp/POS/search.php" class="text-left" style="padding-top: 10px;" method="POST">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 mt-2">
                                <input type="text" class="form-control" name="searchTagID" id="searchtagid" placeholder="Search by ID" autofocus>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <input type="text" class="form-control" name="searchName" id="searchname" placeholder="Search by name">
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
{include file='footer.tpl'}