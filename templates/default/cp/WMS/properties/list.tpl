{include file='header.tpl'}

<div class="row">
    <div class="col-12">
        {foreach $props as $key => $value}
            <div class="row mt-3 border border-secondary p-1">
                <div class="col-4 m-auto">
                    {$key}
                </div>
                <div class="col-4 m-auto d-flex justify-content-center">
                    {$value.name[2].name}
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-primary btn-sm" onclick="ListPropVal('{$key}')">
                                List
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-warning btn-sm" onclick="deleteProp('{$key}')">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}

        <div class="row mt-3">
            <div class="col-6">
                <button type="button" class="btn btn-primary" onclick="togglePropAddModal()" >
                    Add new property
                </button>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a class="btn btn-primary d-inline-flex ml-2" href="/cp/WMS/"><i class="fas fa-undo-alt"></i> Back</a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="PropModal" tabindex="-1" role="dialog" aria-labelledby="PropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PropModalLabel">Add new value</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left" id="PropModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="modalSave">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>

    window.addEventListener("load", function () {
        setPageTitle("Properties");
    });
    function ListPropVal(id){
        fetch("/controllers/products/properties.php?getPropertyValues="+id+"&id_lang=0").then(response => response.json())
        .then(d => {
           console.log(d)
            let body = document.getElementById("PropModalBody");
            body.innerHTML = "";
            let div1 = document.createElement("div");
            div1.setAttribute("class", "row");
            let div2 = document.createElement("div");
            div2.setAttribute("class", "col-6");
            let div3 = document.createElement("div");
            div3.setAttribute("class", "row");
            for (let id in d){
                let div_prop = document.createElement("div");
                div_prop.setAttribute("class", "col-12");
                div_prop.innerHTML = d[id]['name'][2]['name'];
                div3.append(div_prop)
            }
            let div4 = document.createElement("div");
            div4.setAttribute("class", "col-6");
            let div5 = document.createElement("div");
            div5.setAttribute("class", "row");
            div1.append(div2)
            div1.append(div4)

            div2.append(div3)
            div4.append(div5)

            div5.append(createInputBlock("enName", "EN value name"))
            div5.append(createInputBlock("etName", "ET value name"))
            div5.append(createInputBlock("ruName", "RU value name"))


            body.append(div1)

            document.getElementById("modalSave").innerHTML = "Add value";
            document.getElementById("modalSave").setAttribute("onclick", "createPropValue("+id+")")

            $("#PropModal").modal('show');
            //document.getElementById("modalSave").style.display = "none";

        });
    }
    function createInputBlock(name, placeholder){
        let div1 = document.createElement("div");
        div1.setAttribute("class", "form-group w-100");
        let label1 = document.createElement("label");
        label1.setAttribute("for", name);
        label1.innerHTML = placeholder;
        let input1 = document.createElement("input");
        input1.setAttribute("class", "form-control");
        input1.setAttribute("type", "text");
        input1.setAttribute("id", name);
        input1.setAttribute("name", name);
        input1.setAttribute("placeholder", placeholder);
        let input1feedback = document.createElement("div");
        input1feedback.setAttribute("class", "");
        input1feedback.setAttribute("id", name+"Feedback");
        div1.append(label1)
        div1.append(input1)
        div1.append(input1feedback)
        return div1;
    }

    function togglePropAddModal(){
        document.getElementById("modalSave").innerHTML = "Save changes";
        document.getElementById("modalSave").setAttribute("onclick", "")

        let body = document.getElementById("PropModalBody");
        body.innerHTML = "";
        body.append(createInputBlock("enName", "EN property name"))
        body.append(createInputBlock("etName", "ET property name"))
        body.append(createInputBlock("ruName", "RU property name"))
        $("#PropModal").modal('show');
        document.getElementById("modalSave").setAttribute("onclick", "createProp()")
    }
    function createProp(){
        let ruName = document.getElementById("ruName");
        let enName = document.getElementById("enName");
        let etName = document.getElementById("etName");
        let ruNameF = document.getElementById("ruNameFeedback");
        let enNameF = document.getElementById("enNameFeedback");
        let etNameF = document.getElementById("etNameFeedback");
        ruName.setAttribute("class", "form-control");
        ruNameF.setAttribute("class", "");
        ruNameF.innerText = "";
        enName.setAttribute("class", "form-control");
        enNameF.setAttribute("class", "");
        enNameF.innerText = "";
        etName.setAttribute("class", "form-control");
        etNameF.setAttribute("class", "");
        etNameF.innerText = "";
        if (enName.value === ""){
            enName.setAttribute("class", "form-control is-invalid");
            enNameF.setAttribute("class", "invalid-feedback");
            enNameF.innerText = "Empty name";
            return;
        }
        if (etName.value === ""){
            etName.setAttribute("class", "form-control is-invalid");
            etNameF.setAttribute("class", "invalid-feedback");
            etNameF.innerText = "Empty name";
            return;
        }
        if (ruName.value === ""){
            ruName.setAttribute("class", "form-control is-invalid");
            ruNameF.setAttribute("class", "invalid-feedback");
            ruNameF.innerText = "Empty name";
            return;
        }
        const requestOptions = {
            method: "POST",
            headers:  new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify({
                ruName: ruName.value,
                etName: etName.value,
                enName: enName.value
            })
        };
        fetch("/controllers/products/properties.php", requestOptions).then(response => response.json()).then(d => {
            location.reload();
        });


    }
    function createPropValue(id){
        let ruName = document.getElementById("ruName");
        let enName = document.getElementById("enName");
        let etName = document.getElementById("etName");
        let ruNameF = document.getElementById("ruNameFeedback");
        let enNameF = document.getElementById("enNameFeedback");
        let etNameF = document.getElementById("etNameFeedback");
        ruName.setAttribute("class", "form-control");
        ruNameF.setAttribute("class", "");
        ruNameF.innerText = "";
        enName.setAttribute("class", "form-control");
        enNameF.setAttribute("class", "");
        enNameF.innerText = "";
        etName.setAttribute("class", "form-control");
        etNameF.setAttribute("class", "");
        etNameF.innerText = "";
        if (enName.value === ""){
            enName.setAttribute("class", "form-control is-invalid");
            enNameF.setAttribute("class", "invalid-feedback");
            enNameF.innerText = "Empty name";
            return;
        }
        if (etName.value === ""){
            etName.setAttribute("class", "form-control is-invalid");
            etNameF.setAttribute("class", "invalid-feedback");
            etNameF.innerText = "Empty name";
            return;
        }
        if (ruName.value === ""){
            ruName.setAttribute("class", "form-control is-invalid");
            ruNameF.setAttribute("class", "invalid-feedback");
            ruNameF.innerText = "Empty name";
            return;
        }
        const requestOptions = {
            method: "POST",
            headers:  new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify({
                id: id,
                ruNameVal: ruName.value,
                etNameVal: etName.value,
                enNameVal: enName.value
            })
        };
        fetch("/controllers/products/properties.php", requestOptions).then(response => response.json()).then(d => {
            console.log(d)
            ListPropVal(id)
        });


    }
</script>



{include file='footer.tpl'}
