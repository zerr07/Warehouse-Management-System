{include file='header.tpl'}

<div class="row mt-3">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <label for="oldPassword">Old password</label>
                <input type="password" class="form-control" id="oldPassword" name="oldPassword" maxlength="100" placeholder="Old password">
                <div class="" id="oldPasswordFeedback"></div>
            </div>
            <div class="col-12">
                <label for="newPassword">New password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" maxlength="100" minlength="8" placeholder="New password">
                <div class="" id="newPasswordFeedback"></div>
            </div>
            <div class="col-12">
                <label for="confNewPassword">Confirm new password</label>
                <input type="password" class="form-control" id="confNewPassword" name="confNewPassword" maxlength="100" minlength="8" placeholder="Confirm new password">
                <div class="" id="confNewPasswordFeedback"></div>
            </div>



        </div>
        <div class="row mt-3">
            <div class="col-6 col-md-3 d-flex justify-content-start">
                <button type="button" onclick="changePassword()" class="btn btn-success"><i class="far fa-save"></i> Save</button>
            </div>
            <div class="col-6 col-md-3 offset-md-6 d-flex justify-content-end">
                <a class="btn btn-primary" href="/cp/profile"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        setPageTitle("Change password");
    });
    function changePassword(){
        let confNewPassword = document.getElementById("confNewPassword");
        let oldPasswordFeedback = document.getElementById("oldPasswordFeedback");
        let newPasswordFeedback = document.getElementById("newPasswordFeedback");
        let confNewPasswordFeedback = document.getElementById("confNewPasswordFeedback");
        oldPassword.setAttribute("class", "form-control");
        oldPasswordFeedback.setAttribute("class", "");
        oldPasswordFeedback.innerText = "";
        newPassword.setAttribute("class", "form-control");
        newPasswordFeedback.setAttribute("class", "");
        newPasswordFeedback.innerText = "";
        confNewPassword.setAttribute("class", "form-control");
        confNewPasswordFeedback.setAttribute("class", "");
        confNewPasswordFeedback.innerText = "";


        if (oldPassword === newPassword.value){
            newPassword.setAttribute("class", "form-control is-invalid");
            newPasswordFeedback.setAttribute("class", "invalid-feedback");
            newPasswordFeedback.innerText = "Old password is equal to the new one";
        }
        if (confNewPassword.value === newPassword.value){
            const raw = JSON.stringify(
                {
                    "oldPassword": oldPassword.value,
                    "newPassword": newPassword.value,
                    "confNewPassword": confNewPassword.value
                }
            );
            const requestOptions = {
                method: 'POST',
                headers:  new Headers({
                    'Content-Type': 'application/json'
                }),
                body: raw
            };
            fetch("/cp/profile/change_password/update.php", requestOptions).then(response => response.json()).then(d => {
                if (d.hasOwnProperty("error")){
                    if (d.code === "101"){
                        alert(d.error)
                    }
                    if (d.code === "103"){
                        oldPassword.setAttribute("class", "form-control is-invalid");
                        oldPasswordFeedback.setAttribute("class", "invalid-feedback");
                        oldPasswordFeedback.innerText = d.error;
                    }
                    if (d.code === "104"){
                        newPassword.setAttribute("class", "form-control is-invalid");
                        newPasswordFeedback.setAttribute("class", "invalid-feedback");
                        newPasswordFeedback.innerText = d.error;
                    }
                    if (d.code === "105"){
                        confNewPassword.setAttribute("class", "form-control is-invalid");
                        confNewPasswordFeedback.setAttribute("class", "invalid-feedback");
                        confNewPasswordFeedback.innerText = d.error;
                    }
                    if (d.code === "107"){
                        newPassword.setAttribute("class", "form-control is-invalid");
                        newPasswordFeedback.setAttribute("class", "invalid-feedback");
                        newPasswordFeedback.innerText = d.error;
                    }
                    if (d.code === "109"){
                        oldPassword.setAttribute("class", "form-control is-invalid");
                        oldPasswordFeedback.setAttribute("class", "invalid-feedback");
                        oldPasswordFeedback.innerText = d.error;
                    }
                    if (d.code === "110"){
                        newPassword.setAttribute("class", "form-control is-invalid");
                        newPasswordFeedback.setAttribute("class", "invalid-feedback");
                        newPasswordFeedback.innerText = d.error;
                    }
                } else if(d.hasOwnProperty("success")){
                    window.location.href = "/cp/profile/";

                }
            });
        } else {
            newPassword.setAttribute("class", "form-control is-invalid");
            newPasswordFeedback.setAttribute("class", "invalid-feedback");
            newPasswordFeedback.innerText = "New password doesn't match.";
        }
    }
</script>
{include file='footer.tpl'}
