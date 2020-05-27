{include file='header.tpl'}
<main class="d-flex flex-column">
    <div class="py-3 fullHeight">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <table class="table-responsive-sm w-100">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $shards as $key => $shard}
                            <tr>

                            <td>{$key}</td>

                            <td>{$shard}</td>

                            <td><button type="button" class="btn btn-primary btn-sm" onclick="deleteShard('{$key}')">

                                    Delete

                                </button></td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addShardModal">
                        Add new shard
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function getAddress(id) {
        document.getElementById("id_shard").value = "";
        document.getElementById("name").value =  "";
        let shard = $.ajax({
            dataType: "text",
            async: false,
            url: "/controllers/shards.php?getSingleShardAJAX="+id
        });
        shard = JSON.parse(shard.responseText);
        document.getElementById("id_shard").value = shard;
        document.getElementById("name").value = shard['address'];
    }
    function deleteShard(id) {
        let check = confirm("Confirm shard deletion?");
        if (check){
            $.ajax({
                dataType: "text",
                async: false,
                url: "/controllers/shards.php?deleteShard="+id,
                success  : function(data) {
                    console.log(data);
                    if (data === "Success"){
                        location.reload();
                    }
                }
            });
        }
    }
</script>
<div class="modal fade" id="addShardModal" tabindex="-1" role="dialog" aria-labelledby="addShardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addShardModalLabel">Add new shard</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/controllers/shards.php" method="get" id="addShardForm">
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="createShard">Shard name</label>
                        <input type="text" class="form-control" name="createShard" id="createShard" placeholder="Shard name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#addShardForm').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type     : "GET",
            cache    : false,
            url      : $(this).attr('action'),
            data     : $(this).serialize(),
            success  : function(data) {
                if (data === "Success"){
                    $('#addShardModal').modal('toggle');
                    location.reload();
                }
            }
        });
    });
</script>



{include file='footer.tpl'}
