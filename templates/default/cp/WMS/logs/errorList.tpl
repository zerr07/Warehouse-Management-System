{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12">
        {foreach $errors as $v}
            <div class="row">
                <div class="col-2">
                    <a href="/cp/WMS/view/?view={$v.id_product}">{$v.id_product}</a>
                </div>
                <div class="col-2">
                    <span>
                        {$v.id_platform}
                    </span>
                </div>
                <div class="col-4">
                    <span>
                        {$v.msg}
                    </span>
                </div>
                <div class="col-4">
                    {$v.date_created}
                </div>
            </div>
        {/foreach}
    </div>
</div>

{include file='footer.tpl'}