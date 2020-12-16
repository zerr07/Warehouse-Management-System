{include file='header.tpl'}

<div class="row">
    <div class="col-md-12">
        {foreach $rules as $item}
            <div class="row mt-3 border border-secondary p-2">
                <div class="col-12 col-sm-12 col-md-3 text-center m-auto">
                    {$item.start}€ to {$item.end}€
                </div>
                <div class="col-6 col-sm-6 col-md-3 text-center m-auto">
                   Margin: {$item.percent}%
                </div>
                <div class="col-6 col-sm-6 col-md-3 text-center m-auto">
                   Min: {$item.margin}€
                </div>
                <div class="col-12 col-sm-12 col-md-3">
                    <a href="/cp/WMS/priceRule/index.php?delete={$item.id}" class="btn btn-danger w-100">Delete</a>
                </div>
            </div>
        {/foreach}
        <form action="#" method="post">
            <div class="form-row align-items-end">
                <div class="form-group col-6 col-sm-6 col-md-3">
                    <label for="start">From price €</label>
                    <input type="text" class="form-control" name="start" id="start" placeholder="From price €" required>
                </div>
                <div class="form-group col-6 col-sm-6 col-md-3">
                    <label for="end">To price €</label>
                    <input type="text" class="form-control" name="end" id="end"  placeholder="To price €" required>
                </div>
                <div class="form-group col-6 col-sm-6 col-md-2">
                    <label for="percent">Percent %</label>
                    <input type="number" class="form-control" name="percent" id="percent"  placeholder="Percent %" required>
                </div>
                <div class="form-group col-6 col-sm-6 col-md-2">
                    <label for="margin">Min margin €</label>
                    <input type="number" class="form-control" name="margin" step="0.01" id="margin"  placeholder="Min margin €">
                </div>
                <div class="form-group col-12 col-sm-12 col-md-2">
                    <input type="submit" class="btn btn-info w-100" name="submit" id="form17" value="Add new">
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Price rules");
    });
</script>
{include file='footer.tpl'}