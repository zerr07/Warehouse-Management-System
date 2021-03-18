{include file='header.tpl'}
<div class="row mt-3">
    <div class="col-md-12">
        <h1>Create new category</h1>
        <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6  col-lg-4">
                    <label for="catNameET">Kategooria nimi<span style="color: red;">*</span> </label>
                    <input type="text" class="form-control" name="catNameET" id="catNameET" placeholder="Kategooria nimi" required="required">
                </div>
                <div class="col-12 col-sm-12 col-md-6  col-lg-4">
                    <label for="catNameRU">Название категории<span style="color: red;">*</span> </label>
                    <input type="text" class="form-control" name="catNameRU" id="catNameRU" placeholder="Название категории" required="required">
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                    <label for="catNameRU">Category name<span style="color: red;">*</span> </label>
                    <input type="text" class="form-control" name="catNameEN" id="catNameEN" placeholder="Category name EN" required="required">
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <label for="catNameLV">Category name LV</label>
                    <input type="text" class="form-control" name="catNameLV" id="catNameLV" placeholder="Category name LV">
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                    <label for="catNameLT">Category name LT</label>
                    <input type="text" class="form-control" name="catNameLT" id="catNameLT" placeholder="Category name LT">
                </div>
            </div>
            <label>Parent category</label><br>
            <div style="margin-left: 20px;">
                {include file='cp/WMS/category/tree/radio/tree.tpl'}
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a class="btn btn-primary" href="/cp/WMS/category/"><i class="fas fa-undo-alt"></i> Back</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Create category");
    });
</script>
{include file='footer.tpl'}