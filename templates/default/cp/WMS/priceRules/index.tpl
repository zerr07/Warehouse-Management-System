{include file='header.tpl'}

<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">
                    <div class="table-responsive" >
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>From price €</th>
                                <th>To price €</th>
                                <th>Percent %</th>
                                <th>Min margin €</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $rules as $item}
                                <tr>
                                    <td>{$item.start}€</td>
                                    <td>{$item.end}€</td>
                                    <td>{$item.percent}%</td>
                                    <td>{$item.margin}€</td>
                                    <td><a href="/cp/WMS/priceRule/index.php?delete={$item.id}" class="btn btn-danger">Delete</a></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <form class="text-left" action="#" method="post">
                        <input type="text" class="form-control SMTitemsSM"   style="max-width: 19.8% !important;" name="start" id="form17"  placeholder="From price €" required>
                        <input type="text" class="form-control SMTitemsSM"   style="max-width: 19.8% !important;"  name="end" id="form17"  placeholder="To price €" required>
                        <input type="number" class="form-control SMTitemsSM" style="max-width: 19.8% !important;"  name="percent" id="form17"  placeholder="Percent %" required>
                        <input type="number" class="form-control SMTitemsSM" style="max-width: 19.8% !important;" name="margin" step="0.01" id="form17"  placeholder="Min margin €">
                        <input type="submit" class="btn btn-info SMTitemsSM" style="max-width: 19.8% !important;"  name="submit" id="form17" value="Add new">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
{include file='footer.tpl'}