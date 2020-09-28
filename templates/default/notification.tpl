<div role="alert" id="customToast" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="max-width: 450px !important;">
    <div class="toast-header">
        <strong class="mr-auto">WMS</strong>
        <small id="publishedTime">28<sup>th</sup> of September 2020</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="color: black;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        The system has been updated to version {$system.version}. <br />You can review changes <a href="/changelog">here</a><br />
        Don't forget to hard refresh(Ctrl+F5).
        <pre class="text-left">──────▄▌▐▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀&#8203;▀▀▀▀▀▀▌ <br>───▄▄██▌█ BEEP BEEP <br>▄▄▄▌▐██▌█ GAY PORN DELIVERY <br>███████▌█▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄&#8203;▄▄▄▄▄▄▌ <br>▀(@)▀▀▀▀▀▀▀(@)(@)▀▀▀▀▀▀▀▀▀▀▀▀▀&#8203;▀▀▀▀(@)▀
</pre>
    </div>
</div>
<script>
    $(document).ready(function(){
        let updName = "upd1";
        let upd = getCookie(updName);
        if (upd === "true" || upd === ""){   // change true/false for new push notification
            setTimeout(function () {
                $('.toast').toast('show');
            }, 1000);
        }
        setCookie(updName, "false", 365);      // change true/false for new push notification
    });
</script>