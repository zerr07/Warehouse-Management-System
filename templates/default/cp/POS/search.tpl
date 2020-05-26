{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    <form action="/cp/POS/search.php" class="text-left" style="padding-top: 10px;" method="POST">
                        <input type="text" class="form-control inline-items" style="width: 20%; height: 42px;" name="searchTagID" id="form17" placeholder="Search by ID">
                        <input type="text" class="form-control inline-items" style="width: 59.8%; height: 42px;" name="searchName" id="form17" placeholder="Search by name">
                        <input type="submit" name="search" class="btn btn-outline-secondary inline-items" style="width: 20%; height: 42px;" value="Search">
                    </form>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $items as $item}
                            <tr>
                                <td>
                                    {if $item.mainImage!=null}
                                        <img class="img-fluid" src="/uploads/images/products/{$item.mainImage}" width="70px" >
                                    {else}
                                        <img class="img-fluid itemSMimg" src="https://static.pingendo.com/img-placeholder-1.svg" width="70px" >
                                    {/if}
                                </td>
                                <td>{$item.name}</td>
                                <td><a href="/cp/POS/search.php?addID={$item.id}" class="btn btn-info w-100">Add to cart</a> </td>
                            </tr>
                        {/foreach}


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
{include file='footer.tpl'}