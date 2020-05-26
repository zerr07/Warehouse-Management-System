{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="border-radius: 20px;border: solid 1px; padding: 10px;">
                    <h1>Create new category</h1>
                    <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
                        <label for="form17">Item name<span style="color: red;">*</span> </label>
                        <input type="text" class="form-control" name="catNameET" id="form17" placeholder="Kategooria nimi" required="required"><br />
                        <input type="text" class="form-control" name="catNameRU" id="form17" placeholder="Название категории" required="required"><br />
                        <label>Parent category</label><br>
                        <div style="margin-left: 20px;">
                            {include file='cp/SupplierManageTool/category/tree.tpl'}
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/SupplierManageTool/category/"><i class="fas fa-undo-alt"></i> Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>

</script>
{include file='footer.tpl'}