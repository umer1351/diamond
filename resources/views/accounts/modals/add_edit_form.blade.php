<div class="modal fade" id="addEditAccountModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Create Account</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span class="text-dark" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="accountForm" action="{{route('accounts.addEditAccount')}}" method="POST">
                
                    <div class="row">
                        <input  type="hidden" name="id" id="id" value="0">
                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Code:<span class="text-danger">*</span></label>
                            <input  type="text" class="form-control" autocomplete="off" placeholder="Code" name="code" id="code" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Name:<span class="text-danger">*</span></label>
                            <input  type="text" class="form-control" autocomplete="off" placeholder="Name" name="name" id="name" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="code">Opening Balance</label>
                            <input  type="number" class="form-control" autocomplete="off" placeholder="Opening Balance" value="0" name="opening_balance" id="opening_balance">
                        </div>

                        <div class="col-md-12 form-group mb-3">
                            <label for="code">Account Type:<span class="text-danger">*</span></label>
                            <select class="form-control" id="account_type_id" name="account_type_id" required>
                                <option value="" selected disabled><strong>--Select Account Type--</strong></option>
                                @foreach ($account_types as $type)
                                <option value="{{$type->id}}">{{$type->type}}</option>
                                @endforeach
                                
                            </select>
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
