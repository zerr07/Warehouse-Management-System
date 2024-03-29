{include file='header.tpl'}
            <div class="accordion text-left mt-4" id="accordion">

                <div class="card">
                    <div class="card-header" id="heading0240">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0240" aria-expanded="false" aria-controls="collapse0240">
                                Version 0.24.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0240" class="collapse" aria-labelledby="heading0240" data-parent="#accordion">
                        <div class="card-body">
                            Product translations added to the API.<br />
                            Product creation route added to API (Not yet documented). <br/>
                            Languages function added<br/>
                            Added pagination to checked out shipments.<br/>
                            Improved performance by optimizing SQL queries.<br/>
                            Export statistics added.<br/>
                            XML generator logs.<br/>
                            Category full path function modified to allow different language output.<br/>
                            <hr>
                            Changed buttons layout.<br />
                            Fixed categories output.<br/>
                            Fixed platform output array.<br/>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0230">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0230" aria-expanded="false" aria-controls="collapse0230">
                                Version 0.23.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0230" class="collapse" aria-labelledby="heading0230" data-parent="#accordion">
                        <div class="card-body">
                            PUT request replace to optimise speed.<br />
                            Ean codes and manufacturer added to prestashop API.<br />
                            Manufacturer functions added.<br />
                            New route for locations <br />
                            Sync controller for prestashop. <br />
                            "Remove all" added to xml generator<br />
                            Product editor modified<br />
                            List of parsed by SKU fixed so that it starts loading from where it finished the last load<br />
                            Main category can now be selected on product add/edit page<br />
                            Prestashop API now updates images separately rather than reuploads them<br />
                            Reservastions are sorted now<br />
                            Product flags implemented (can be used to mark errors or notifications for products)<br />
                            Parser by sku modified to exclude products from output<br />
                            Product dimensions added<br />
                            Function to get only exported main category added<br />
                            Price parser modified to apply prices specific for each platform<br />
                            Delete property function implemented<br />
                            Search optimized for different shards<br />
                            Price parser added<br />
                            Parser functions calls modified to include platform name in it<br />
                            Category full path function implemented<br />
                            Separated home warehouses from supplier<br />
                            Category tree is now in the same form with other search options<br />
                            Manual mobile scanner optimised for both desktop and mobile devices<br />
                            Parser by SKU is now procedural.<br />
                            Enabled lazyload for images on main page<br />
                            Api call to sync supplier data added<br />
                            Added check in parser if site is reachable<br />
                            Added error notification to parser<br />
                            <hr>
                            Escape fixed.<br />
                            POS search by name, fixed empty name.<br />
                            Platform search fixed<br />
                            Category bulk link fixed missing variable<br />
                            Default file input file display fixed<br />
                            Scanners layout fixed<br />
                            Fixed check on Smartpost submit<br />
                            Fixed reservations product load with char escape<br />
                            Fixed non existent variables on product add<br />
                            Fixed empty names in Auction statistics<br />
                            Fixed location operations without sync<br />
                            Carrier sync fixed<br />
                            Fixed quote escape<br />
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0220">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0220" aria-expanded="false" aria-controls="collapse0220">
                                Version 0.22.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0220" class="collapse" aria-labelledby="heading0220" data-parent="#accordion">
                        <div class="card-body">
                            Name and tag copy added to sale items in case of item deletion.<br />
                            Multiple categories selection implemented.<br />
                            Function to toggle multiple tabs at once implemented<br />
                            <hr>
                            Fixed search field in POS.<br />
                            Changed redirection on product deletion.<br />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0211">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0211" aria-expanded="false" aria-controls="collapse0211">
                                Version 0.21.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0211" class="collapse" aria-labelledby="heading0211" data-parent="#accordion">
                        <div class="card-body">
                            Added function to move products between categories.<br />
                            Datalist added for categories move form.<br />
                            Parser by sku implemented<br />
                            <hr>
                            Fixed z-index on custom alert.<br />
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0210">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0210" aria-expanded="false" aria-controls="collapse0210">
                                Version 0.21.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0210" class="collapse" aria-labelledby="heading0210" data-parent="#accordion">
                        <div class="card-body">
                            Custom alert box implemented<br/>
                            Parser implemented.<br />
                            Images tab in editor separated from categories.<br />
                            Text auto translation implemented.<br />
                            Invoice bank details switch added.<br />
                            <hr>
                            Products controller description language fix.<br />
                            Fixed bug when product does not have platform data it db.<br />
                            Encoding fixed in barcode generator link<br />
                            {literal}
                                <pre>
