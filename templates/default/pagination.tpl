<div class="row">
    <div class="col-md-12">
        <ul class="pagination ul-center">
            <li class="page-item li-center">
                <a class="page-link" href="?page=1" >
                    <span>«</span> <span class="sr-only">First page</span>
                </a>
            </li>
            {foreach $pages.pages as $page}
                {if $page != $current_page}
                    <li class="page-item li-center">
                        <a class="page-link" href="{$pageBase}page={$page}">{$page}</a>
                    </li>
                {else}
                    <li class="page-item li-center active">
                        <a class="page-link" href="#">{$page}</a>
                    </li>
                {/if}
            {/foreach}

            <li class="page-item li-center">
                <a class="page-link" href="{$pageBase}page={$pages.lastPage}">
                    <span>»</span> <span class="sr-only">Last page</span>
                </a>
            </li>
        </ul>
    </div>
</div>