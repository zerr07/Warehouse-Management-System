
<!-- Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="text-left">
                        <div class="row">
                            <div class="col-3">
                                <label for="invoiceDueDate">Due date</label>
                            </div>
                            <div class="col-9">
                                <input type="date" id="invoiceDueDate">
                                <button type="button" onclick="document.getElementById('invoiceDueDate').stepUp();">Up</button>
                                <button type="button" onclick="document.getElementById('invoiceDueDate').stepDown();">Down</button>
                                <button type="button" onclick="up(10);">+10</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label for="invoiceOsjta">Customer</label>
                            </div>
                            <div class="col-9">
                                <input type="text" id="invoiceOstja" value="Eraisik">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label for="invoicePayment">Payment type</label>
                            </div>
                            <div class="col-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="bankInvoice" value="Pank">
                                    <label class="form-check-label" for="bankInvoice">Bank</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cashInvoice" value="Sularaha">
                                    <label class="form-check-label" for="cashInvoice">Cash</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cardInvoice" value="Kaart">
                                    <label class="form-check-label" for="cardInvoice">Card</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label for="invoicePayment">Bank account</label>
                            </div>
                            <div class="col-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bank" id="bankRegular" value="Regular" checked>
                                    <label class="form-check-label" for="bankRegular">
                                        Regular
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bank" id="bankFB" value="FB">
                                    <label class="form-check-label" for="bankFB">
                                        FB
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {literal}
                    <button type="button" class="btn btn-info"  onclick="printPDF()">
                        Print invoice
                    </button>
                {/literal}

            </div>
        </div>
    </div>
</div>

<script>
    function printPDF() {
        let d = (document.getElementById("invoiceDueDate").value).split("-")
        let due = d[2] + "/" + d[1] + "/" + d[0];
        let bankInvoice = document.getElementById("bankInvoice");
        let cardInvoice = document.getElementById("cardInvoice");
        let cashInvoice = document.getElementById("cashInvoice");
        let bank = document.querySelector("input[type='radio'][name='bank']:checked");
        let invoicePayment = "";
        if (cashInvoice.checked || cardInvoice.checked || bankInvoice.checked) {
            let c = 0;
            if (cashInvoice.checked) {
                if (c > 0) {
                    invoicePayment += " +";
                }
                invoicePayment += " Sularaha";
                c++;
            }
            if (cardInvoice.checked) {
                if (c > 0) {
                    invoicePayment += " +";
                }
                invoicePayment += " Kaart";
                c++;
            }
            if (bankInvoice.checked) {
                if (c > 0) {
                    invoicePayment += " +";
                }
                invoicePayment += " Pank";
                c++;
            }
        }
        const requestOptions = {
            method: "POST",
            headers: new Headers({
                'Content-Type': 'application/json'
            }),
            body: JSON.stringify({
                Data: product_arr,
                InvoiceNum: {$reservation.id},
                Client: document.getElementById("invoiceOstja").value,
                PaymentMethod: invoicePayment,
                DueDate: due,
                Sum: {$sum|string_format:"%.2f"},
                base64: "",
                bank: bank.value

            })
        };
        console.log("invoicePayment",invoicePayment);
        fetch("/cp/POS/GeneratePDFInvoice.php?base64", requestOptions)
            .then(response => response.text())
            .then((d) => {
                {literal}
                printJS({printable: d, type: 'pdf', base64: true});
                {/literal}

            });

    }
    window.addEventListener("load", function (){
        let dateInput = document.getElementById("invoiceDueDate");
        let d = new Date();
        dateInput.value = d.getFullYear()+"-"+('0' + (d.getMonth()+1)).slice(-2)+"-"+('0' + (d.getDate())).slice(-2);
    });
    function up(c) {
        let dateInput = document.getElementById("invoiceDueDate");
        for (let i = 0;i<c;i++){
            dateInput.stepUp();
        }
    }
</script>