{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-5 text-center text-white fullHeight" >
        <div class="container">

            <div class="row">
                <div class="col-md-12 text-left">
                    {* Default tree *}
                    {function name=cat_tree margin=1}
                        {foreach $data as $key => $value}
                            {if is_null($value.child)}
                                <label class='form-check-label' for='exampleRadios1'>{$value.cat_id} {$value.name}</label><br>
                            {else}
                                <label class='form-check-label' data-toggle='collapse' data-target="#k{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                                    {$value.cat_id} {$value.name}  +</label><br>
                                <div  class='collapse' id='k{$key}' style="margin: 0 0 0 {$margin*20}px">
                                    {if !is_null($value.child)}
                                        {cat_tree data=$value.child margin=$margin+1}
                                    {/if}
                                </div>
                            {/if}

                        {/foreach}
                    {/function}

                    {cat_tree data=$cat_tree}
                </div>
            </div>
        </div>
    </div>
</main>

{include file='footer.tpl'}
