<div role="alert" id="customToast" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="max-width: 450px !important;">
    <div class="toast-header">
        <strong class="mr-auto">WMS</strong>
        <small id="publishedTime">2<sup>nd</sup> of October 2020</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="color: black;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        The system has been updated to version {$system.version}. <br />You can review changes <a href="/changelog">here</a><br />
        Don't forget to hard refresh(Ctrl+F5).
        <pre class="text-left">
            ░▄▀▄▀▀▀▀▄▀▄░░░░░░░░░
            ░█░░░░░░░░▀▄░░░░░░▄░
            █░░▀░░▀░░░░░▀▄▄░░█░█
            █░▄░█▀░▄░░░░░░░▀▀░░█
            █░░▀▀▀▀░░░░░░░░░░░░█
            █░░░░░░░░░░░░░░░░░░█
            █░░░░░░░░░░░░░░░░░░█
            ░█░░▄▄░░▄▄▄▄░░▄▄░░█░
            ░█░▄▀█░▄▀░░█░▄▀█░▄▀░
            ░░▀░░░▀░░░░░▀░░░▀░░░
</pre>
    </div>
</div>
<script>
    $(document).ready(function(){
        let updName = "upd1";
        let upd = getCookie(updName);
        if (upd === "false" || upd === ""){   // change true/false for new push notification
            setTimeout(function () {
                $('.toast').toast('show');
            }, 1000);
        }
        setCookie(updName, "true", 365);      // change true/false for new push notification
    });
</script>