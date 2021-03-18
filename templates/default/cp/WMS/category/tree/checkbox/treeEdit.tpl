{* Tree for category edit *}
{function name=cat_treeCatEdit margin=1}
    {foreach $data as $key => $value}
        {if $item.parent == $key}
            <script>
                $(window).on("load", function () {
                    $('#collapse{$value.parent}').collapse('toggle');
                });
            </script>
            <input class='form-check-input' type='checkbox' name='cat[]' id='cat{$key}' value='{$key}' checked>
        {else}
            <input class='form-check-input' type='checkbox' name='cat[]' id='cat{$key}' value='{$key}' >
        {/if}
        {if is_null($value.child)}
            <label class='form-check-label' for='exampleRadios1'>{$value.name}</label><br>
        {else}
            <label class='form-check-label' data-toggle='collapse' data-target="#collapse{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                {$value.name}+</label><br>
            <div  class='collapse collapseDiv' id='collapse{$key}' style="margin: 0 0 0 {$margin*20}px">
                {if !is_null($value.child)}
                    {cat_treeCatEdit data=$value.child margin=$margin+1}
                {/if}
            </div>
        {/if}
    {/foreach}
{/function}
<button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
        data-target=".collapseDiv" aria-expanded="false" style="margin-left: -1.25rem;"
        aria-controls='collapseExample'>Expand all</button>
<br>
<input class='form-check-input' type='checkbox' name='cat[]' id='none' value='None' required>
<label class='form-check-label' for='exampleRadios1'>None</label><br>
{cat_treeCatEdit data=$cat_tree}