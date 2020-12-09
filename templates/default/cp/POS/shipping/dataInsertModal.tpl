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
                                {if $reservation.shipping_type != "empty"}
                                    <option value="{$key}" {if $reservation.shipping_type  == $key}selected{else}disabled{/if}>{$value.name}</option>
                                    {else}
                                    <option value="{$key}">{$value.name}</option>
                                {/if}

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
                                console.log(d);
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
            name: nameInput.value.replace("#", ''),
            phone: phoneInput.value.replace("#", ''),
            deliveryNr: deliveryNrInput.value.replace("#", ''),
            terminal: terminalID.replace("#", ''),
            checked: checked.replace("#", ''),
            email: emailInput.value.replace("#", ''),
            comment: commentInput.value.replace("#", ''),
            COD_Sum: COD_SUMInput.value.replace("#", '')
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
            phoneInput.value = phoneInput.value.replace(" ", "");
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
    function checkVenipakFields(){
        let nameInput       = document.getElementById("VenipakInputName");
        //let addressInput      = document.getElementById("VenipakInputAddress");
        //let postcodeInput = document.getElementById("VenipakInputPostcode");
        //let houseNrInput   = document.getElementById("VenipakInputHouseNr");
        //let barcodeInput        = document.getElementById("VenipakInputBarcode");

        let nameFeedback        = document.getElementById("VenipakInputNameFeedback");
        //let addressFeedback       = document.getElementById("VenipakInputAddressFeedback");
        //let postcodeFeedback  = document.getElementById("VenipakInputPostcodeFeedback");
        //let houseNrFeedback    = document.getElementById("VenipakInputHouseNrFeedback");
        //let barcodeFeedback         = document.getElementById("VenipakInputBarcodeFeedback");


        nameInput.setAttribute("class", "form-control");
        nameFeedback.setAttribute("class", "");
        nameFeedback.innerText = "";

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

        return true;
    }
    function loadVenipakForm(){
        let form = document.getElementById("dataInsertForm");
        form.innerHTML = "";
        let inputNameForm = document.createElement("input");

        //---------------------------------------------------

        inputNameForm.setAttribute("class", "form-control");
        inputNameForm.setAttribute("type", "text");
        inputNameForm.setAttribute("name" , "VenipakInputName");
        inputNameForm.setAttribute("id" , "VenipakInputName");
        inputNameForm.setAttribute("placeholder", "Name")

        let labelNameForm = document.createElement("label");
        labelNameForm.setAttribute("class", "mt-2");
        labelNameForm.setAttribute("for", "VenipakInputName");
        labelNameForm.innerText = "Name";

        let checkNameForm = document.createElement("div");
        checkNameForm.setAttribute("id", "VenipakInputNameFeedback");

        form.appendChild(labelNameForm);
        form.appendChild(inputNameForm);
        form.appendChild(checkNameForm);

        //---------------------------------------------------

        let addressBlockDiv = document.createElement("div");
        addressBlockDiv.setAttribute("class", "row");

        let addressAddressDiv = document.createElement("div");
        addressAddressDiv.setAttribute("class", "col-8");

        let addressPostcodeDiv = document.createElement("div");
        addressPostcodeDiv.setAttribute("class", "col-2");

        let addressHouseNrDiv = document.createElement("div");
        addressHouseNrDiv.setAttribute("class", "col-2");

        addressBlockDiv.appendChild(addressAddressDiv);
        addressBlockDiv.appendChild(addressPostcodeDiv);
        addressBlockDiv.appendChild(addressHouseNrDiv);

        form.appendChild(addressBlockDiv);

        //---------------------------------------------------

        let inputAddressForm = document.createElement("input");
        inputAddressForm.setAttribute("class", "form-control");
        inputAddressForm.setAttribute("type", "text");
        inputAddressForm.setAttribute("name" , "VenipakInputAddress");
        inputAddressForm.setAttribute("id" , "VenipakInputAddress");
        inputAddressForm.setAttribute("placeholder", "Address")

        let labelAddressForm = document.createElement("label");
        labelAddressForm.setAttribute("class", "mt-2");
        labelAddressForm.setAttribute("for", "VenipakInputAddress");
        labelAddressForm.innerText = "Address";

        let checkAddressForm = document.createElement("div");
        checkAddressForm.setAttribute("id", "VenipakInputAddressFeedback");

        addressAddressDiv.appendChild(labelAddressForm);
        addressAddressDiv.appendChild(inputAddressForm);
        addressAddressDiv.appendChild(checkAddressForm);

        //---------------------------------------------------

        let inputIndexForm = document.createElement("input");
        inputIndexForm.setAttribute("class", "form-control");
        inputIndexForm.setAttribute("type", "text");
        inputIndexForm.setAttribute("name" , "VenipakInputPostcode");
        inputIndexForm.setAttribute("id" , "VenipakInputPostcode");
        inputIndexForm.setAttribute("placeholder", "Postcode")

        let labelIndexForm = document.createElement("label");
        labelIndexForm.setAttribute("class", "mt-2");
        labelIndexForm.setAttribute("for", "VenipakInputPostcode");
        labelIndexForm.innerText = "Postcode";

        let checkIndexForm = document.createElement("div");
        checkIndexForm.setAttribute("id", "VenipakInputPostcodeFeedback");

        addressPostcodeDiv.appendChild(labelIndexForm);
        addressPostcodeDiv.appendChild(inputIndexForm);
        addressPostcodeDiv.appendChild(checkIndexForm);

        //---------------------------------------------------

        let inputHouseNrForm = document.createElement("input");
        inputHouseNrForm.setAttribute("class", "form-control");
        inputHouseNrForm.setAttribute("type", "text");
        inputHouseNrForm.setAttribute("name" , "VenipakInputHouseNr");
        inputHouseNrForm.setAttribute("id" , "VenipakInputHouseNr");
        inputHouseNrForm.setAttribute("placeholder", "House nr")

        let labelHouseNrForm = document.createElement("label");
        labelHouseNrForm.setAttribute("class", "mt-2");
        labelHouseNrForm.setAttribute("for", "VenipakInputHouseNr");
        labelHouseNrForm.innerText = "House nr";

        let checkHouseNrForm = document.createElement("div");
        checkHouseNrForm.setAttribute("id", "VenipakInputHouseNrFeedback");

        addressHouseNrDiv.appendChild(labelHouseNrForm);
        addressHouseNrDiv.appendChild(inputHouseNrForm);
        addressHouseNrDiv.appendChild(checkHouseNrForm);

        //---------------------------------------------------

        let inputBarcodeForm = document.createElement("input");
        inputBarcodeForm.setAttribute("class", "form-control");
        inputBarcodeForm.setAttribute("type", "text");
        inputBarcodeForm.setAttribute("name" , "VenipakInputBarcode");
        inputBarcodeForm.setAttribute("id" , "VenipakInputBarcode");
        inputBarcodeForm.setAttribute("placeholder", "Barcode")

        let labelBarcodeForm = document.createElement("label");
        labelBarcodeForm.setAttribute("class", "mt-2");
        labelBarcodeForm.setAttribute("for", "VenipakInputBarcode");
        labelBarcodeForm.innerText = "Barcode";

        let checkBarcodeForm = document.createElement("div");
        checkBarcodeForm.setAttribute("id", "VenipakInputBarcodeFeedback");

        form.appendChild(labelBarcodeForm);
        form.appendChild(inputBarcodeForm);
        form.appendChild(checkBarcodeForm);

        //---------------------------------------------------

        let inputEmailForm = document.createElement("input");
        inputEmailForm.setAttribute("class", "form-control");
        inputEmailForm.setAttribute("type", "text");
        inputEmailForm.setAttribute("name" , "VenipakInputEmail");
        inputEmailForm.setAttribute("id" , "VenipakInputEmail");
        inputEmailForm.setAttribute("placeholder", "Email")

        let labelEmailForm = document.createElement("label");
        labelEmailForm.setAttribute("class", "mt-2");
        labelEmailForm.setAttribute("for", "VenipakInputEmail");
        labelEmailForm.innerText = "Email";

        let checkEmailForm = document.createElement("div");
        checkEmailForm.setAttribute("id", "VenipakInputEmailFeedback");

        form.appendChild(labelEmailForm);
        form.appendChild(inputEmailForm);
        form.appendChild(checkEmailForm);

        //---------------------------------------------------

        let inputPhoneForm = document.createElement("input");
        inputPhoneForm.setAttribute("class", "form-control");
        inputPhoneForm.setAttribute("type", "text");
        inputPhoneForm.setAttribute("name" , "VenipakInputPhone");
        inputPhoneForm.setAttribute("id" , "VenipakInputPhone");
        inputPhoneForm.setAttribute("placeholder", "Phone")

        let labelPhoneForm = document.createElement("label");
        labelPhoneForm.setAttribute("class", "mt-2");
        labelPhoneForm.setAttribute("for", "VenipakInputPhone");
        labelPhoneForm.innerText = "Phone";

        let checkPhoneForm = document.createElement("div");
        checkPhoneForm.setAttribute("id", "VenipakInputPhoneFeedback");

        form.appendChild(labelPhoneForm);
        form.appendChild(inputPhoneForm);
        form.appendChild(checkPhoneForm);

        //---------------------------------------------------

        let fileInputDiv = document.createElement("div");
        fileInputDiv.setAttribute("class", "custom-file mt-3");

        let fileInputLabel = document.createElement("label");
        fileInputLabel.setAttribute("class", "custom-file-label");
        fileInputLabel.setAttribute("for", "VenipakFileInput");
        fileInputLabel.innerText = "Choose file";

        let fileInputForm = document.createElement("input");
        fileInputForm.setAttribute("type", "file");
        fileInputForm.setAttribute("class", "custom-file-input");
        fileInputForm.setAttribute("id", "VenipakFileInput");
        fileInputForm.setAttribute("accept", "application/pdf, application/vnd.ms-excel");

        fileInputDiv.appendChild(fileInputLabel);
        fileInputDiv.appendChild(fileInputForm);
        form.appendChild(fileInputDiv);

        //---------------------------------------------------

        let submitBtn = document.createElement("button");
        submitBtn.setAttribute("type", "button");
        submitBtn.setAttribute("class", "btn btn-success mt-3");
        submitBtn.setAttribute("id", "VenipakSaveData");
        submitBtn.setAttribute("onclick", "submitVenipak()");
        submitBtn.innerText = "Save";

        let getPDFBtn = document.createElement("button");
        getPDFBtn.setAttribute("type", "button");
        getPDFBtn.setAttribute("class", "btn btn-success ml-3 mt-3");
        getPDFBtn.setAttribute("id", "VenipakGetPDFBtn");
        getPDFBtn.setAttribute("onclick", "getPDF(this)");
        getPDFBtn.innerText = "Get PDF";
        getPDFBtn.disabled = true;

        form.appendChild(submitBtn);
        form.appendChild(getPDFBtn);
        //---------------------------------------------------

        fetch("/cp/POS/shipping/getShippingStatus.php?type_idJSON={$reservation.id}")
            .then(response => response.json())
            .then((r) => {
                if (r.hasOwnProperty("id") && r.id === "2"){
                    fetch("/cp/POS/shipping/getShippingStatus.php?data_id={$reservation.id}")
                        .then(response => response.json())
                        .then((d) => {
                            console.log(d);
                            if (d.hasOwnProperty("data")){
                                document.getElementById("VenipakInputName").value = d.data.name;
                                document.getElementById("VenipakInputAddress").value = d.data.address;
                                document.getElementById("VenipakInputPostcode").value = d.data.postcode;
                                document.getElementById("VenipakInputHouseNr").value = d.data.housenr;
                                document.getElementById("VenipakInputBarcode").value = d.data.barcode;
                                document.getElementById("VenipakInputPhone").value = d.data.phone;
                                document.getElementById("VenipakInputEmail").value = d.data.email;

                            }
                            if (d.hasOwnProperty("file")){
                                if (d.file){
                                    document.getElementById("VenipakGetPDFBtn").setAttribute("data-id", d.file);
                                    document.getElementById("VenipakGetPDFBtn").disabled = false;
                                }
                            }

                        });
                }
            });
    }
    async function submitVenipak(){
        if (checkVenipakFields()){
            let json = await formJSONVenipak();
            if (document.getElementById("VenipakFileInput").files.length !== 0) {
                const file = document.querySelector('#VenipakFileInput').files[0];
                let res = await convertInputFileToBase64(file).catch(e => Error(e));
                if (res instanceof Error) {
                    console.log('Error: ', res.message);
                    return;
                }
                console.log(JSON.stringify({
                    file: res,
                    saveVenipakFile: "{$reservation.id}"
                }));
                const requestOptions = {
                    method: "POST",
                    headers:  new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        file: res,
                        saveVenipakFile: "{$reservation.id}"
                    })
                };
                fetch("/cp/POS/shipping/getShippingData.php?saveVenipak={$reservation.id}&saveVenipakData="+json).finally(function () {
                    fetch("/cp/POS/shipping/getShippingData.php", requestOptions)
                        .finally(function () {

                            setShippingStatus();
                            loadVenipakForm();
                        });
                });

            } else {
                fetch("/cp/POS/shipping/getShippingData.php?saveVenipak={$reservation.id}&saveVenipakData="+json).finally(function () {
                    setShippingStatus();
                    loadVenipakForm();
                });
            }


        }
    }

    async function formJSONVenipak() {
        let NameInput = document.getElementById("VenipakInputName");
        let AddressInput = document.getElementById("VenipakInputAddress");
        let PostcodeNrInput = document.getElementById("VenipakInputPostcode");
        let HouseNrInput = document.getElementById("VenipakInputHouseNr");
        let BarcodeInput = document.getElementById("VenipakInputBarcode");
        let EmailInput = document.getElementById("VenipakInputEmail");
        let PhoneInput = document.getElementById("VenipakInputPhone");
        let obj = {
            name: NameInput.value.replace("#", ''),
            address: AddressInput.value.replace("#", ''),
            postcode: PostcodeNrInput.value.replace("#", ''),
            housenr: HouseNrInput.value.replace("#", ''),
            barcode: BarcodeInput.value.replace("#", ''),
            phone: PhoneInput.value.replace("#", ''),
            email: EmailInput.value.replace("#", '')
        }
        let json = JSON.stringify(obj);
        console.log("/cp/POS/shipping/getShippingData.php?saveVenipak={$reservation.id}&saveVenipakData=" + json)
        return json;
    }

    function checkDefaultFields(){
        let nameInput       = document.getElementById("DefaultInputName");
        //let addressInput      = document.getElementById("DefaultInputAddress");
        //let postcodeInput = document.getElementById("DefaultInputPostcode");
        //let houseNrInput   = document.getElementById("DefaultInputHouseNr");
        //let barcodeInput        = document.getElementById("DefaultInputBarcode");

        let nameFeedback        = document.getElementById("DefaultInputNameFeedback");
        //let addressFeedback       = document.getElementById("DefaultInputAddressFeedback");
        //let postcodeFeedback  = document.getElementById("DefaultInputPostcodeFeedback");
        //let houseNrFeedback    = document.getElementById("DefaultInputHouseNrFeedback");
        //let barcodeFeedback         = document.getElementById("DefaultInputBarcodeFeedback");


        nameInput.setAttribute("class", "form-control");
        nameFeedback.setAttribute("class", "");
        nameFeedback.innerText = "";

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

        return true;
    }
    function loadDefaultForm(){
        let form = document.getElementById("dataInsertForm");
        form.innerHTML = "";
        let inputNameForm = document.createElement("input");

        //---------------------------------------------------

        inputNameForm.setAttribute("class", "form-control");
        inputNameForm.setAttribute("type", "text");
        inputNameForm.setAttribute("name" , "DefaultInputName");
        inputNameForm.setAttribute("id" , "DefaultInputName");
        inputNameForm.setAttribute("placeholder", "Name")

        let labelNameForm = document.createElement("label");
        labelNameForm.setAttribute("class", "mt-2");
        labelNameForm.setAttribute("for", "DefaultInputName");
        labelNameForm.innerText = "Name";

        let checkNameForm = document.createElement("div");
        checkNameForm.setAttribute("id", "DefaultInputNameFeedback");

        form.appendChild(labelNameForm);
        form.appendChild(inputNameForm);
        form.appendChild(checkNameForm);

        //---------------------------------------------------

        let addressBlockDiv = document.createElement("div");
        addressBlockDiv.setAttribute("class", "row");

        let addressAddressDiv = document.createElement("div");
        addressAddressDiv.setAttribute("class", "col-8");

        let addressPostcodeDiv = document.createElement("div");
        addressPostcodeDiv.setAttribute("class", "col-2");

        let addressHouseNrDiv = document.createElement("div");
        addressHouseNrDiv.setAttribute("class", "col-2");

        addressBlockDiv.appendChild(addressAddressDiv);
        addressBlockDiv.appendChild(addressPostcodeDiv);
        addressBlockDiv.appendChild(addressHouseNrDiv);

        form.appendChild(addressBlockDiv);

        //---------------------------------------------------

        let inputAddressForm = document.createElement("input");
        inputAddressForm.setAttribute("class", "form-control");
        inputAddressForm.setAttribute("type", "text");
        inputAddressForm.setAttribute("name" , "DefaultInputAddress");
        inputAddressForm.setAttribute("id" , "DefaultInputAddress");
        inputAddressForm.setAttribute("placeholder", "Address")

        let labelAddressForm = document.createElement("label");
        labelAddressForm.setAttribute("class", "mt-2");
        labelAddressForm.setAttribute("for", "DefaultInputAddress");
        labelAddressForm.innerText = "Address";

        let checkAddressForm = document.createElement("div");
        checkAddressForm.setAttribute("id", "DefaultInputAddressFeedback");

        addressAddressDiv.appendChild(labelAddressForm);
        addressAddressDiv.appendChild(inputAddressForm);
        addressAddressDiv.appendChild(checkAddressForm);

        //---------------------------------------------------

        let inputIndexForm = document.createElement("input");
        inputIndexForm.setAttribute("class", "form-control");
        inputIndexForm.setAttribute("type", "text");
        inputIndexForm.setAttribute("name" , "DefaultInputPostcode");
        inputIndexForm.setAttribute("id" , "DefaultInputPostcode");
        inputIndexForm.setAttribute("placeholder", "Postcode")

        let labelIndexForm = document.createElement("label");
        labelIndexForm.setAttribute("class", "mt-2");
        labelIndexForm.setAttribute("for", "DefaultInputPostcode");
        labelIndexForm.innerText = "Postcode";

        let checkIndexForm = document.createElement("div");
        checkIndexForm.setAttribute("id", "DefaultInputPostcodeFeedback");

        addressPostcodeDiv.appendChild(labelIndexForm);
        addressPostcodeDiv.appendChild(inputIndexForm);
        addressPostcodeDiv.appendChild(checkIndexForm);

        //---------------------------------------------------

        let inputHouseNrForm = document.createElement("input");
        inputHouseNrForm.setAttribute("class", "form-control");
        inputHouseNrForm.setAttribute("type", "text");
        inputHouseNrForm.setAttribute("name" , "DefaultInputHouseNr");
        inputHouseNrForm.setAttribute("id" , "DefaultInputHouseNr");
        inputHouseNrForm.setAttribute("placeholder", "House nr")

        let labelHouseNrForm = document.createElement("label");
        labelHouseNrForm.setAttribute("class", "mt-2");
        labelHouseNrForm.setAttribute("for", "DefaultInputHouseNr");
        labelHouseNrForm.innerText = "House nr";

        let checkHouseNrForm = document.createElement("div");
        checkHouseNrForm.setAttribute("id", "DefaultInputHouseNrFeedback");

        addressHouseNrDiv.appendChild(labelHouseNrForm);
        addressHouseNrDiv.appendChild(inputHouseNrForm);
        addressHouseNrDiv.appendChild(checkHouseNrForm);

        //---------------------------------------------------

        let inputBarcodeForm = document.createElement("input");
        inputBarcodeForm.setAttribute("class", "form-control");
        inputBarcodeForm.setAttribute("type", "text");
        inputBarcodeForm.setAttribute("name" , "DefaultInputBarcode");
        inputBarcodeForm.setAttribute("id" , "DefaultInputBarcode");
        inputBarcodeForm.setAttribute("placeholder", "Barcode")

        let labelBarcodeForm = document.createElement("label");
        labelBarcodeForm.setAttribute("class", "mt-2");
        labelBarcodeForm.setAttribute("for", "DefaultInputBarcode");
        labelBarcodeForm.innerText = "Barcode";

        let checkBarcodeForm = document.createElement("div");
        checkBarcodeForm.setAttribute("id", "DefaultInputBarcodeFeedback");

        form.appendChild(labelBarcodeForm);
        form.appendChild(inputBarcodeForm);
        form.appendChild(checkBarcodeForm);

        //---------------------------------------------------

        let inputEmailForm = document.createElement("input");
        inputEmailForm.setAttribute("class", "form-control");
        inputEmailForm.setAttribute("type", "text");
        inputEmailForm.setAttribute("name" , "DefaultInputEmail");
        inputEmailForm.setAttribute("id" , "DefaultInputEmail");
        inputEmailForm.setAttribute("placeholder", "Email")

        let labelEmailForm = document.createElement("label");
        labelEmailForm.setAttribute("class", "mt-2");
        labelEmailForm.setAttribute("for", "DefaultInputEmail");
        labelEmailForm.innerText = "Email";

        let checkEmailForm = document.createElement("div");
        checkEmailForm.setAttribute("id", "DefaultInputEmailFeedback");

        form.appendChild(labelEmailForm);
        form.appendChild(inputEmailForm);
        form.appendChild(checkEmailForm);

        //---------------------------------------------------

        let inputPhoneForm = document.createElement("input");
        inputPhoneForm.setAttribute("class", "form-control");
        inputPhoneForm.setAttribute("type", "text");
        inputPhoneForm.setAttribute("name" , "DefaultInputPhone");
        inputPhoneForm.setAttribute("id" , "DefaultInputPhone");
        inputPhoneForm.setAttribute("placeholder", "Phone")

        let labelPhoneForm = document.createElement("label");
        labelPhoneForm.setAttribute("class", "mt-2");
        labelPhoneForm.setAttribute("for", "DefaultInputPhone");
        labelPhoneForm.innerText = "Phone";

        let checkPhoneForm = document.createElement("div");
        checkPhoneForm.setAttribute("id", "DefaultInputPhoneFeedback");

        form.appendChild(labelPhoneForm);
        form.appendChild(inputPhoneForm);
        form.appendChild(checkPhoneForm);

        //---------------------------------------------------

        let fileInputDiv = document.createElement("div");
        fileInputDiv.setAttribute("class", "custom-file mt-3");

        let fileInputLabel = document.createElement("label");
        fileInputLabel.setAttribute("class", "custom-file-label");
        fileInputLabel.setAttribute("for", "DefaultFileInput");
        fileInputLabel.innerText = "Choose file";

        let fileInputForm = document.createElement("input");
        fileInputForm.setAttribute("type", "file");
        fileInputForm.setAttribute("class", "custom-file-input");
        fileInputForm.setAttribute("id", "DefaultFileInput");
        fileInputForm.setAttribute("accept", "application/pdf, application/vnd.ms-excel");

        fileInputDiv.appendChild(fileInputLabel);
        fileInputDiv.appendChild(fileInputForm);
        form.appendChild(fileInputDiv);

        //---------------------------------------------------

        let submitBtn = document.createElement("button");
        submitBtn.setAttribute("type", "button");
        submitBtn.setAttribute("class", "btn btn-success mt-3");
        submitBtn.setAttribute("id", "DefaultSaveData");
        submitBtn.setAttribute("onclick", "submitDefault()");
        submitBtn.innerText = "Save";

        let getPDFBtn = document.createElement("button");
        getPDFBtn.setAttribute("type", "button");
        getPDFBtn.setAttribute("class", "btn btn-success ml-3 mt-3");
        getPDFBtn.setAttribute("id", "DefaultGetPDFBtn");
        getPDFBtn.setAttribute("onclick", "getPDF(this)");
        getPDFBtn.innerText = "Get PDF";
        getPDFBtn.disabled = true;

        form.appendChild(submitBtn);
        form.appendChild(getPDFBtn);
        //---------------------------------------------------

        fetch("/cp/POS/shipping/getShippingStatus.php?type_idJSON={$reservation.id}")
            .then(response => response.json())
            .then((r) => {
                if (r.hasOwnProperty("id") && r.id === "3"){
                    fetch("/cp/POS/shipping/getShippingStatus.php?data_id={$reservation.id}")
                        .then(response => response.json())
                        .then((d) => {
                            console.log(d);
                            if (d.hasOwnProperty("data")){
                                document.getElementById("DefaultInputName").value = d.data.name;
                                document.getElementById("DefaultInputAddress").value = d.data.address;
                                document.getElementById("DefaultInputPostcode").value = d.data.postcode;
                                document.getElementById("DefaultInputHouseNr").value = d.data.housenr;
                                document.getElementById("DefaultInputBarcode").value = d.data.barcode;
                                document.getElementById("DefaultInputPhone").value = d.data.phone;
                                document.getElementById("DefaultInputEmail").value = d.data.email;

                            }
                            if (d.hasOwnProperty("file")){
                                if (d.file){
                                    document.getElementById("DefaultGetPDFBtn").setAttribute("data-id", d.file);
                                    document.getElementById("DefaultGetPDFBtn").disabled = false;
                                }
                            }

                        });
                }
            });
    }
    async function submitDefault(){
        if (checkDefaultFields()){
            let json = await formJSONDefault();
            if (document.getElementById("DefaultFileInput").files.length !== 0) {
                const file = document.querySelector('#DefaultFileInput').files[0];
                let res = await convertInputFileToBase64(file).catch(e => Error(e));
                if (res instanceof Error) {
                    console.log('Error: ', res.message);
                    return;
                }
                console.log(JSON.stringify({
                    file: res,
                    saveDefaultFile: "{$reservation.id}"
                }));
                const requestOptions = {
                    method: "POST",
                    headers:  new Headers({
                        'Content-Type': 'application/json'
                    }),
                    body: JSON.stringify({
                        file: res,
                        saveDefaultFile: "{$reservation.id}"
                    })
                };
                fetch("/cp/POS/shipping/getShippingData.php?saveDefault={$reservation.id}&saveDefaultData="+json).finally(function () {
                    fetch("/cp/POS/shipping/getShippingData.php", requestOptions)
                        .finally(function () {
                            setShippingStatus();
                            loadDefaultForm();
                        });
                });

            } else {
                fetch("/cp/POS/shipping/getShippingData.php?saveDefault={$reservation.id}&saveDefaultData="+json).finally(function () {
                    setShippingStatus();
                    loadDefaultForm();
                });
            }


        }
    }

    async function formJSONDefault() {
        let NameInput = document.getElementById("DefaultInputName");
        let AddressInput = document.getElementById("DefaultInputAddress");
        let PostcodeNrInput = document.getElementById("DefaultInputPostcode");
        let HouseNrInput = document.getElementById("DefaultInputHouseNr");
        let BarcodeInput = document.getElementById("DefaultInputBarcode");
        let EmailInput = document.getElementById("DefaultInputEmail");
        let PhoneInput = document.getElementById("DefaultInputPhone");
        let obj = {
            name: NameInput.value.replace("#", ''),
            address: AddressInput.value.replace("#", ''),
            postcode: PostcodeNrInput.value.replace("#", ''),
            housenr: HouseNrInput.value.replace("#", ''),
            barcode: BarcodeInput.value.replace("#", ''),
            phone: PhoneInput.value.replace("#", ''),
            email: EmailInput.value.replace("#", '')
        }
        let json = JSON.stringify(obj);
        console.log("/cp/POS/shipping/getShippingData.php?saveDefault={$reservation.id}&saveDefaultData=" + json)
        return json;
    }

    const convertInputFileToBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });

    function getPDF(el){
        {literal}printJS('/uploads/files/pdf/'+el.getAttribute("data-id")){/literal}
    }
</script>