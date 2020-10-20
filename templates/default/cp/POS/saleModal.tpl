<div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel" aria-hidden="true" style="color: black">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saleModalLabel" >Perform sale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalSHOP">
                <h4 id="modalSUM">Sum: 0</h4>
                <div class="row">
                    <div class="col-2 m-auto">
                        <span>Cash:</span>
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control" id="cash" name="cash" placeholder="0.00" value="0.00">
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-success" id="cashBtn">100%</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2 m-auto">
                        <span>Card:</span>
                    </div>
                    <div class="col-7">
                        <input type="text" class="form-control" id="card" name="card" placeholder="0.00" value="0.00" >
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-success" id="cardBtn">100%</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <label for="ostja">Ostja:</label>
                    </div>
                    <div class="col-10">
                        <input type="text" class="form-control" id="ostja" name="ostja" placeholder="Ostja">
                    </div>
                </div>
                <h4 id="modalTagasi">Tagasi : </h4>
            </div>
            <div class="modal-body text-white" id="modalOTHER">
                <label for="card">Tellimuse Nr:</label>
                <input type="text" class="form-control" id="tellimuseNr" name="tellimuseNr" placeholder="Tellimuse Nr">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pood" id="pood" value="Pood" checked>
                    <label class="form-check-label" for="pood">
                        Pood
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pood" id="tellimus" value="Tellimus">
                    <label class="form-check-label" for="tellimus">
                        Tellimus
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="submit" class="btn btn-success" {*formtarget="_blank"*} id="sale" name="sale" formaction="/cp/POS/sale.php" disabled>Perform sale</button>
            </div>
        </div>
    </div>
</div>
