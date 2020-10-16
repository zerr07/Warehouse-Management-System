{include file='header.tpl'}
<link rel="stylesheet" href="/templates/default/assets/css/editor.css?t=16102020T165419">


            <div class="row mt-3">
                <div class="col-md-12">
                    <h1>Edit description for {$platform_name}</h1>
                    <form class="text-left" method="POST" action="upload.php">
                        <input type="text" name="id" value="{$platform_id}" hidden>
                        <div style="padding-top: 20px;">
                            <p>Use <code><-TAG-></code> for product tag output</p>
                            <ul class="nav nav-tabs">
                                <li class="nav-item"> <a href="" class="nav-link active show" data-toggle="tab" data-target="#tabRUS">RUS</a> </li>
                                <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabEST">EST</a> </li>
                                <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabPL">PL</a> </li>
                                <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabENG">ENG</a> </li>
                                <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabLV">LV</a> </li>
                            </ul>
                            <div class="tab-content mt-2 mb-2">
                                <div class="tab-pane fade active show" id="tabRUS" role="tabpanel">
                                    <textarea name="RUS" id="ruText">{$platforms.ru}</textarea>
                                </div>
                                <div class="tab-pane fade" id="tabEST" role="tabpanel">
                                    <textarea name="EST" id="etText">{$platforms.et}</textarea>
                                </div>
                                <div class="tab-pane fade" id="tabPL" role="tabpanel">
                                    <textarea name="PL" id="plText">{$platforms.pl}</textarea>
                                </div>
                                <div class="tab-pane fade" id="tabENG" role="tabpanel">
                                    <textarea name="ENG" id="enText">{$platforms.en}</textarea>
                                </div>
                                <div class="tab-pane fade" id="tabLV" role="tabpanel">
                                    <textarea name="LV" id="lvText"{$platforms.lv}></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        <a class="btn btn-primary" style="display: inline-block; float:right;" href="/cp/WMS/platforms"><i class="fas fa-undo-alt"></i> Back</a>
                    </form>
                </div>
            </div>

<script src="/templates/default/assets/js/editor.js?t=16102020T165416"></script>

<script>

    $(window).on('load', function(){
        loadEditor('lvText', 'lv');
        loadEditor('plText', 'pl');
        loadEditor('ruText', 'ru');
        loadEditor('etText', 'et');
        loadEditor('enText', 'en');
    });

</script>
{include file='footer.tpl'}