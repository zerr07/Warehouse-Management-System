
<div class="modal fade" id="invoiceModalPDF" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabelPDF" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabelPDF">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="text-left">
                        <div class="row">
                            <div class="col-3">
                                <label for="invoiceDueDatePDF">Due date</label>
                            </div>
                            <div class="col-9">
                                <input type="date" id="invoiceDueDatePDF">
                                <button type="button" onclick="document.getElementById('invoiceDueDate').stepUp();">Up</button>
                                <button type="button" onclick="document.getElementById('invoiceDueDate').stepDown();">Down</button>
                                <button type="button" onclick="up(10);">+10</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label for="invoiceOsjtaPDF">Customer</label>
                            </div>
                            <div class="col-9">
                                <input type="text" id="invoiceOstjaPDF" value="Eraisik">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <label for="invoicePayment">Payment type</label>
                            </div>
                            <div class="col-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="bankInvoicePDF" value="Pank">
                                    <label class="form-check-label" for="bankInvoicePDF">Bank</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cashInvoicePDF" value="Sularaha">
                                    <label class="form-check-label" for="cashInvoicePDF">Cash</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="cardInvoicePDF" value="Kaart">
                                    <label class="form-check-label" for="cardInvoicePDF">Card</label>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {literal}
                    <button type="button" class="btn btn-info"  onclick="savePDF()">
                        Download invoice
                    </button>
                {/literal}
                <div id="elementH"></div>

            </div>
        </div>
    </div>
</div>

<script>

    function savePDF() {
        let d = (document.getElementById("invoiceDueDatePDF").value).split("-");
        let due = d[2] + "/" + d[1] + "/" + d[0];
        let bankInvoice = document.getElementById("bankInvoicePDF");
        let cardInvoice = document.getElementById("cardInvoicePDF");
        let cashInvoice = document.getElementById("cashInvoicePDF");
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
                Client: document.getElementById("invoiceOstjaPDF").value,
                PaymentMethod: invoicePayment,
                DueDate: due,
                Sum: {$sum|string_format:"%.2f"},
                base64: ""

            })
        };
        console.log("invoicePayment",invoicePayment);
        fetch("/cp/POS/GeneratePDFInvoice.php?base64", requestOptions)
            .then(response => response.text())
            .then((d) => {
                downloadPDF(d)

            });

    }
    function up(c) {
        let dateInput = document.getElementById("invoiceDueDatePDF");
        for (let i = 0;i<c;i++){
            dateInput.stepUp();
        }
    }
    function downloadPDF(pdf) {
        const linkSource = `data:application/pdf;base64,`+pdf;
        const downloadLink = document.createElement("a");
        const fileName = "{$reservation.id}_invoice.pdf";

        downloadLink.href = linkSource;
        downloadLink.download = fileName;
        downloadLink.click();
    }
    window.addEventListener("load", function (){

        let dateInput = document.getElementById("invoiceDueDatePDF");
        let d = new Date();
        dateInput.value = d.getFullYear()+"-"+('0' + (d.getMonth()+1)).slice(-2)+"-"+('0' + (d.getDate())).slice(-2);
    });

</script>