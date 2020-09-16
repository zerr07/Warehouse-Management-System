{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="accordion text-left" id="accordion">


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
        </div>
    </div>
</main>
{include file='footer.tpl'}