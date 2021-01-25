{if isset($user)}
    {include file='notification.tpl'}
{/if}

<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>


</div>
</div>
</div>
</div>


</main>
<div class="py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0">© <span id="hr">2018-2021</span> AZdev. All rights reserved. Version:
                    <a data-toggle="tooltip" data-placement="top" data-html="true" title="<pre class='text-left'>{literal}/\_/\
(='_' )
(, (') ('){/literal}</pre>"
                       href="/changelog">{$system.version}</a> /
                    <a href="/privacy">ToS</a>
                </p>
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

<script>
    $(window).on('load', function () {
        turnOffPreloader();
    });
</script>

<script src="/templates/default/assets/js/fontawesome-all.js?t=16102020T165606"></script>

<script src="/templates/default/assets/js/popper.js?t=16102020T165603"></script>
<script src="/templates/default/assets/js/bootstrap.min.js?t=16102020T165600"></script>


<script src="/templates/default/assets/js/smooth-scroll.js?t=16102020T165557"></script>
<script src="/templates/default/assets/js/sidebar.js?t=20201209T124521"></script>

<script type="text/javascript">
    $(document).ready(function() {
        console.log("Time until DOMready: ", Date.now()-timerStart);
    });
    $(window).on('load', function(){
        console.log("Time until everything loaded: ", Date.now()-timerStart);
    });
</script>
</body>
</html>