<div class="modal fade" id="changeDollarRateModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Change Dollar Rate</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="changeDollarRateForm" action="{{ url('dollar-rate/store') }}" class="form-horizontal"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="dollar_rate">Dollar Rate:<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="dollar_rate" name="dollar_rate"
                                    placeholder="Enter dollar rate" onkeypress="return isNumberKey(event)"
                                    maxlength="10" required>
                                @error('dollar_rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
