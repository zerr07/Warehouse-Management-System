{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <label for="defLoc">Default location</label>

                <select class="custom-select" id="defLoc">
                    {foreach $location_types as $key => $value}
                        <option value="{$key}" {if $default_location_type==$key}selected{/if}>{$value.name}</option>
                    {/foreach}
                </select>
            </div>


        </div>
        <div class="row mt-3">
            <div class="col-6 col-md-3 d-flex justify-content-start">
                <button type="button" onclick="saveSettings()" class="btn btn-success"><i class="far fa-save"></i> Save</button>
            </div>
            <div class="col-6 col-md-3 offset-md-6 d-flex justify-content-end">
                <a class="btn btn-primary" href="/cp/profile"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Profile settings");
    });
    function saveSettings(){
        let defLoc = document.getElementById("defLoc");

        const raw = JSON.stringify(
            {
                "defLoc": defLoc.value
            }
        );
        const requestOptions = {
            method: 'POST',
            headers:  new Headers({
                'Content-Type': 'application/json'
            }),
            body: raw
        };
        fetch("/cp/profile/settings/update.php", requestOptions).then(response => response.json()).then(d => {
            if (d.hasOwnProperty("error")){
                if (d.code === "101"){
                    alert(d.error)
                }
                if (d.code === "106"){
                    alert(d.error)
                }
            } else if(d.hasOwnProperty("success")){
                setCookie("default_location_type", defLoc.value, 30);
                location.reload();
            }
        });
    }
</script>
{include file='footer.tpl'}
