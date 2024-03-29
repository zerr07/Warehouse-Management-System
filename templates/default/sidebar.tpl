{assign var="filter" value="filter2"}
{assign var="filter_text" value="filter_text2"}

<div id="sidebar_menu" class="">
    <div class="row w-100 m-0">
        <div class="col-12 w-100 p-0" id="sidebar_body">
            <a href="javascript:void(0)" class="close_btn" onclick="closeNav()">&times;</a>

            <a href="/" class="sidebar_item mt-5">  <i class="fas fa-home"></i> Home</a>
            <a href="/cp/POS" class="sidebar_item">       <i class="fas fa-store"></i> POS</a>

            <a class="sidebar_item" href="javascript:void(0);" data-toggle="collapse" data-target="#btnGroupDrop1"
               role="button" aria-expanded="false" aria-controls="btnGroupDrop1">
                <i class="fas fa-qrcode"></i> Scanners <i class="dropdown_svg fas fa-chevron-right"></i>
            </a>
            <div class="collapse multi-collapse sidebar_dropdown_menu" id="btnGroupDrop1">
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/mobileScanner.php"><i class="fas fa-qrcode"></i> Mobile scanner</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/manualMobileScanner.php"><i class="fas fa-qrcode"></i> Manual mobile scanner</a>
            </div>

            <a class="sidebar_item" href="javascript:void(0);" data-toggle="collapse" data-target="#btnGroupDrop2"
               role="button" aria-expanded="false" aria-controls="btnGroupDrop2">
                <i class="fas fa-cogs"></i> Tools <i class="dropdown_svg fas fa-chevron-right"></i>
            </a>
            <div class="collapse multi-collapse sidebar_dropdown_menu" id="btnGroupDrop2">
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/platforms/"><i class="fas fa-store-alt"></i> Platforms</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="#staticBackdrop" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-tags"></i> Custom Label Generator</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="#staticBackdropLG" data-toggle="modal" data-target="#staticBackdropLG"><i class="fas fa-tags"></i> Large Custom Label Generator</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/tree-links/"><i class="fas fa-tree"></i> Tree links</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/FB/auctions/"><i class="fab fa-facebook-square"></i> Facebook auctions controls</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/XML/"><i class="far fa-file-code"></i> XML generator</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/properties"><i class="fas fa-ellipsis-v"></i> Properties</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/parsers/list/"><i class="far fa-list-alt"></i> Parser list by images</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="javascript:void(0);" data-toggle="collapse" data-target="#btnGroupDropParsers"
                   role="button" aria-expanded="false" aria-controls="btnGroupDropParsers">
                    <i class="far fa-list-alt"></i> Parser list by sku<i class="dropdown_svg fas fa-chevron-right"></i>
                </a>
                <div class="collapse multi-collapse sidebar_dropdown_menu" id="btnGroupDropParsers">
                    {if isset($parser_profiles_sku)}
                        {foreach $parser_profiles_sku as $key => $value}
                            <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/parsers/sku/?platform={$key} " onclick="closeNav();turnOnPreloader()"><i class="far fa-list-alt"></i> {$key} </a>
                        {/foreach}
                    {/if}
                </div>


            </div>

            <a class="sidebar_item" href="javascript:void(0);" data-toggle="collapse" data-target="#btnGroupDrop4"
               role="button" aria-expanded="false" aria-controls="btnGroupDrop4">
                <i class="fas fa-chart-line"></i> Statistics <i class="dropdown_svg fas fa-chevron-right"></i>
            </a>
            <div class="collapse multi-collapse sidebar_dropdown_menu" id="btnGroupDrop4">
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/statistics/auctions/"><i class="fas fa-gavel"></i> Auctions</a>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/statistics/export/" onclick="closeNav();turnOnPreloader()"><i class="far fa-list-alt"></i> Export </a>
            </div>


            <a class="sidebar_item" href="javascript:void(0);" data-toggle="collapse" data-target="#btnGroupDrop3"
               role="button" aria-expanded="false" aria-controls="btnGroupDrop3">
                <i class="fas fa-qrcode"></i> Shards <i class="dropdown_svg fas fa-chevron-right"></i>
            </a>
            <div class="collapse multi-collapse sidebar_dropdown_menu" id="btnGroupDrop3">
                {foreach $shards as $key => $shard}
                    <a class="dropdown-item sidebar_item sidebar_dropdown_item" onclick="
                            setCookie('shard', '{$shard}', 365);
                            setCookie('id_shard', '{$key}', 365);
                            location.reload();
                            ">{$shard}</a>
                {/foreach}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item sidebar_item sidebar_dropdown_item" href="/cp/WMS/shards">Manage</a>
            </div>
            {*<a href="/cp/chat" class="sidebar_item"><i class="far fa-comments"></i> Chat</a>*}
            <hr style="border-color: #4c4c4c;">

            <span class="sidebar_item mt-3">Logged as {$user}</span>
            <a href="/cp/profile" class="sidebar_item"><i class="far fa-id-card"></i> Profile</a>

            <a class="sidebar_item" href="/cp/WMS/HourLogger.php" role="button">
                <i class="fas fa-user-clock"></i> Hour logger
            </a>


            <a class="sidebar_item" href="?logout"><i class="fas fa-sign-out-alt"></i>Logout</a>

        </div>
    </div>

</div>
