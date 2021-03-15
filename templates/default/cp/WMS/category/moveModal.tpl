<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveModalTitle">Move category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="moveModalBody">
                <div class="form-group">
                    <label for="fromCategory">From (id)</label>
                    <input type="text" class="form-control" id="fromCategory">
                    <div class="" id="fromCategoryFeedback"></div>
                </div>
                <div class="form-group">
                    <label for="toCategory">To (id)</label>
                    <input type="text" class="form-control" id="toCategory">
                    <div class="" id="toCategoryFeedback"></div>
                </div>
                <button type="button" onclick="MoveCategories()" class="btn btn-primary">Submit</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function MoveCategories(){
        let to = document.getElementById("toCategory");
        let toFeedback = document.getElementById("toCategoryFeedback");

        let from = document.getElementById("fromCategory");
        let fromFeedback = document.getElementById("fromCategoryFeedback")

        to.setAttribute("class", "form-control");
        toFeedback.setAttribute("class", "");
        toFeedback.innerText = "";

        from.setAttribute("class", "form-control");
        fromFeedback.setAttribute("class", "");
        fromFeedback.innerText = "";

        if (from.value === ""){
            from.setAttribute("class", "form-control is-invalid");
            fromFeedback.setAttribute("class", "invalid-feedback");
            fromFeedback.innerText = "Field empty.";
            return ;
        }

        if (to.value === ""){
            to.setAttribute("class", "form-control is-invalid");
            toFeedback.setAttribute("class", "invalid-feedback");
            toFeedback.innerText = "Field empty.";
            return ;
        }

        fetch("/controllers/categories/moveProducts.php?from="+from.value+"&to="+to.value).then(response => response.json())
        .then(d => {
            if (d.hasOwnProperty("error")){
                displayAlert(d.error, 5000, 'error')
            } else if (d.hasOwnProperty("success")){
                displayAlert("Success", 5000, 'success')
            } else {
                displayAlert("No response received.", 5000, 'error')
            }
        })
    }
</script>