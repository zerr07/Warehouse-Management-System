{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <label for="themeSelector">Theme</label>
                <select class="custom-select" id="themeSelector" onchange="toggleThemeMode(this)">
                    <option value="default" id="defaultTheme">Default</option>
                    <option value="standard" id="standardTheme">Standard (not tested)</option>
                    <option value="dark-mode" id="dark-modeTheme">Dark mode (not tested)</option>
                </select>
            </div>
            <div class="col-12 p-0 mt-3">
                <label for="access_token" class="ml-3">API token</label>
                <div class="row m-auto">

                    <div class="col-9 col-sm-9 col-md-9 col-lg-6">


                        <input type="text" class="form-control" id="access_token" value="{if !is_null($access_token)}{$access_token}{/if}" placeholder="Empty">
                    </div>
                    <div class="col-3 col-lg-2">
                        <button class="btn btn-outline-info w-100" {if is_null($access_token)}disabled{/if} onclick="copyAccessToken()"><i class="far fa-copy"></i></button>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-3 mt-lg-0">
                        <button class="btn btn-secondary w-100" type="button" onclick="generateAccessToken()">
                            <i class="fas fa-id-card"></i> Generate new access token
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 mt-3">
                <a class="btn btn-secondary w-100" href="/cp/profile/settings"><i class="fas fa-cogs"></i> Settings</a>

            </div>
            <div class="col-12 col-sm-12 col-md-6 mt-3">
                <a class="btn btn-secondary w-100" href="/cp/profile/change_password"><i class="fas fa-key"></i> Change password</a>

            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Profile");
        if (getCookie("user_id") !== ""){
            if (getCookie("darkTheme") === "true"){
                document.getElementById("dark-modeTheme").selected = true;
            } else if (getCookie("standardTheme") === "true"){
                document.getElementById("standardTheme").selected = true;
            } else if (getCookie("defaultTheme") === "true"){
                document.getElementById("defaultTheme").selected = true;
            }
        }
    });
    function generateAccessToken(){
        fetch("/controllers/generateAccessToken.php").then(response=>response.json()).then((d)=>{
            if (d.hasOwnProperty('token')){
                setCookie("access_token", d.token)
                document.getElementById("access_token").value = d.token;
            } else if (d.hasOwnProperty("error")){
                alert("Error has occurred while generating access token.");
                console.log(d.error);
            } else {
                alert("Unknown error.");
            }

        });
    }
    function copyAccessToken(){
        var copyTextarea = document.querySelector('#access_token');
        copyTextarea.focus();
        copyTextarea.select();
        try {
            let successful = document.execCommand('copy');
            let msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }
    }

</script>
{include file='footer.tpl'}
