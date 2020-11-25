<div class="modal fade text-left" id="dataInsertModal" tabindex="-1" role="dialog" aria-labelledby="dataInsertLabel" aria-hidden="true" style="color: black">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row w-100">
                    <div class="col-4">
                        <h5 class="modal-title" id="dataInsertLabel" >Set shipping data</h5>
                    </div>
                    <div class="col-7">
                        <select class="custom-select mr-sm-2" id="carrierSelect" onchange="loadCarrierForm(this.value)">
                            {foreach $shipping_types as $key => $value}
                                <option value="{$key}">{$value.name}</option>
                            {/foreach}

                        </select>
                    </div>
                    <div class="col-1">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>



            </div>
            <div class="modal-body" id="dataInsertForm">
                {* Another magic happens lol *}
            </div>
        </div>
    </div>
</div>

<script>
    $(window).on("load", function () {
       loadCarrierForm(document.getElementById("carrierSelect").value);
    });
    function loadCarrierForm(val){
        console.log(val)
        if (val === "1"){
            loadSmartpostForm();
        } else if (val === "2"){
            loadVenipakForm();
        } else {
            loadDefaultForm();
        }
    }

    function loadSmartpostForm(){

        let name        = "<label class='mt-2' for='SmartPostNameInput'>Name</label><input type='text' class='form-control' name='SmartPost_name' placeholder='Name' id='SmartPostNameInput'><div class='' id='SmartPostNameFeedback'></div>";
        let phone       = "<label class='mt-2' for='SmartPostPhoneInput'>Phone number</label><input type='text' class='form-control' name='SmartPost_phone' placeholder='Phone number' id='SmartPostPhoneInput'><div class='' id='SmartPostPhoneFeedback'></div>";
        let deliveryNr  = "<label class='mt-2' for='SmartPostDelivNrInput'>Delivery number</label><input type='text' class='form-control' name='SmartPost_deliveryNr' placeholder='Delivery number' id='SmartPostDelivNrInput'><div class='' id='SmartPostDelivNrFeedback'></div>";
        let email       = "<label class='mt-2' for='SmartPost_email'>Email</label><input type='text' class='form-control' name='SmartPost_email' placeholder='Email' id='SmartPost_email'>";
        let comment     = "<label class='mt-2' for='SmartPostCommentInput'>Comment</label><textarea class='form-control' rows='3' id='SmartPostCommentInput'></textarea>";
        let lunasumma   = "<div class='custom-control custom-radio mt-3'>" +
            "  <input type='radio' id='clientPaysTheDelivery' name='Smartpost_type' class='custom-control-input' value='clientPaysTheDelivery'>" +
            "  <label class='custom-control-label my-auto' for='clientPaysTheDelivery'>Client pays the delivery</label>" +
            "</div>";
        let cashOnDelivery =
            "<div class='form-row align-items-center mt-3'>" +
            "   <div class='col-auto'>" +
            "      <div class='custom-control custom-radio'>" +
            "          <input type='radio' id='cashOnDelivery' name='Smartpost_type' class='custom-control-input' value='cashOnDelivery'>" +
            "          <label class='custom-control-label my-auto' for='cashOnDelivery'>Cash on delivery</label>" +
            "      </div>" +
            "   </div>" +
            "   <div class='col-auto'>" +
            "      <input type='text' class='form-control' name='SmartPost_COD_Sum' placeholder='Sum' id='SmartPost_COD_Sum' disabled><div class='' id='SmartPostCODSumFeedback'></div>" +
            "   </div>" +
            "</div>";
        let defDelivery = "<div class='custom-control custom-radio mt-3'>" +
            "  <input type='radio' id='defDelivery' name='Smartpost_type' class='custom-control-input' value='defDelivery' checked>" +
            "  <label class='custom-control-label my-auto' for='defDelivery'>Default</label>" +
            "</div>";

        let parcel_term = "<label class='mt-2' for='SmartPostTerminalInput'>Parcel terminal</label>" +
            "<input type='text' class='form-control' name='SmartPost_terminal' placeholder='Smartpost parcel terminal' list='Smartpost_term' id='SmartPostTerminalInput'>" +
            "<div class='' id='SmartPostTerminalFeedback'></div>";
        let parcel_term_list = "<datalist id='Smartpost_term'></datalist>";

        let submit_btn = "<button type='button' class='btn btn-success mt-3' id='SmartpostSaveData' onclick='submitSmartpost()' disabled>Save</button>"
        let submit_get_bar_btn = "<button type='button' class='btn btn-success mt-3 ml-3' id='SmartpostSaveDataAndGetBar' onclick='SmartpostGetBar()' disabled>Save and get barcode</button>"
        let submit_label_btn = "<button type='button' class='btn btn-success mt-3 ml-3' id='getSmartpostLabelBtn' onclick='getSmartpostLabel();' disabled>Generate label</button>"
        let submit_label_and_mark_btn = "<button type='button' class='btn btn-success mt-3 ml-3' id='getSmartpostLabelAndMarkShipped' onclick='getSmartpostLabel();markAsShipped();loadSmartpostForm();' disabled>" +
            "Generate label and mark as posted" +
            "</button>"

        $("#dataInsertForm").html(name + phone + email + deliveryNr + parcel_term + parcel_term_list +lunasumma+cashOnDelivery+defDelivery+ comment + submit_btn + submit_get_bar_btn + submit_label_btn + submit_label_and_mark_btn);

        let term_list = document.getElementById("Smartpost_term");
        fetch("/cp/POS/shipping/getShippingData.php?getSmartpost")
        .then(response => response.json())
        .then((d) => {
            d.item.forEach(i => {
                let el = document.createElement("option");
                el.setAttribute("value", i.name);
                el.setAttribute("data-id", i.place_id);
                term_list.appendChild(el);
            })
        }).finally(function (){
            fetch("/cp/POS/shipping/getShippingStatus.php?type_idJSON={$reservation.id}")
                .then(response => response.json())
                .then((r) => {
                    if (r.hasOwnProperty("id") && r.id === "1"){
                        fetch("/cp/POS/shipping/getShippingStatus.php?data_id={$reservation.id}")
                            .then(response => response.json())
                            .then((d) => {

                                if (d.hasOwnProperty("data")){
                                    document.getElementById("SmartPostNameInput").value = d.data.name;
                                    document.getElementById("SmartPostPhoneInput").value = d.data.phone;
                                    document.getElementById("SmartPost_email").value = d.data.email;

                                    document.getElementById("SmartPostDelivNrInput").value = d.data.deliveryNr;
                                    document.getElementById("SmartPostTerminalInput").value = document.querySelector("datalist > option[data-id='"+d.data.terminal+"']").value;
                                    document.querySelector("input[name='Smartpost_type'][value='"+d.data.checked+"']").checked = true;
                                    document.getElementById("SmartPostCommentInput").innerText = d.data.comment;
                                    if (d.data.COD_Sum !== undefined){
                                        document.getElementById("SmartPost_COD_Sum").value = d.data.COD_Sum;
                                    }
                                }
                                console.log(d.data)
                                if (d.data.hasOwnProperty("barcode")){
                                    document.getElementById("additionalInfo").innerHTML = "<p>Barcode: "+d.data.barcode+"<br>Reference: "+d.data.reference+"</p>";
                                }

                            });
                    }
                    fetch("/cp/POS/shipping/getShippingStatus.php?idJSON={$reservation.id}")
                        .then(response => response.json())
                        .then((d) => {
                            checkButtons(d.id)
                        });
                    checkRadio();
                    $("input[type='radio'][name='Smartpost_type']").on("change", function(){
                        checkRadio();
                    });
                });
        });

    }
    function checkButtons(status){
        let SmartpostSaveData = document.getElementById("SmartpostSaveData");
        let SmartpostSaveDataAndGetBar = document.getElementById("SmartpostSaveDataAndGetBar");
        let getSmartpostLabelBtn = document.getElementById("getSmartpostLabelBtn");
        let getSmartpostLabelAndMarkShipped = document.getElementById("getSmartpostLabelAndMarkShipped");

        if (status === "1" || status === "2"){
            SmartpostSaveData.disabled = false;
            SmartpostSaveDataAndGetBar.disabled = false;
            getSmartpostLabelBtn.disabled = false;
            getSmartpostLabelAndMarkShipped.disabled = false;

        } else if (status === "3" || status === "4"){
            SmartpostSaveData.disabled = true;
            SmartpostSaveDataAndGetBar.disabled = true;
            getSmartpostLabelBtn.disabled = false;
            getSmartpostLabelAndMarkShipped.disabled = false;
        } else if (status === "5" || status === "6") {
            SmartpostSaveData.disabled = true;
            SmartpostSaveDataAndGetBar.disabled = true;
            getSmartpostLabelBtn.disabled = false;
            getSmartpostLabelAndMarkShipped.disabled = true;
        } else {
            SmartpostSaveData.disabled = false;
            SmartpostSaveDataAndGetBar.disabled = false;
            getSmartpostLabelBtn.disabled = false;
            getSmartpostLabelAndMarkShipped.disabled = false;
        }

    }
    function checkRadio(){
        if (document.getElementById("cashOnDelivery").checked === true){
            document.getElementById("SmartPost_COD_Sum").disabled = false;
        } else {
            document.getElementById("SmartPost_COD_Sum").disabled = true;
        }
    }
    function formJSONSmartpost(){
        let nameInput       = document.getElementById("SmartPostNameInput");
        let phoneInput      = document.getElementById("SmartPostPhoneInput");
        let deliveryNrInput = document.getElementById("SmartPostDelivNrInput");
        let terminalInput   = document.getElementById("SmartPostTerminalInput");
        let COD_SUMInput    = document.getElementById("SmartPost_COD_Sum");
        let emailInput      = document.getElementById("SmartPost_email");

        let terminalID = document.querySelector("datalist > option[value='"+terminalInput.value+"']").getAttribute("data-id");
        let checked;
        document.querySelectorAll("input[name='Smartpost_type']").forEach(el => {
            if (el.checked) {
                checked = el.value;
            }
        });
        let commentInput    = document.getElementById("SmartPostCommentInput");
        let obj = {
            name: nameInput.value,
            phone: phoneInput.value,
            deliveryNr: deliveryNrInput.value,
            terminal: terminalID,
            checked: checked,
            email: emailInput.value,
            comment: commentInput.value,
            COD_Sum: COD_SUMInput.value
        }
        let json = JSON.stringify(obj);
        console.log("/cp/POS/shipping/getShippingData.php?saveSmartPost={$reservation.id}&saveSmartPostData="+json)
        return json;
    }
    function submitSmartpost(){
        if (checkSmartpostFields()){
            let json = formJSONSmartpost();
            fetch("/cp/POS/shipping/getShippingData.php?saveSmartPost={$reservation.id}&saveSmartPostData="+json).finally(function () {
                setShippingStatus();
                loadSmartpostForm();
            });

        }
    }
    function SmartpostGetBar(){
        let json = formJSONSmartpost();
        console.log("/cp/POS/shipping/getShippingData.php?saveAndBarSmartPost={$reservation.id}&saveAndBarSmartPostData="+json)
        fetch("/cp/POS/shipping/getShippingData.php?saveAndBarSmartPost={$reservation.id}&saveAndBarSmartPostData="+json)
        .finally(function () {
            setShippingStatus();
            loadSmartpostForm();
        });
    }
    function getSmartpostLabel(){
        fetch("/cp/POS/shipping/getShippingData.php?getSmartPostLabel={$reservation.id}")
        .then(response => response.text())
        .then(d => {
            {literal}printJS({printable: d, type: 'pdf', base64: true}){/literal}
        });

    }
    function checkSmartpostFields(){
        let nameInput       = document.getElementById("SmartPostNameInput");
        let phoneInput      = document.getElementById("SmartPostPhoneInput");
        let deliveryNrInput = document.getElementById("SmartPostDelivNrInput");
        let terminalInput   = document.getElementById("SmartPostTerminalInput");
        let CODInput        = document.getElementById("SmartPost_COD_Sum");

        let nameFeedback        = document.getElementById("SmartPostNameFeedback");
        let phoneFeedback       = document.getElementById("SmartPostPhoneFeedback");
        let deliveryNrFeedback  = document.getElementById("SmartPostDelivNrFeedback");
        let terminalFeedback    = document.getElementById("SmartPostTerminalFeedback");
        let CODFeedback         = document.getElementById("SmartPostCODSumFeedback");

        nameInput.setAttribute("class", "form-control mt-3");
        nameFeedback.setAttribute("class", "");
        nameFeedback.innerText = "";

        phoneInput.setAttribute("class", "form-control mt-3");
        phoneFeedback.setAttribute("class", "");
        phoneFeedback.innerText = "";

        deliveryNrInput.setAttribute("class", "form-control mt-3");
        deliveryNrFeedback.setAttribute("class", "");
        deliveryNrFeedback.innerText = "";

        terminalInput.setAttribute("class", "form-control mt-3");
        terminalFeedback.setAttribute("class", "");
        terminalFeedback.innerText = "";

        CODInput.setAttribute("class", "form-control");
        CODFeedback.setAttribute("class", "");
        CODFeedback.innerText = "";

        try {
            if (nameInput.value === ""){
                throw "Name is empty";
            }
        } catch (err) {
            nameInput.setAttribute("class", "form-control mt-3 is-invalid");
            nameFeedback.setAttribute("class", "invalid-feedback");
            nameFeedback.innerText = "Please specify clients name!";
            return false;
        }

        try {
            if (phoneInput.value === ""){
                throw "Phone is empty";
            }
        } catch (err) {
            phoneInput.setAttribute("class", "form-control mt-3 is-invalid");
            phoneFeedback.setAttribute("class", "invalid-feedback");
            phoneFeedback.innerText = "Please specify phone number!";
            return false;
        }
        try {
            if (deliveryNrInput.value === ""){
                throw "Delivery number is empty";
            }
        } catch (err) {
            deliveryNrInput.setAttribute("class", "form-control mt-3 is-invalid");
            deliveryNrFeedback.setAttribute("class", "invalid-feedback");
            deliveryNrFeedback.innerText = "Please specify phone number!";
            return false;
        }
        try {
            let el = terminalInput.value;
            let val = document.querySelector("datalist > option[value='"+el+"']").getAttribute("data-id");
        } catch (err){
            terminalInput.setAttribute("class", "form-control mt-3 is-invalid");
            terminalFeedback.setAttribute("class", "invalid-feedback");
            terminalFeedback.innerText = "Please specify parcel terminal!";
            return false;
        }
        try {
            if (document.getElementById("cashOnDelivery").checked === true){
                if (document.getElementById("SmartPost_COD_Sum").value === ""){
                    throw "COD sum in empty";

                }
                document.getElementById("SmartPost_COD_Sum").disabled = false;
            }
            if (deliveryNrInput.value === ""){
            }
        } catch (err) {
            CODInput.setAttribute("class", "form-control is-invalid");
            CODFeedback.setAttribute("class", "invalid-feedback");
            CODFeedback.innerText = "Please specify phone number!";
            return false;
        }

        return true;

    }
    function loadVenipakForm(){
        $("#dataInsertForm").html("Nothing here yet<pre>•ᴗ•</pre>");
    }
    function loadDefaultForm(){
        $("#dataInsertForm").html("Nothing here yet<pre>╰⋃╯ლ(´ڡ`ლ)</pre>");

    }
</script>