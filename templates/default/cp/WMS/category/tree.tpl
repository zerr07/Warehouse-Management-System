{* Default tree *}
{function name=cat_tree margin=1}
    {foreach $data as $key => $value}
        <input class='form-check-input' type='radio' name='cat' id='cat{$key}' value='{$key}' >
        {if is_null($value.child)}
            <label class='form-check-label' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary">{$value.count}</span>
            </label><br>
        {else}
            <label class='form-check-label' data-toggle='collapse' data-target="#k{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary">{$value.count}</span>+</label><br>
            <div  class='collapse collapseDiv' id='k{$key}' style="margin: 0 0 0 {$margin*20}px">
                {if !is_null($value.child)}
                    {cat_tree data=$value.child margin=$margin+1}
                {/if}
            </div>
        {/if}

    {/foreach}
{/function}
{* Tree for product edit *}
{function name=cat_treeEdit margin=1}
    {foreach $data as $key => $value}
        {if $item.id_category == $key}
            <script>
            $('#collapse{$value.parent}').show()
            </script>
            <input class='form-check-input' type='radio' name='cat' id='cat{$key}' value='{$key}' checked>
        {else}
            <input class='form-check-input' type='radio' name='cat' id='cat{$key}' value='{$key}' >
        {/if}
        {if is_null($value.child)}
            <label class='form-check-label' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary">{$value.count}</span>
            </label><br>
        {else}
            <label class='form-check-label' data-toggle='collapse' data-target="#collapse{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary">{$value.count}</span>+</label><br>
            <div  class='collapse collapseDiv' id='collapse{$key}' style="margin: 0 0 0 {$margin*20}px">
                {if !is_null($value.child)}
                    {cat_treeEdit data=$value.child margin=$margin+1}
                {/if}
            </div>
        {/if}
    {/foreach}
{/function}

{if isset($item.id_category) && !is_null($item.id_category)}
    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
            data-target=".collapseDiv" aria-expanded="false" style="margin-left: -1.25rem;"
            aria-controls='collapseExample'>Expand all</button>
    <br>
    <input class='form-check-input' type='radio' name='cat' id='none' value='None' required>
    <label class='form-check-label' for='exampleRadios1'>None</label><br>

    {cat_treeEdit data=$cat_tree}

{else}
    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
            data-target=".collapseDiv" aria-expanded="false" style="margin-left: -1.25rem;"
            aria-controls='collapseExample'>Expand all</button>
    <br>
    <input class='form-check-input' type='radio' name='cat' id='none' value='None' required checked>
    <label class='form-check-label' for='exampleRadios1'>None</label><br>
    {cat_tree data=$cat_tree}
{/if}
