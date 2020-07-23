{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-5 text-center text-white fullHeight" >
        <div class="container">

            <div class="row">
                <div class="col-md-12 text-left">
                    <h3>Bruh there is {$total} worth items in da warehouse.</h3>
                    <small style="opacity: 0.3">Do not click on this page</small>
                    <script>
                        document.body.addEventListener("click", function () {
                            var audio = document.getElementById("audio");
                            audio.volume = 1;
                            audio.play();
                            alert("Fak u");

                        });
                    </script>
                    <audio id="audio" src="/templates/default/bruh.mp3"></audio>
                </div>
            </div>
        </div>
    </div>
</main>

{include file='footer.tpl'}