<div hidden>
    <div id="form" class="container">
        <div class="row">
            <div class="col-7">
                <img src="/cp/POS/sales/aaaaaaa.png">
            </div>
            <div class="col-2">
                <b>
                    Reservatsioon id<br />
                    Kuupäev
                </b>
            </div>
            <div class="col-3">
                <b>
                    {$reservation.id} <br />
                    {$reservation.date}
                </b>
            </div>
        </div>
        <div class="row">
            <div class="col-6" style="border: 1px solid black">
                <div class="row">
                    <div class="col-2">
                        <b>Müüja:</b><br />
                        Reg Nr<br />
                        Aadress<br />
                        KMKR
                    </div>
                    <div class="col-10">
                        AZ Trade OÜ<br />
                        12474341<br />
                        J. Koorti tn 2-122 Tallinn Harjumaa 13623<br />
                        EE101681917
                    </div>
                </div>
            </div>
            <div class="col-6" style="border: 1px solid black">
                <div class="row">
                    <div class="col-2">
                        <b>Ostja:</b><br />
                        <span id="invoicePaymentLabel"></span>
                    </div>
                    <div class="col-10">
                        <span id="invoiceOsjtaText"></span><br />
                        <span id="invoicePaymentText"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2 offset-6">
                <b>Maksetähtaeg:</b><br />
                Viivis:
            </div>
            <div class="col-4">
                <b id="invoiceDueDateText">8/6/2020</b><br />
                0.05%
            </div>
        </div>
        <div class="row border border-dark">
            <div class="col-1">Kood</div>
            <div class="col-3">Teenuste/kaupade nimetus</div>
            <div class="col-1">Ühik</div>
            <div class="col-1">Maht</div>
            <div class="col-2">Hind KM-ga</div>
            <div class="col-2">Hind KM-ta</div>
            <div class="col-2">Kokku KM-ta</div>
        </div>
        {foreach $reservation.products as $prod}
            <div class="row border border-dark">
                {if $prod.tag == "Buffertoode"}
                    <div class="col-1"></div>
                    <div class="col-3">{$prod.name}</div>
                {else}
                    <div class="col-1">{$prod.tag}</div>
                    <div class="col-3">{$prod.name.et}</div>
                {/if}
                <div class="col-1">tk</div>
                <div class="col-1">{$prod.quantity}</div>
                <div class="col-2">{$prod.basePrice}</div>
                <div class="col-2">{($prod.basePrice/1.2)|round:4}</div>
                <div class="col-2">{($prod.price/1.2)|round:4}</div>
            </div>
        {/foreach}

        <div class="row">
            <div class="col-2 offset-6">
                Summa<br />
                Käibemaks 20%<br />
                <b>Kokku</b><br />
                Valuuta
            </div>
            <div class="col-4">
                {($sum/1.2)|round:2|string_format:"%.2f"}<br />
                {($sum - $sum/1.2)|round:2|string_format:"%.2f"}<br />
                <b>{$sum|string_format:"%.2f"}</b><br />
                <b>EUR</b>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                AZ TRADE OÜ, J. Koorti tn 2-122, 13623, Tallinn<br />
                Reg nr: 12474341 , KMKR: EE101681917<br />
                E-post: info@bigshop.ee<br />
                Telefon: +37258834435
            </div>
            <div class="col-6">
                SWEDBANK EE792200221057460362<br />
                SWIFT: HABAEE2X
            </div>
        </div>
    </div>
</div>



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
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {literal}
                    <button type="button" class="btn btn-info"  onclick="update();printJS(
                    {printable: 'form', type: 'html',
                    documentTitle: 'Invoice',
                    css: 'https\://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'}
                            )">
                        Print invoice
                    </button>
                {/literal}

            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        let dateInput = document.getElementById("invoiceDueDate");
        let d = new Date();
        dateInput.value = d.getFullYear()+"-"+('0' + (d.getMonth()+1)).slice(-2)+"-"+d.getDate();

    }
    function up(c) {
        let dateInput = document.getElementById("invoiceDueDate");
        for (let i = 0;i<c;i++){
            dateInput.stepUp();
        }
    }
    function update() {
        let d = (document.getElementById("invoiceDueDate").value).split("-")
        document.getElementById("invoiceDueDateText").innerText =  d[2]+"/"+d[1]+"/"+d[0];
        document.getElementById("invoiceOsjtaText").innerText =  document.getElementById("invoiceOstja").value
        let bankInvoice = document.getElementById("bankInvoice");
        let cardInvoice = document.getElementById("cardInvoice");
        let cashInvoice = document.getElementById("cashInvoice");

        if (cashInvoice.checked || cardInvoice.checked || bankInvoice.checked) {
            let invoicePayment = "";
            let c = 0;
            if (cashInvoice.checked){
                if (c > 0){
                    invoicePayment += " +";
                }
                invoicePayment += " Sularaha";
                c++;
            }
            if (cardInvoice.checked){
                if (c > 0){
                    invoicePayment += " +";
                }
                invoicePayment += " Kaart";
                c++;
            }
            if (bankInvoice.checked){
                if (c > 0){
                    invoicePayment += " +";
                }
                invoicePayment += " Pank";
                c++;
            }
            document.getElementById("invoicePaymentLabel").innerText = "Makseviis";
            document.getElementById("invoicePaymentText").innerText = invoicePayment;
        } else {
            document.getElementById("invoicePaymentLabel").innerText = "";
            document.getElementById("invoicePaymentText").innerText = "";
        }
    }
</script>