<div class="modal fade" id="customerModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">New Customer</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <form id="customerForm" name="customerForm" class="form-horizontal">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="name">Name<span class="text-danger">*</span> </label>
                            <input class="form-control" type="text" name="name"
                                value="" maxlength="191"
                                placeholder="Enter name" required />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="cnic">CNIC<small class="text-danger">(unique)</small></label>
                            <input class="form-control" type="text" name="cnic"
                                value="" maxlength="191"
                                placeholder="Enter CNIC" />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="contact">Contact<span class="text-danger">**</span></label>
                            <input class="form-control" type="text" name="contact"
                                value="" maxlength="191"
                                placeholder="Enter contact" />
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email"
                                value="" maxlength="191"
                                placeholder="Enter email" />
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="address">Address</label>
                            <input class="form-control" type="text" name="address"
                                value="" maxlength="191"
                                placeholder="Enter address" />
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="date_of_birth">Date of Birth</label>
                            <input class="form-control" type="date" name="date_of_birth"
                                value="" />
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="anniversary_date">Anniversary Date</label>
                            <input class="form-control" type="date" name="anniversary_date"
                                value="" />
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="ring_size">Ring Size</label>
                            <input class="form-control" type="text" name="ring_size" value=""
                                maxlength="191" placeholder="Enter ring size" />
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label for="bangle_size">Bangle Size</label>
                            <input class="form-control" type="text" name="bangle_size" value=""
                                maxlength="191" placeholder="Enter bangle size" />
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="bank_name">Bank Name</label>
                            <input class="form-control" type="text" name="bank_name"
                                value=""
                                maxlength="191" placeholder="Enter bank name" />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="account_title">Account Title</label>
                            <input class="form-control" type="text" name="account_title"
                                value=""
                                maxlength="191" placeholder="Enter account title" />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="account_no">Account No</label>
                            <input class="form-control" type="text" name="account_no"
                                value=""
                                maxlength="191" placeholder="Enter account no" />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="reference">Reference</label>
                            <input class="form-control" type="text" name="reference"
                                value=""
                                maxlength="191" placeholder="Enter reference" />
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label for="comment">Comment</label>
                            <input class="form-control" type="text" name="comment"
                                value="" maxlength="191"
                                placeholder="Enter comment" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="customerSave" class="btn btn-primary"
                                    value="create">Save</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