/\_/\
(='_' )
(, (") (")
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0200">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0200" aria-expanded="false" aria-controls="collapse0200">
                                Version 0.20.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0200" class="collapse" aria-labelledby="heading0200" data-parent="#accordion">
                        <div class="card-body">
                            Product properties implemented.<br/>
                            Hour logger date format adjusted.<br />
                            Product cache implemented to increase search speed.<br />
                            Product output limited to increase performance.<br />
                            Shipment status added to reserve info.<br />
                            Chat disabled<br />
                            Platform search extended<br/>
                            Venipak added to Shipments API<br/>
                            Latvian and Lithuanian languages added to products and categories. <br/>
                            <hr>
                            Invalid key in shipment API fixed.<br />
                            Smartpost label output fixed.<br />
                            Pagination fixed for new search filters.<br />
                            {literal}
                                <pre>
{\__/}
(●_●)
( > 🍪 Want a cookie?
{\__/}
(●_●)
( 🍪< No my cookie.
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="heading0190">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0190" aria-expanded="false" aria-controls="collapse0190">
                                Version 0.19.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0190" class="collapse" aria-labelledby="heading0190" data-parent="#accordion">
                        <div class="card-body">
                            Added check if status already set<br/>
                            Added check for id in shipping conversion.<br />
                            Merge reservation API implemented.<br />
                            Added English names for export purposes.<br />
                            Extended config. Now able to disable connections to external databases.<br />
                            <hr>
                            Fixed locations bug on new products added to reservation.<br />
                            Multi submit prevention fix.<br />
                            Layout fix when no products on the page.<br />
                            {literal}
                                <pre>
༼ つ ◕_◕ ༽つ gib
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0180">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0180" aria-expanded="false" aria-controls="collapse0180">
                                Version 0.18.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0180" class="collapse" aria-labelledby="heading0180" data-parent="#accordion">
                        <div class="card-body">
                            New search options added.<br />
                            Shipment API extended.<br />
                            Reservation confirm added to the API.<br />
                            Sales functions added to the API.<br />
                            <hr>
                            Fixed minor js bugs.<br />
                            Fixed ability to view shipment on reservations page.<br />
                            Hour logger mobile layout fix.<br />
                            {literal}
                                <pre>
┈┈┈┈┈┈┈┈┈
┈┈┈┈┈┈┈┈┈┈┈┈╭━━━
┈┈┈╭━━╮┈┈┈┈┈┃RAWR
┈┈╭╯┊◣╰━━━╮┈╰┳━━
┈┈┃┊┊┊╱▽▽▽┛┈┈┃┈┈
┈┈┃┊┊┊▏━━━━━━╯┈┈
━━╯┊┊┊╲△△△┓┈┈┈┈┈
┊┊┊┊╭━━━━━╯┈┈┈┈┈
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0170">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0170" aria-expanded="false" aria-controls="collapse0170">
                                Version 0.17.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0170" class="collapse" aria-labelledby="heading0170" data-parent="#accordion">
                        <div class="card-body">
                            New api for reservations implemented.<br />
                            Routing base controller implemented.<br />
                            Token checker implemented.<br />
                            User profile implemented.<br />
                            Access token generator.<br />
                            Hour logger implemented<br />
                            <hr>
                            Fixed locations bug on api reservation request<br />
                            {literal}
                                <pre>
▄▄░░▄██▄░░░
░░░░░▐▀█▀▌░░░░▀█▄░░░
░░░░░▐█▄█▌░░░░░░▀█▄░░
░░░░░░▀▄▀░░░▄▄▄▄▄▀▀░░
░░░░▄▄▄██▀▀▀▀░░░░░░░
░░░█▀▄▄▄█░▀▀░░
░░░▌░▄▄▄▐▌▀▀▀░░ This is Bob
▄░▐░░░▄▄░█░▀▀ ░░
▀█▌░░░▄░▀█▀░▀ ░░
░░░░░░░▄▄▐▌▄▄░░░
░░░░░░░▀███▀█░▄░░
░░░░░░▐▌▀▄▀▄▀▐▄░░
░░░░░░▐▀░░░░░░▐▌░░
░░░░░░█░░░░░░░░█
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0160">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0160" aria-expanded="false" aria-controls="collapse0160">
                                Version 0.16.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0160" class="collapse" aria-labelledby="heading0160" data-parent="#accordion">
                        <div class="card-body">
                            Search by SKU added to search by name function.<br />
                            Added mode selection to description editor.<br />
                            Disabled check for duplicate products in FB control.<br />
                            Full manual prestashop synchronization implemented.<br />
                            Search functions limiter function added.<br />
                            <hr>
                            Fixed bug on script trying to enable warning on non existing button.<br />
                            Fixed bug on multiple hash sign removal.<br />
                            Fixed cart check. <br />
                            Fixed bugs in prestashop API calls.<br />
                            {literal}
                                <pre>


⢸⠉⠉⠉⠉⠉⠉⠉⠉⠉⠉⠉⠉⠉⡷⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⢸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⡇⠢⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⢸⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀⠈⠑⢦⡀⠀⠀⠀⠀⠀
⢸⠀⠀⠀⠀⢀⠖⠒⠒⠒⢤⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠙⢦⡀⠀⠀⠀⠀
⢸⠀⠀⣀⢤⣼⣀⡠⠤⠤⠼⠤⡄⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠙⢄⠀⠀⠀⠀
⢸⠀⠀⠑⡤⠤⡒⠒⠒⡊⠙⡏⠀⢀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠑⠢⡄⠀
⢸⠀⠀⠀⠇⠀⣀⣀⣀⣀⢀⠧⠟⠁⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⡇⠀
⢸⠀⠀⠀⠸⣀⠀⠀⠈⢉⠟⠓⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⠈⢱⡖⠋⠁⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⣠⢺⠧⢄⣀⠀⠀⣀⣀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⣠⠃⢸⠀⠀⠈⠉⡽⠿⠯⡆⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⣰⠁⠀⢸⠀⠀⠀⠀⠉⠉⠉⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠣⠀⠀⢸⢄⠀⠀⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⠀⢸⠀⢇⠀⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⠀⡌⠀⠈⡆⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⢠⠃⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸
⢸⠀⠀⠀⠀⢸⠀⠀⠀⠁⠀⠀⠀⠀⠀⠀⠀⠷
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="heading0150">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0150" aria-expanded="false" aria-controls="collapse0150">
                                Version 0.15.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0150" class="collapse" aria-labelledby="heading0150" data-parent="#accordion">
                        <div class="card-body">
                            Prestashop synchronization is now API based.<br />
                            XML generator product parent tag output fixed.<br />
                            {literal}
                                <pre>
                                    ⡏⠉⠉⠉⠉⠉⠉⠋⠉⠉⠉⠉⠉⠉⠋⠉⠉⠉⠉⠉⠉⠉⠉⠉⠉⠙⠉⠉⠉⠹
                                    ⡇⢸⣿⡟⠛⢿⣷⠀⢸⣿⡟⠛⢿⣷⡄⢸⣿⡇⠀⢸⣿⡇⢸⣿⡇⠀⢸⣿⡇⠀
                                    ⡇⢸⣿⣧⣤⣾⠿⠀⢸⣿⣇⣀⣸⡿⠃⢸⣿⡇⠀⢸⣿⡇⢸⣿⣇⣀⣸⣿⡇⠀
                                    ⡇⢸⣿⡏⠉⢹⣿⡆⢸⣿⡟⠛⢻⣷⡄⢸⣿⡇⠀⢸⣿⡇⢸⣿⡏⠉⢹⣿⡇⠀
                                    ⡇⢸⣿⣧⣤⣼⡿⠃⢸⣿⡇⠀⢸⣿⡇⠸⣿⣧⣤⣼⡿⠁⢸⣿⡇⠀⢸⣿⡇⠀
                                    ⣇⣀⣀⣀⣀⣀⣀⣄⣀⣀⣀⣀⣀⣀⣀⣠⣀⡈⠉⣁⣀⣄⣀⣀⣀⣠⣀⣀⣀⣰
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0140">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0140" aria-expanded="false" aria-controls="collapse0140">
                                Version 0.14.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0140" class="collapse" aria-labelledby="heading0140" data-parent="#accordion">
                        <div class="card-body">
                            Page titles added<br />
                            Pickup from store option added<br />
                            Changed redirection on category add/edit.<br />
                            Favicon added.<br />
                            Added get quota call to FB control panel.<br />
                            Warning null notification fixed<br />

                            {literal}
                                <pre>
                                    <￣｀ヽ　　　　　　　／￣＞this is the Lenny
                                    　ゝ、　＼　／<>ヽ,ノ　/´owl they are almost
                                    　　ゝ、　`( ( ͡° ͜ʖ ͡°)　／extinct.
                                    　　　　　>　　,ノ
                                    　　　　　∠_,/
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0130">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0130" aria-expanded="false" aria-controls="collapse0130">
                                Version 0.13.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0130" class="collapse" aria-labelledby="heading0130" data-parent="#accordion">
                        <div class="card-body">
                            Shipment/reservation warning button added<br />
                            Shortcut to download invoice as PDF added.<br />
                            Invoice generator update (now built from PDF rather than from HTML).<br />
                            Warning notifications added.<br />
                            Fixed reservation creation call.<br />
                            {literal}
                                <pre>
                                    I hate Facebook! (╯°□°)╯︵ ʞooqǝɔɐℲ
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0120">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0120" aria-expanded="false" aria-controls="collapse0120">
                                Version 0.12.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0120" class="collapse" aria-labelledby="heading0120" data-parent="#accordion">
                        <div class="card-body">
                            Get linked categories function added<br/>
                            Reservation products are now sorted by locations<br/>
                            Product duplicate function now duplicates carrier data<br />
                            Function to get product list from supplied array of their ids added.<br />
                            XML generator added.<br />
                            FB auctions control panel extended<br />
                            Fixed invoice default date<br />
                            Shipping redirect added.<br />
                            {literal}
                                <pre>
                                    Throwing the nice guy who put the table back (╯°Д°）╯︵/(.□ . \)
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0111">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0111" aria-expanded="false" aria-controls="collapse0111">
                                Version 0.11.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0111" class="collapse" aria-labelledby="heading0111" data-parent="#accordion">
                        <div class="card-body">
                            Cart hotfix<br/>
                            Reservation editor fix<br/>

                            {literal}
                                <pre>
                                    ┻┳|
                                    ┳┻| _
                                    ┻┳| •.•)
                                    ┳┻|⊂ﾉ
                                    ┻┳|
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading0110">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0110" aria-expanded="false" aria-controls="collapse0110">
                                Version 0.11.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0110" class="collapse" aria-labelledby="heading0110" data-parent="#accordion">
                        <div class="card-body">
                            FB descriptions added<br/>
                            Added time shift for FB auctions<br/>
                            Search extended to look for approximate id or comment<br/>
                            Reservation editor implemented.<br/>
                            Added list functionality to FB control panel.<br/>
                            Fixed reservations list<br/>
                            Location call fixed<br/>
                            Buffertoode fix.<br />
                            Fixed buffertoode appearance on shipment/reservation submit while it did not exist in cart.
                            {literal}
                                <pre>
ฅ^•ﻌ•^ฅ
                                </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading0100">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse0100" aria-expanded="false" aria-controls="collapse0100">
                                Version 0.10.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse0100" class="collapse" aria-labelledby="heading0100" data-parent="#accordion">
                        <div class="card-body">
                            Search functions updated.<br/>
                            Pagination added to reservations.<br/>
                            Fixed smartpost save function call.
                            {literal}
                                <pre>
ヽ(。_°)ノ    </pre>
                            {/literal}
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="heading090">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse090" aria-expanded="false" aria-controls="collapse090">
                                Version 0.9.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse090" class="collapse" aria-labelledby="heading090" data-parent="#accordion">
                        <div class="card-body">
                            Reservations merge tool implemented.<br/>
                            Shortcuts added to header.<br/>
                            Added highlight on more that 1 pcs in shipping list and fixed layout.<br/>
                            Added redirection on reservation -> shipping conversion.<br/>
                            Functions to process files and other carriers added.<br/>
                            Fixed redirection on item cancellation on shipment page.<br/>
                            {literal}
                            <pre>
{\__/}
( • . •)u want this toilet paper
/>🧻
{\__/}
( • - •) noo!! it's mine
🧻<\
                            </pre>
                            {/literal}
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading081">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse081" aria-expanded="false" aria-controls="collapse081">
                                Version 0.8.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse081" class="collapse" aria-labelledby="heading081" data-parent="#accordion">
                        <div class="card-body">
                            Added cart reload if empty locations.<br/>
                            Location data added to reservations and shipping.<br/>
                            Fixed margin in POS.<br/>
                            Fixed shipping invalid filtration algorithm.<br/>
                            Fixed shipping invalid button disabled state.<br/>
                            Fixed shipping invalid request handler.<br/>
                            <pre>ᕙ(⇀‸↼‶)ᕗ #VERYangry *Grrrr*</pre>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading080">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse080" aria-expanded="false" aria-controls="collapse080">
                                Version 0.8.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse080" class="collapse" aria-labelledby="heading080" data-parent="#accordion">
                        <div class="card-body">
                            Shipping type implemented.<br/>
                            Reservation -> Shipping conversation function added.<br/>
                            POS buttons changed to icons as there are too many of them now<br/>
                            Added focus on input on click in POS.<br/>
                            Some libraries saved as local<br/>
                            FB auctions controller added<br/>
                            Privacy statement added.<br/>
                            Product name output added to auctions statistics.<br/>
                            Fixed error of system trying to load config on not authorised user.<br/>
                            <pre>ᕙ(⇀‸↼‶)ᕗ #angry</pre>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading073">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse073" aria-expanded="false" aria-controls="collapse073">
                                Version 0.7.3
                            </button>
                        </h2>
                    </div>
                    <div id="collapse073" class="collapse" aria-labelledby="heading073" data-parent="#accordion">
                        <div class="card-body">
                            POS products are now sorted by date they are added to the cart.<br/>
                            Added Facebook auctions controls. (Currently for testing purposes)<br/>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading072">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse072" aria-expanded="false" aria-controls="collapse072">
                                Version 0.7.2
                            </button>
                        </h2>
                    </div>
                    <div id="collapse072" class="collapse" aria-labelledby="heading072" data-parent="#accordion">
                        <div class="card-body">
                            Moved chart function to separate file to allow access from other pages.<br/>
                            Fixed awkward image scaling<br/>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading071">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse071" aria-expanded="false" aria-controls="collapse071">
                                Version 0.7.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse071" class="collapse" aria-labelledby="heading071" data-parent="#accordion">
                        <div class="card-body">
                            Fixed POS search<br/>
                            Fixed carrier form on item creation<br/>
                            New styles added (can be switched between in the sidebar)<br/>
                            Added auction statistics<br/>
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="heading070">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse070" aria-expanded="false" aria-controls="collapse070">
                                Version 0.7.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse070" class="collapse" aria-labelledby="heading070" data-parent="#accordion">
                        <div class="card-body">
                            Full redesign<br/>
                            Mobile version implemented<br/>
                            Chat implemented<br/>
                            #cats
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading060">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse060" aria-expanded="false" aria-controls="collapse060">
                                Version 0.6.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse060" class="collapse" aria-labelledby="heading060" data-parent="#accordion">
                        <div class="card-body">
                            Auction charts added<br />
                            Image uploader reworked (fixed effects on drag and drop)<br />
                            Disabled warning message on no descriptions file.<br />
                            Timestamp 'GET' tags added to styles and scripts calls to enable force reload when needed.<br />
                            Large barcode generator added<br />
                            Image uploader optimised for mobile devices<br />
                            Tacos?!
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading051">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse051" aria-expanded="false" aria-controls="collapse051">
                                Version 0.5.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse051" class="collapse" aria-labelledby="heading051" data-parent="#accordion">
                        <div class="card-body">
                            Image zoom reworked.<br />
                            Collapse autoscroll disabled.<br />
                            Image download button fixed.<br />
                            Default location type is now auto selected on new location creation form.<br />
                            Osta XML generator now creates destination file only once its load finished.<br />
                            Image uploader is now not bound to prefix name (any can be used from now on).<br />
                            Image uploader css fixed.<br />
                            Image uploader updated and fixed base encoding check function.
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading050">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse050" aria-expanded="false" aria-controls="collapse050">
                                Version 0.5.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse050" class="collapse" aria-labelledby="heading050" data-parent="#accordion">
                        <div class="card-body">
                            Fixed error when trying to add product without location resulted in array error.<br />
                            Export bulk link function added<br />
                            Image uploader is now drag and drop<br />
                            Live images handling added<br />
                            Big hrusha update! Check it out (･ั(00)･ั)
                        </div>
                    </div>
                </div>





                <div class="card">
                    <div class="card-header" id="heading040">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse040" aria-expanded="false" aria-controls="collapse040">
                                Version 0.4.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse040" class="collapse" aria-labelledby="heading040" data-parent="#accordion">
                        <div class="card-body">
                            Sales platform filter added<br />
                            Added image size reduction function (decreases image quality until its size is less or equal to 1MB)<br />
                            Images are now displayed as carousel in product table and view pages<br />
                            Live images handling added<br />
                            Image uploader library now works using prefix to allow multiple uploaders on single page.

                            <hr>
                            Fixed:<br />
                            Product image duplication function fixed<br />
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="heading031">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse031" aria-expanded="false" aria-controls="collapse031">
                                Version 0.3.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse031" class="collapse" aria-labelledby="heading031" data-parent="#accordion">
                        <div class="card-body">
                            Product duplication functionality<br />
                            Export indicators added<br />
                            <hr>
                            Fixed:<br />
                            Reservation cancellation quantity operation fix<br />
                            Quantity fix on product cancel<br />
                            POS cart reservation bug fixed<br />
                            Edited Osta.ee sync (created cron job to generate XML, new link is to download only now)<br />
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading030">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse030" aria-expanded="false" aria-controls="collapse030">
                                Version 0.3.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse030" class="collapse" aria-labelledby="heading030" data-parent="#accordion">
                        <div class="card-body">
                            EAN addition modal added to product view page.<br />
                            Product images bulk download button added<br />
                            Location deletion added to product scanner<br />
                            Reservation quantity lists added<br />
                            Product sale list added<br />
                            Requests logging implemented<br />
                            Reservation and sale invoices<br />
                            Load reservation into cart functionality<br />
                            Buffer products now supported in POS reservations<br />
                            Location deletion function<br />
                            Reservation list is now sorted by date<br />
                            Cosmetic changes <br />
                            <hr>
                            Fixed:<br />
                            Category tree output for OkiDoki<br />
                            Location duplication in product scanner<br />
                            Reservations load with multiple buffers fixed<br />
                            POS quantity check fixed<br />
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading020">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse020" aria-expanded="false" aria-controls="collapse020">
                                Version 0.2.0
                            </button>
                        </h2>
                    </div>
                    <div id="collapse020" class="collapse" aria-labelledby="heading020" data-parent="#accordion">
                        <div class="card-body">
                            Product quantities are now linked to their warehouse location. All quantities computation
                            functions reworked. ( ╥﹏╥) ノシ<br />
                            Location types added.<br />
                            Cosmetic changes
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading018">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapse018" aria-expanded="false" aria-controls="collapse018">
                                Version 0.1.8
                            </button>
                        </h2>
                    </div>
                    <div id="collapse018" class="collapse" aria-labelledby="heading018" data-parent="#accordion">
                        <div class="card-body">
                            Extended API (Reserve cart, reservation removal, reservation confirm and sale submitting functionality)<br />
                            Minor bug fix in POS<br />
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading017">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse017" aria-expanded="false" aria-controls="collapse017">
                                Version 0.1.7
                            </button>
                        </h2>
                    </div>
                    <div id="collapse017" class="collapse" aria-labelledby="heading017" data-parent="#accordion">
                        <div class="card-body">
                            Implemented cart reservations. New functionality allows to reserve cart in POS so that
                            selected amount of products becomes unavailable for purchase.<br />
                            Tree list page added<br />
                            <hr>
                            Hotfix<br />
                            Fixed include errors
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="heading016">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse016" aria-expanded="false" aria-controls="collapse016">
                                Version 0.1.6
                            </button>
                        </h2>
                    </div>
                    <div id="collapse016" class="collapse" aria-labelledby="heading016" data-parent="#accordion">
                        <div class="card-body">
                            Blocked duplicate script calls.<br />
                            New image uploader implemented.<br />
                            Tags now appear in Scanner and on POS page.
                            <hr>
                            Hotfix<br />
                            Fixed links in POS
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading015">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse015" aria-expanded="false" aria-controls="collapse015">
                                Version 0.1.5
                            </button>
                        </h2>
                    </div>
                    <div id="collapse015" class="collapse" aria-labelledby="heading015" data-parent="#accordion">
                        <div class="card-body">
                            Bootstrap JS library updated.<br/>
                            For the sake of simplicity SupplierManageTool has been renamed into WMS.<br/>
                            Fixed bug on Bootstrap collapse occasionally not opening.<br/>
                            Fixed bug on Bootstrap dropdown requiring double click to be opened.<br/>
                            Shards implemented.<br/>
                            All related functions made to work with either selected or default shard.<br/>
                            Patch notifications implemented.
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="heading011">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse011" aria-expanded="false" aria-controls="collapse011">
                                Version 0.1.1
                            </button>
                        </h2>
                    </div>
                    <div id="collapse011" class="collapse" aria-labelledby="heading011" data-parent="#accordion">
                        <div class="card-body">
                            Implemented function to get product data by EAN code.<br />
                            Manual scanners can now search products by EAN code.<br />
                            Summernote library removed as it is not used anymore.
                        </div>
                    </div>
                </div>


            </div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Changelog");
    });
</script>
{include file='footer.tpl'}