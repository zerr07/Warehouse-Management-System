{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-5 text-center text-white fullHeight" >
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h1 class="mb-3">Control Panel Login</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form class="" method="POST" action="/controllers/login.php">
                        <div class="form-group">
                            <label class="d-flex">Username</label>
                            <input type="username" class="form-control" name="username" placeholder="Enter username"> <small class="form-text text-body d-flex">We'll never share your username with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label class="d-flex">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    window.addEventListener("load", function (){
        setPageTitle("Login");
    })
</script>
{include file='footer.tpl'}
