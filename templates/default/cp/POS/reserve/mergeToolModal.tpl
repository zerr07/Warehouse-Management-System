<div class="modal fade text-left" id="mergeToolModal" tabindex="-1" role="dialog" aria-labelledby="mergeToolModalLabel" aria-hidden="true" style="color: black">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeToolModalLabel">Merge reservations</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mergeToolModalBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="addExtraMergeTool">Add extra</button>
                <button type="button" class="btn btn-success" id="submitMergeTool">Merge</button>
            </div>
        </div>
    </div>
</div>
<datalist id="reservations">
    {foreach $reservedList as $item}
        <option value="{$item.id} | {$item.comment}" data-id="{$item.id}">{$item.id} | {$item.comment}</option>
    {/foreach}
</datalist>

<script>
    window.addEventListener("load", function (){
        addNewFieldMergeTool();
        addNewFieldMergeTool();
        applyListenersMergeTool();
    });
    let c = 1;
    function addNewFieldMergeTool(){
        let row = document.createElement("div");
        row.setAttribute("class", "row");
        row.setAttribute("id", "mergeToolInputDiv"+c)
        let div1 = document.createElement("div");
        div1.setAttribute("class", "col-10");
        let div2 = document.createElement("div");
        div2.setAttribute("class", "col-2 d-block mx-auto my-auto");
        let del = document.createElement("button");
        del.setAttribute("type", "button");
        del.setAttribute("id", "mergeToolDelete"+c)
        del.setAttribute("class", "btn btn-link");
        del.setAttribute("style", "color: #cd6464");
        del.innerHTML = "<i class='fas fa-trash'></i>"
        let el = document.createElement("input");
        el.setAttribute("type", "text");
        el.setAttribute("class", "form-control");
        el.setAttribute("name", "mergeToolItem[]");
        el.setAttribute("list", "reservations");
        el.setAttribute("id", "mergeToolInput"+c)
        let el_feedback = document.createElement("div");
        el_feedback.setAttribute("id", "mergeToolInput"+c+"Feedback");
        div1.appendChild(el);
        div1.appendChild(el_feedback);
        div2.appendChild(del);
        row.appendChild(div1);
        row.appendChild(div2);
        document.getElementById("mergeToolModalBody").appendChild(row);
        applyListenersMergeTool();
        c += 1;
    }

    document.getElementById("addExtraMergeTool").addEventListener("click", function () {
        addNewFieldMergeTool();
    });

    function checkMergeToolFields(){
        document.querySelectorAll("input[type='text'][id^='mergeToolInput']").forEach(el => {
           el.setAttribute("class", "form-control");
        });
        document.querySelectorAll("div[id^='mergeToolInput'][id$='Feedback']").forEach(el => {
            el.setAttribute("class", "");
            el.innerText = "";
        });
        let errors = false;
        document.querySelectorAll("input[type='text'][id^='mergeToolInput']").forEach(el => {
            try {
                let val = document.querySelector("datalist > option[value='"+el.value+"']").getAttribute("data-id");
                el.setAttribute("class", "form-control  is-valid");
            } catch (err) {
                console.log("SETTING FALSE");
                el.setAttribute("class", "form-control mt-2 is-invalid");
                document.getElementById(el.id + "Feedback").setAttribute("class", "invalid-feedback");
                document.getElementById(el.id + "Feedback").innerText = "Error finding this reservation check input!";
                errors = true;
            }
        });
        return !errors;
    }

    document.getElementById("submitMergeTool").addEventListener("click", function () {
        console.log(checkMergeToolFields())
        if (checkMergeToolFields() !== false) {
            let arr = [];
            document.querySelectorAll("input[type='text'][id^='mergeToolInput']").forEach(el => {
                arr.push(document.querySelector("datalist > option[value='"+el.value+"']").getAttribute("data-id"))
            });
            console.log("/cp/POS/reserve/merge.php?mergeList="+JSON.stringify(arr));
            fetch("/cp/POS/reserve/merge.php?mergeList="+JSON.stringify(arr))
            .finally(function () {
                location.reload();
            });
        }
    });

    function applyListenersMergeTool(){
        document.querySelectorAll("input[name='mergeToolItem[]'][list='reservations']").forEach(el => {
            el.addEventListener("input", function () {
                document.querySelectorAll("datalist#reservations > option").forEach(opt => {
                    opt.disabled = false;
                });
                document.querySelectorAll("input[name='mergeToolItem[]'][list='reservations']").forEach(child => {
                    let element = document.querySelector("datalist#reservations > option[value='"+child.value+"']");
                    if (element){
                        if (child.value !== ""){
                            element.disabled = true;
                        }
                    }

                });
            })
        });
        document.querySelectorAll("button[id^='mergeToolDelete'][type='button']").forEach(el => {
            el.addEventListener("click", function () {
                let element = el.parentNode.parentNode;
                if (element && element.parentNode){
                    element.parentNode.removeChild(element);
                }

            });
        });
    }
</script>