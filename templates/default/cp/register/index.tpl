{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-5 text-center text-white fullHeight" >
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h1 class="mb-3">Register new user</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="" method="POST" action="/controllers/register.php">
                        <div class="form-group">
                            <label class="d-flex">Username</label>
                            <input type="username" class="form-control" name="usernameReg" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label class="d-flex">Password</label>
                            <input type="password" class="form-control" id="passwordReg" name="passwordReg" placeholder="Password" onchange="checkPasswordMatch()" required>
                        </div>
                        <div class="form-group">
                            <label class="d-flex">Confirm password</label>
                            <input type="password" class="form-control" id="passwordConfirmReg" name="passwordConfirmReg" placeholder="Confirm password" onchange="checkPasswordMatch()" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="submitReg" disabled>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function checkPasswordMatch(){
        var pass = $("#passwordReg").val();
        var passConf = $("#passwordConfirmReg").val();
        if (pass == passConf){
            $("#submitReg").attr("disabled", false);
        } else {
            $("#submitReg").attr("disabled", true);
        }
    }

</script>
{include file='footer.tpl'}