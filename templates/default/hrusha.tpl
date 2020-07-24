
<div class="modal fade" id="hrushaModal" tabindex="-1" role="dialog" aria-labelledby="hrushaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hrushaModalLabel">Вас охрюшили</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="hr-iframe" src="https://www.youtube.com/embed/XRfgS6LIMdU?controls=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let countHrusha = 0;
    document.getElementById("hr").onclick = function () {
        hidesubmit();
    }
    function hidesubmit() {
        countHrusha++;
        if(countHrusha >= 5){
            $("#hrushaModal").modal("toggle");
        }
    }
</script>