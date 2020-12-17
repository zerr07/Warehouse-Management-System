{include file='header.tpl'}



<div class="row mt-3">
    <div class="col-md-12">
        <h1>Edit category {$item.id}</h1>
        <form class="text-left" method="POST" action="upload.php" enctype="multipart/form-data">
            <input type="text" name="idEdit" value="{$item.id}" hidden>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6">
                    <label for="catNameET">Kategooria nimi<span style="color: red;">*</span> </label>
                    <input type="text" class="form-control" name="catNameET" id="catNameET" value="{$item.name.et}"
                           placeholder="Kategooria nimi" required="required">
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <label for="catNameRU">Название категории<span style="color: red;">*</span> </label>
                    <input type="text" class="form-control" name="catNameRU" id="catNameRU" value="{$item.name.ru}"
                           placeholder="Название категории" required="required">
                </div>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="customSwitch1" name="enabled" value="Yes" {if $item.enabled}checked{/if}>
                <label class="custom-control-label" for="customSwitch1">Enabled</label>
            </div>
            <label>Parent category</label><br>
            <div style="margin-left: 20px;">
                {include file='cp/WMS/category/treeEdit.tpl'}
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
        setPageTitle("Edit category {$item.id}");
    })
</script>
{include file='footer.tpl'}