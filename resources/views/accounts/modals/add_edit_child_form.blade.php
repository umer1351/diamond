<div class="modal fade" id="addEditAccountChildModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Create Account</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span class="text-dark" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="accountChildForm" action="{{route('accounts.addEditAccount')}}" method="POST">

                    <div class="row">
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="parent_id" id="parent_id" value="0">
                        <input type="hidden" name="account_type_id" id="account_type_id" value="0">
                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Code:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" placeholder="Code" name="code" id="code" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Name:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" autocomplete="off" placeholder="Name" name="name" id="name" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Opening Balance</label>
                            <input type="number" class="form-control" autocomplete="off" placeholder="Opening Balance" value="0" name="opening_balance" id="opening_balance">
                        </div>
                        <div class="form-group col-md-4 mb-3 ml-2">
                            <label class="switch switch-primary mr-2"><input type="checkbox" name="is_cash_account" id="is_cash_account" value="1"><span class="slider"></span> Is Cash Account</label>
                        </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>