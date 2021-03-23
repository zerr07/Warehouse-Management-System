{* Default tree *}
{function name=cat_tree margin=1}
    {foreach $data as $key => $value}
        <input class='form-check-input' type='checkbox' name='cat[]' id='cat{$key}' value='{$key}' >
        <input class='form-check-input ml-0' type='radio' name='catmain' id='catMain{$key}' value='{$key}' disabled>
        {if is_null($value.child) || sizeof($value.child) === 0}
            <label class='form-check-label ml-3 text-white' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary ml-2 mr-2">{$value.count}</span>
            </label><br>
        {else}
            <label class='form-check-label ml-3 text-white' data-toggle='collapse' data-target="#k{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary ml-2 mr-2">{$value.count}</span>+</label><br>
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
        {if in_array($key, $item.categories)}
            <script>
                $(window).on("load", function () {
                    $('#collapse{$value.parent}').collapse('toggle');
                });
            </script>
            <input class='form-check-input' type='checkbox' name='cat[]' id='cat{$key}' value='{$key}' checked>
            {if $item.main_category == $key}
                <input class='form-check-input ml-0' type='radio' name='catmain' id='catMain{$key}' value='{$key}' checked>
                {else}
                <input class='form-check-input ml-0' type='radio' name='catmain' id='catMain{$key}' value='{$key}'>
            {/if}

        {else}
            <input class='form-check-input' type='checkbox' name='cat[]' id='cat{$key}' value='{$key}' >
            <input class='form-check-input ml-0' type='radio' name='catmain' id='catMain{$key}' value='{$key}' disabled>

        {/if}
        {if is_null($value.child) || sizeof($value.child) === 0}
            <label class='form-check-label ml-3 text-white' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary ml-2 mr-2">{$value.count}</span>
            </label><br>
        {else}
            <label class='form-check-label ml-3 text-white' data-toggle='collapse' data-target="#collapse{$key}" aria-expanded='false' aria-controls='collapseExample' for='exampleRadios1'>
                {$value.name}<span class="badge badge-secondary ml-2 mr-2">{$value.count}</span>+</label><br>
            <div  class='collapse collapseDiv' id='collapse{$key}' style="margin: 0 0 0 {$margin*20}px;">
                {if !is_null($value.child)}
                    {cat_treeEdit data=$value.child margin=$margin+1}
                {/if}
            </div>
        {/if}
    {/foreach}
{/function}

{if isset($item.categories) && !is_null($item.categories)}
    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
            data-target=".collapseDiv" aria-expanded="false" style="margin-left: -1.25rem;"
            aria-controls='collapseExample'>Expand all</button>
    <br>
    <input class='form-check-input' type='checkbox' name='cat[]' id='none' value='None' required>
    <label class='form-check-label text-white' for='exampleRadios1'>None</label><br>

    {cat_treeEdit data=$cat_tree}

{else}
    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
            data-target=".collapseDiv" aria-expanded="false" style="margin-left: -1.25rem;"
            aria-controls='collapseExample'>Expand all</button>
    <br>
    <input class='form-check-input' type='checkbox' name='cat[]' id='none' value='None' required checked>
    <label class='form-check-label text-white' for='exampleRadios1'>None</label><br>
    {cat_tree data=$cat_tree}
{/if}