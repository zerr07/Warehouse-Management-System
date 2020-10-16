{include file='header.tpl'}

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


                                {foreach $reservedList as $item}

                                    <div class="row mt-3 border border-secondary p-1">
                                        <div class="col-2 col-sm-2    m-auto   col-md-2     col-lg-2   col-xl-1">{$item.id}</div>
                                        <div class="col-10 col-sm-10  m-auto   col-md-6     col-lg-6   col-xl-4 text-truncate">{$item.comment}</div>
                                        <div class="col-12 col-sm-12  m-auto   col-md-4     col-lg-4   col-xl-3">{$item.date}</div>
                                        <div class="col-12 col-sm-12  m-auto   col-md-12    col-lg-12  col-xl-4 d-flex justify-content-center">
                                            <a class="btn btn-outline-primary w-100" href="/cp/POS/reserve/index.php?view={$item.id}" >
                                                <i class="fas fa-link"></i>
                                                View
                                            </a>
                                            <a class="btn btn-outline-info w-100 ml-2 mr-2" href="/cp/POS/reserve/loadReservationInCart.php?id={$item.id}" >
                                                <i class="fas fa-link"></i>
                                                Load in POS
                                            </a>
                                            <a class="btn btn-outline-danger w-100" href="/cp/POS/reserve/index.php?cancelFull={$item.id}">
                                                <i class="fas fa-frown"></i>
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                {/foreach}
                        </div>
                    {/if}
                    <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>

{include file='footer.tpl'}