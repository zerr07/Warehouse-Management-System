{include file='header.tpl'}

<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">
                    {foreach $sales as $item}
                    <div class="col-12" style="display: inline-flex;">
                        <p style="margin-right: auto; margin-left: auto">
                            Arve Nr: {$item.arveNr}<br>
                            Date: {$item.date}<br>
                            {if $item.mode == "Bigshop"}
                                <span style="margin-right: 5px; width: 100px; background-color:#009ac0;" class="badge badge-warning">{$item.mode}</span><br>
                                Ostja: {$item.ostja}
                            {/if}
                            {if $item.mode == "Minuvalik"}
                                <span style="margin-right: 5px; width: 100px; background-color:greenyellow;" class="badge badge-warning">{$item.mode}</span>
                            {/if}
                            {if $item.mode == "Osta"}
                                <span style="margin-right: 5px; width: 100px; background-color:orange;" class="badge badge-warning">{$item.mode}</span>
                            {/if}
                            {if $item.mode == "Shoppa"}
                                <span style="margin-right: 5px; width: 100px; background-color:coral;" class="badge badge-warning">{$item.mode}</span>
                            {/if}
                            <br>
                            Sum: {$item.sum}€<br>
                            Card: {$item.card}€<br>
                            Cash: {$item.cash}€
                            {if isset($item.tellimuseNr) and $item.tellimuseNr != ""}
                                <br>Tellimuse Nr: {$item.tellimuseNr}
                            {/if}
                        </p>
                        <a class="btn btn-primary" target="_blank" rel="noopener noreferrer" href="/cp/POS/sales/printReceipt.php?view={$item.id}">Print receipt</a>
                    </div>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            {foreach $desc as $prod}
                                <tr>
                                    {if is_numeric($prod.id)}
                                        <td class="td-20"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id}">{$prod.tag}</a></td>
                                        <td class="td-20"><a style="color: white;text-overflow: ellipsis; " href="/cp/WMS/view/?view={$prod.id}">{$prod.name}</a></td>
                                        {else}
                                        <td class="td-20">{$prod.tag}</td>
                                        <td class="td-20">{$prod.name}</td>
                                    {/if}
                                    <td class="td-20">{$prod.quantity}</td>
                                    <td>{$prod.price} €</td>
                                    <td>{$prod.status}</td>
                                    <td>
                                        {if $prod.status != "Tagastus"}
                                            <a class="btn btn-outline-danger" href="/cp/POS/sales/index.php?tagastusFull[]={$prod.saleID}">
                                                <i class="fas fa-frown"></i>
                                                Tagastus
                                            </a>
                                        {/if}

                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                        <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/POS/sales"><i class="fas fa-undo-alt"></i> Back</a>
                        {/foreach}

                </div>
            </div>
        </div>
    </div>
</main>
{include file='footer.tpl'}
