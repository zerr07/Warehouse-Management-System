{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="accordion text-left" id="accordion">

                <div class="card">
                    <div class="card-header" id="heading0.1.5">
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