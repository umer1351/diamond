<div class="modal fade" id="changeGoldRateModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Change Gold Rate</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="changeGoldRateForm"  action="{{ url('gold-rate/store') }}" class="form-horizontal" method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="gold_rate">Gold Rate:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="gold_rate" name="gold_rate"
                                    placeholder="Enter gold rate" onkeypress="return isNumberKey(event)" maxlength="10"
                                    required>
                                    @error('gold_rate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnChangeGoldRate">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
