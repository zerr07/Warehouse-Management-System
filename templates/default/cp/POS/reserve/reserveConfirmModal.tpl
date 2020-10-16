<div class="modal fade text-left" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel" aria-hidden="true" style="color: black">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saleModalLabel" >Perform sale</h5>
                <select class="custom-select mr-sm-2" id="modeSelect" name="mode"
                            style="margin-left: 25px; display: block; width: 25%;
                            background: #009ac0; border-color: #009ac0;color: white;">
                    <option value="Bigshop" selected>Shop</option>
                    <option value="Osta">Osta</option>
                    <option value="Minuvalik">Minuvalik</option>
                    <option value="Shoppa">Shoppa</option>
                </select>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalSHOP">

                <h4 id="modalSUM">Sum: 0</h4>
                <div class="row">
                    <div class="col-2"><label for="cash" class="text-white">Cash:</label></div>
                    <div class="col-6"><input type="text" class="form-control" id="cash" name="cash" placeholder="0.00" value="0.00"></div>
                    <div class="col-4"><button type="button" class="btn btn-success w-100" id="cashBtn">100%</button></div>
                </div>
                <div class="row">
                    <div class="col-2"><label for="card" class="text-white">Card:</label></div>
                    <div class="col-6"><input type="text" class="form-control" id="card" name="card" placeholder="0.00" value="0.00"></div>
                    <div class="col-4"><button type="button" class="btn btn-success w-100" id="cardBtn">100%</button></div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-2"><label for="ostja" class="text-white">Ostja:</label></div>
                    <div class="col-10"><input type="text" class="form-control" id="ostja" name="ostja" placeholder="Ostja"></div>
                </div>


                <h4 style="margin-top: 10px" id="modalTagasi">Tagasi : </h4>
            </div>
            <div class="modal-body text-white" id="modalOTHER">
                <div class="row">
                    <div class="col-4"><label for="card" >Tellimuse Nr:</label></div>
                    <div class="col-8"><input type="text" class="form-control" id="tellimuseNr" name="tellimuseNr" placeholder="Tellimuse Nr" style="margin-top: 10px;height: 42px"></div>
                </div>


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

                <button type="button" class="btn btn-success" {*formtarget="_blank"*} id="sale" name="sale" disabled>Perform sale</button>
            </div>
        </div>
    </div>
</div>
