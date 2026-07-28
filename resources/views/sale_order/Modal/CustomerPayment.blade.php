<div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                  </div>
                  <form id="customerPaymentForm" class="form-horizontal">
                        <div class="modal-body">
                              <div class="row">
                                    <input type="hidden" id="id" name="id">
                                    <input type="hidden" id="sale_order_id" name="sale_order_id">
                                    <input type="hidden" id="sale_order_customer_id" name="sale_order_customer_id">
                                    <div class="col-md-6">
                                          <label for="amount">Amount:<span style="color:red;">*</span></label>
                                          <div class="form-group">
                                                <input type="number" step="any" class="form-control" name="amount"
                                                      id="amount" placeholder="Enter Amount">
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <label for="recieving_account_id">Recieving Account:<span style="color:red;">*</span></label>
                                          <div class="form-group">
                                                <select class="form-control" name="recieving_account_id" id="recieving_account_id" style="width: 100%;" required>
                                                      <option value="" selected disabled>--Select Account--</option>
                                                      @if (isset($accounts))
                                                      @foreach ($accounts as $item)
                                                      <option value="{{ $item->id }}" {{ (isset($setting) && $setting->recieving_account_id == $item->id) ? 'selected' : '' }}>{{ $item->code }} {{ $item->name }}
                                                      </option>
                                                      @endforeach
                                                      @endif
                                                </select>
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                          <label for="reference">Reference:</label>
                                          <div class="form-group">
                                                <input type="text" class="form-control" name="reference" id="reference"
                                                      placeholder="Enter Reference">
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-secondary" type="button" data-dismiss="modal"
                                    id="close">Close</button>
                              <button type="submit" id="submit" class="btn btn-primary" value="create">Save
                              </button>
                        </div>
                  </form>
            </div>
      </div>
</div>