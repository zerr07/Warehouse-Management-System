{include file='header.tpl'}
<div class="row mt-3">
    <div class="col-12 mt-2">
        Photos uploaded
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" id="SyncProgress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
    </div>
    <div class="col-12">
        <button type="button" class="btn btn-info" id="sync" disabled>Sync all products</button>
    </div>
</div>
<script>
    function setSyncProgress(v){
        document.getElementById("SyncProgress").setAttribute("aria-valuenow", v);
        document.getElementById("SyncProgress").setAttribute("style", "width: "+v+"%");
    }
    window.addEventListener("load", function () {
        fetch("?getProducts")
            .then(response => response.json())
            .then(cp => {
                fetch("?getProductsPR")
                    .then(response => response.json())
                    .then(async pr => {
                        let size;
                        if (Object.keys(cp).length < Object.keys(pr).length){
                            size = Object.keys(pr).length;
                        } else {
                            size = Object.keys(cp).length;
                        }
                        console.log(size);
                        count = 0;
                        setSyncProgress(count);

                        incrementor = 100/size;
                        for (const item in cp){
                            console.log(item);console.log(cp[item]);
                            console.log(pr[item]);
                            if (!pr[item]){
                                count += incrementor;
                                setSyncProgress(count);
                                await fetch("?POSTPROD="+item);
                                console.log("Item to post", item);
                            } else {
                                count += incrementor;
                                setSyncProgress(count);
                                await fetch("?PUTPROD="+item);
                                console.log("Item to put", item);
                            }
                            delete pr[item];
                        }
                        for (const item in pr){
                            count += incrementor;
                            setSyncProgress(count);
                            await fetch("?DELETEPROD="+item);
                            console.log("Item to delete", item);
                        }
                        console.log(pr);
                        document.getElementById("sync").disabled = false;
                    });
            });
    });
</script>
{include file='footer.tpl'}
