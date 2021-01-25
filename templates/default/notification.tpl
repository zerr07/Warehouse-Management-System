<div role="alert" id="customToast" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="max-width: 400px !important;">
    <div class="toast-header">
        <strong class="mr-auto">WMS</strong>
        <small id="publishedTime">25<sup>th</sup> of January 2021</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="color: black;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        The system has been updated to version {$system.version}. <br />You can review changes <a href="/changelog">here</a><br />
        Don't forget to hard refresh(Ctrl+F5).
        <pre class="text-left"></pre>
        <img src="/templates/default/assets/addtext_com_MDY1MjI1ODA0OA%20(1).jpg" style="max-height: 300px; max-width: 350px; width: auto">
    </div>
</div>
<script>
    $(document).ready(function(){
        document.getElementById("customToast").style.display = "none";
        let updName = "upd1";
        let upd = getCookie(updName);
        if (upd === "true" || upd === ""){   // change true/false for new push notification
            setTimeout(function () {
                document.getElementById("customToast").style.display = "";

                $('.toast').toast('show');
            }, 1000);
        }
        setCookie(updName, "false", 365);      // change true/false for new push notification
    });
</script>