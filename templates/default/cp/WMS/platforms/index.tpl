{include file='header.tpl'}
{assign var="SHOP" value=1}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">

                    {if $platforms|@count == 0}
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 50px;">
                                <p>Nothing Found</p>
                                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                            </div>
                        </div>
                    {else}

                        <div class="table-responsive" >
                            <table class="table table-sm table-borderless">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Margin</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $platforms as $item}
                                    <tr>
                                        <td><a href="/cp/WMS/platforms/desc/?edit={$item.id}">{$item.id}</a></td>
                                        <td><a style="color: white;text-overflow: ellipsis; "
                                               href="/cp/WMS/platforms/desc/?edit={$item.id}">{$item.name}</a></td>
                                        <td>{$item.margin}</td>
                                        <td>

                                            <a class="btn btn-sm btn-outline-primary" href="/cp/WMS/platforms/desc/?edit={$item.id}" >
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>
{include file='footer.tpl'}