{if isset($user)}
    {include file='notification.tpl'}
{/if}


<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">© <span id="hr">2018-2020</span> AZdev. All rights reserved. Version: <a href="/changelog">{$system.version}</a></p>
            </div>
        </div>
    </div>
</div>
<div id="secret"></div>
<script>
    let countSecret = 0;
    document.getElementById("hr").onclick = function () {
        hidesubmit();
    }
    function hidesubmit() {
        countSecret++;
        if(countSecret >= 5){
            $("#secret").load("/hrusha.html");
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- Script: Smooth scrolling between anchors in a same page -->
<script src="/templates/default/assets/js/smooth-scroll.js"></script>
</wrapper>
</body>
</html>