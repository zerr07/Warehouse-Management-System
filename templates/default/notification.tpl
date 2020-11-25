<div role="alert" id="customToast" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false" style="max-width: 500px !important;">
    <div class="toast-header">
        <strong class="mr-auto">WMS</strong>
        <small id="publishedTime">25<sup>th</sup> of November 2020</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="color: black;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        The system has been updated to version {$system.version}. <br />You can review changes <a href="/changelog">here</a><br />
        Don't forget to hard refresh(Ctrl+F5).
        {*<pre class="text-left">
       ________________                              _______________
      /                \                            / /           \ \
     / /          \ \   \                          |    -    -       \
     |                  |                          | /        -   \  |
    /                  /                           \                 \
   |      ___\ \| | / /                             \____________  \  \
   |      /           |                             |            \    |
   |      |     __    |                             |             \   \
  /       |       \   |                             |              \  |
  |       |        \  |                             | ====          | |
  |       |       __  |                             | (o-)      _   | |
  |      __\     (_o) |                             /            \  | |
  |     |             |     Heh Heh Heh            /            ) ) | |
   \    ||             \      /      Huh Huh Huh  /             ) / | |
    |   |__             \    /                \  |___            - |  |
    |   |           (*___\  /                  \    *'             |  |
    |   |       _     |    /                    \  |____           |  |
    |   |    //_______|                             ####\          |  |
    |  /       |_|_|_|___/\                        ------          |_/
     \|       \ -         |                        |                |
      |       _----_______/                        \_____           |
      |      /                                          \           |
      |_____/                                            \__________|
</pre>*}
        <img src="/templates/default/assets/do-not-try-to-fix-the-bug-there-is-no-bug.jpg" style="max-height: 300px; width: auto">
    </div>
</div>
<script>
    $(document).ready(function(){
        document.getElementById("customToast").style.display = "none";
        let updName = "upd1";
        let upd = getCookie(updName);
        if (upd === "false" || upd === ""){   // change true/false for new push notification
            setTimeout(function () {
                document.getElementById("customToast").style.display = "";

                $('.toast').toast('show');
            }, 1000);
        }
        setCookie(updName, "true", 365);      // change true/false for new push notification
    });
</script>