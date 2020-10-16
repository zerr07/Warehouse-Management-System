{include file='header.tpl'}
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
{include file='cp/POS/sales/invoice.tpl'}

<div class="row mt-3">
    <div class="col-md-12">
        {foreach $sales as $item}
            <div class="row">
                <div class="col-6" style="display: inline-flex;">
                    <p>
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
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="OpenReceipt()">Print receipt</button>
                    <script>
                        function OpenReceipt()
                        {
                            window.open("/cp/POS/sales/printReceipt.php?view={$item.id}", '_blank');
                        }
                    </script>
                    {literal}
                        <button type="button" class="btn btn-info ml-2"  onclick="printJS(
                               {printable: 'form', type: 'html',
                               documentTitle: 'Invoice',
                               css: 'https\://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'}
                                       )">
                            Print invoice
                        </button>
                    {/literal}
                </div>
            </div>

                {foreach $desc as $prod}

                    <div class="row mt-3 border border-secondary p-1">
                        {if is_numeric($prod.id)}
                            <div class="col-2 col-sm-2 col-md-1 m-auto"><a style="color: white;text-overflow: ellipsis;" href="/cp/WMS/view/?view={$prod.id}">{$prod.tag}</a></div>
                            <div class="col-6 col-sm-6 col-md-3 m-auto text-truncate"><a style="color: white;text-overflow: ellipsis;" href="/cp/WMS/view/?view={$prod.id}">{$prod.name}</a></div>
                        {else}
                            <div class="col-4 col-sm-4 col-md-2 m-auto">{$prod.tag}</div>
                            <div class="col-4 col-sm-4 col-md-2 m-auto">{$prod.name}</div>
                        {/if}
                        <div class="col-4 col-sm-4 col-md-2 m-auto">{$prod.quantity} pcs</div>
                        <div class="col-4 col-sm-4 col-md-2 m-auto">{$prod.price} €</div>
                        <div class="col-4 col-sm-4 col-md-2 m-auto">{$prod.status}</div>
                        <div class="col-4 col-sm-4 col-md-2 m-auto">
                            {if $prod.status != "Tagastus"}
                                <a class="btn btn-outline-danger" href="/cp/POS/sales/index.php?tagastusFull[]={$prod.saleID}">
                                    <i class="fas fa-frown"></i>
                                    Tagastus
                                </a>
                            {/if}
                        </div>
                    </div>
                {/foreach}

            <div class="row mt-3">

                <div class="col-12 d-flex justify-content-end">
                    <a class="btn btn-primary d-inline-flex ml-2" href="/cp/POS/sales"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        {/foreach}
    </div>
</div>

{include file='footer.tpl'}
