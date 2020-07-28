{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="white-space: nowrap;">
                    {if $reservedList|@count == 0}
                        <div class="row">
                            <div class="col-md-12" style="margin-top: 50px;">
                                <p>Nothing Found</p>
                                <img class="img-fluid d-block align-items-center d-inline-flex notFound" src="/templates/default/assets/7e2e8b70e25555eeafe05f2a17c8d81f.png">
                            </div>
                        </div>
                    {else}


                        <div class="table-responsive" >
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Note</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {foreach $reservedList as $item}
                                    <tr>
                                        <td>{$item.id}</td>
                                        <td>{$item.comment}</td>
                                        <td>{$item.date}</td>
                                        <td>
                                            <a class="btn btn-outline-primary" href="/cp/POS/reserve/index.php?view={$item.id}" >
                                                <i class="fas fa-link"></i>
                                                View
                                            </a>
                                            <a class="btn btn-outline-info" href="/cp/POS/reserve/loadReservationInCart.php?id={$item.id}" >
                                                <i class="fas fa-link"></i>
                                                Load in POS
                                            </a>
                                            <a class="btn btn-outline-danger" href="/cp/POS/reserve/index.php?cancelFull={$item.id}">
                                                <i class="fas fa-frown"></i>
                                                Cancel
                                            </a>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</main>
{include file='footer.tpl'}