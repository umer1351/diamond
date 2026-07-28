<div class="modal fade" id="paymentModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading">Sale Payment</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                  </div>
                  <form id="paymentForm" name="paymentForm" class="form-horizontal">
                        <input type="hidden" name="sale_id" id="sale_id">
                        <input type="hidden" name="sale_customer_id" id="sale_customer_id">
                        <div class="modal-body">
                              <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="">Sale Order</label>
                                          <select id="sale_order_id" name="sale_order_id" class="form-control" style="width:100%;">
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="advance_amount">Advance</label>
                                          <input class="form-control" type="number" readonly name="advance_amount" id="advance_amount" value="0" min="0" />
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                          <label for="advance_reference">Reference</label>
                                          <input class="form-control" type="text" name="advance_reference" id="advance_reference"
                                                value="" maxlength="191"
                                                placeholder="Enter Reference" />
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="cash_amount">Cash Amount</label>
                                          <input class="form-control" type="number" name="cash_amount" id="cash_amount" value="0" min="0" />
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="">Cash Account</label>
                                          <select id="cash_account_id" name="cash_account_id" class="form-control"
                                                style="width:100%;">
                                                <option value="" selected="selected">--Select Cash
                                                      Account--</option>
                                                @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}"
                                                      {{ (isset($setting) && $setting->cash_account_id == $item->id) ? 'selected' : '' }}>
                                                      {{ $item->code ?? '' }} -
                                                      {{ $item->name ?? '' }}
                                                </option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="cash_reference">Reference</label>
                                          <input class="form-control" type="text" name="cash_reference" id="cash_reference"
                                                value="" maxlength="191"
                                                placeholder="Enter Reference" />
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="bank_transfer_amount">Bank Transfer Amount</label>
                                          <input class="form-control" type="number" name="bank_transfer_amount" id="bank_transfer_amount" value="0" min="0" />
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="">Bank Transfer Account</label>
                                          <select id="bank_transfer_account_id" name="bank_transfer_account_id"
                                                class="form-control" style="width:100%;">
                                                <option value="" selected="selected">--Select
                                                      Bank Transfer
                                                      Account--</option>
                                                @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}"
                                                      {{ (isset($setting) && $setting->bank_account_id == $item->id) ? 'selected' : '' }}>
                                                      {{ $item->code ?? '' }} -
                                                      {{ $item->name ?? '' }}
                                                </option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="bank_transfer_reference">Reference</label>
                                          <input class="form-control" type="text" name="bank_transfer_reference" id="bank_transfer_reference"
                                                value="" maxlength="191"
                                                placeholder="Enter Reference" />
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="card_amount">Card Amount</label>
                                          <input class="form-control" type="number" name="card_amount" id="card_amount" value="0" min="0" />
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="">Card Account</label>
                                          <select id="card_account_id" name="card_account_id" class="form-control"
                                                style="width:100%;">
                                                <option value="" selected="selected">--Select
                                                      Card
                                                      Account--</option>
                                                @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}"
                                                      {{ (isset($setting) && $setting->card_account_id == $item->id) ? 'selected' : '' }}>
                                                      {{ $item->code ?? '' }} -
                                                      {{ $item->name ?? '' }}
                                                </option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="card_reference">Reference</label>
                                          <input class="form-control" type="text" name="card_reference" id="card_reference"
                                                value="" maxlength="191"
                                                placeholder="Enter Reference" />
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="gold_impurity_amount">Gold Impurity Amount</label>
                                          <input class="form-control" type="number" name="gold_impurity_amount" id="gold_impurity_amount" value="0" min="0" />
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="">Gold Impurity Account</label>
                                          <select id="gold_impurity_account_id" name="gold_impurity_account_id" class="form-control"
                                                style="width:100%;">
                                                <option value="" selected="selected">--Select
                                                      Card
                                                      Account--</option>
                                                @foreach ($accounts as $item)
                                                <option value="{{ $item->id }}"
                                                      {{ (isset($setting) && $setting->card_account_id == $item->id) ? 'selected' : '' }}>
                                                      {{ $item->code ?? '' }} -
                                                      {{ $item->name ?? '' }}
                                                </option>
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                          <label for="gold_impurity_reference">Reference</label>
                                          <input class="form-control" type="text" name="gold_impurity_reference" id="gold_impurity_reference"
                                                value="" maxlength="191"
                                                placeholder="Enter Reference" />
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-4 form-group">
                                          <label for="">Total Amount</label>
                                          <input type="text" id="total_amount" name="total_amount" readonly value="0" class="form-control" style="width:100%;" />
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label for="">Total Paid Amount</label>
                                          <input type="text" id="total_paid" name="total_paid" readonly value="0" class="form-control" style="width:100%;" />
                                    </div>
                                    <div class="col-md-4 form-group">
                                          <label for="">Balance</label>
                                          <input type="text" id="balance" name="balance" readonly value="0" class="form-control" style="width:100%;" />
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button id="paymentSave" class="btn btn-primary"
                                    value="create">Paid</button>
                              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        </div>
                  </form>
            </div>
      </div>
</div>