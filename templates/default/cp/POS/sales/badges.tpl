{if $item.mode == "Bigshop"}
    <span style="margin-right: 5px; width: 100px; background-color:#009ac0;" class="badge badge-warning">{$item.mode}</span>
{/if}
{if $item.mode == "Minuvalik"}
    <span style="margin-right: 5px; width: 100px; background-color:greenyellow; color: black" class="badge badge-warning">{$item.mode}</span>
{/if}
{if $item.mode == "Osta"}
    <span style="margin-right: 5px; width: 100px; background-color:orange;" class="badge badge-warning">{$item.mode}</span>
{/if}
{if $item.mode == "Shoppa"}
    <span style="margin-right: 5px; width: 100px; background-color:coral;" class="badge badge-warning">{$item.mode}</span>
{/if}