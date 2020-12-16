{include file='header.tpl'}

<!-- Load c3.css -->
<script src="/templates/default/assets/js/d3.min.js?t=16102020T165340"></script>
<script src="/templates/default/assets/js/c3.min.js?t=16102020T165341"></script>
<link href="/templates/default/assets/css/c3.min.css?t=16102020T165344" rel="stylesheet" />

<script src="/templates/default/assets/js/moment.js"></script>
<script src="/templates/default/assets/js/auction_charts_init.js?d=20201112T103709"></script>

<div class="row mt-4">
    <div id="auction_charts"></div>
    <div class="col-12">
        <input type="text" name="dates" class="form-control" placeholder="Date range">
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1>Auctions summary</h1>
                <span>
                    {if $showing}
                        Showing last 14 days
                        {else}
                        Showing specified period {$date1} - {$date2}
                    {/if}

                </span>
                <div class="row mt-3">
                    <div class="col-6 col-sm-6 col-md-3">Profit sum:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.profitsum|round:2} €</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">Final price sum:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.finalsum|round:2} €</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">Count sum:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.countsum|round:2}</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">Profit avg:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.profitavg|round:2} €</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">Lisateenused sum:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.lisasum|round:2} €</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">Buy price sum:</div>
                    <div class="col-6 col-sm-6 col-md-9">{$AuctionsSummary.buysum|round:2} €</div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-3">ROI:</div>
                    <div class="col-6 col-sm-6 col-md-9">{($AuctionsSummary.roi*100)|round:2}%</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="accordion" id="accordionSKU">
            {foreach $AuctionsSKU as $sku => $value}
                <div class="card">
                    <div class="card-header" id="heading{$sku}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse{$sku}" aria-expanded="true" aria-controls="collapse{$sku}"
                                    onclick="loadSKUdata('{$sku}', '{$between}')">
                                <i class="fas fa-cat"></i> {$sku} - {$value}
                            </button>
                        </h2>
                    </div>

                    <div id="collapse{$sku}" class="collapse" aria-labelledby="heading{$sku}" data-parent="#accordionSKU">
                        <div class="card-body">
                            <a href="/cp/WMS/?searchTagID={$sku}">Go to item</a>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Profit sum:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="profitsum{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Final price sum:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="finalsum{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Count sum:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="countsum{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Profit avg:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="profitavg{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Lisateenused sum:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="lisasum{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">Buy price sum:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="buysum{$sku}"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-3">ROI:</div>
                                <div class="col-6 col-sm-6 col-md-9" id="roi{$sku}"></div>
                            </div>
                            <button type="button" class="btn btn-info" onclick="loadAuctionCharts('{$sku}')"
                            ><i class="fas fa-ad"></i> View auction charts</button>

                        </div>
                    </div>
                </div>
            {/foreach}

        </div>
    </div>
</div>
<script src="/templates/default/assets/js/moment.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    window.addEventListener("load", function () {
        setPageTitle("Auction statistics");
    });
    $('input[name="dates"]').daterangepicker();
    $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        window.location.href = "?between=" + encodeURIComponent($('input[name="dates"]').val());

    });
    function loadSKUdata(sku, between){
        $.ajax({
            type: "GET",
            cache: false,
            url: "/controllers/products/getAuctionsStats.php?tag=" + sku + "&between=" + between,
            success:function(data) {
                console.log(data);
                let d = JSON.parse(data);
                document.getElementById("profitsum"+sku).innerText  = ((!isNaN(round2D(d['profitsum']   ))) ? round2D(d['profitsum'])+"€"   : 'Could not parse')
                document.getElementById("finalsum"+sku).innerText   = ((!isNaN(round2D(d['finalsum']    ))) ? round2D(d['finalsum'])+"€"    : 'Could not parse')
                document.getElementById("countsum"+sku).innerText   = ((!isNaN(round2D(d['countsum']    ))) ? round2D(d['countsum'])        : 'Could not parse')
                document.getElementById("profitavg"+sku).innerText  = ((!isNaN(round2D(d['profitavg']   ))) ? round2D(d['profitavg'])+"€"   : 'Could not parse')
                document.getElementById("lisasum"+sku).innerText    = ((!isNaN(round2D(d['lisasum']     ))) ? round2D(d['lisasum'])+"€"     : 'Could not parse')
                document.getElementById("buysum"+sku).innerText     = ((!isNaN(round2D(d['buysum']      ))) ? round2D(d['buysum'])+"€"      : 'Could not parse')
                document.getElementById("roi"+sku).innerText        = ((!isNaN(round2D(d['roi']         ))) ? round2D(d['roi']*100)+"%"     : 'Could not parse')


            }
        });
    }

</script>
{include file='footer.tpl'}
