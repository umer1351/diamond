@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Supplier/Karigar</h1>
            @if (isset($supplier))
                <ul>
                    <li>Update</li>
                    <li>Edit</li>
                </ul>
            @else
                <ul>
                    <li>Create</li>
                    <li>Add</li>
                </ul>
            @endif
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <form action="{{ url('suppliers/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{ isset($supplier) ? $supplier->id : '' }}" />
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name">Name<span class="text-danger">*</span> </label>
                                    <input class="form-control" type="text" name="name"
                                        value="{{ isset($supplier) ? $supplier->name : old('name') }}" maxlength="191"
                                        placeholder="Enter name" required />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="cnic">CNIC</label>
                                    <input class="form-control" type="text" name="cnic"
                                        value="{{ isset($supplier) ? $supplier->cnic : old('cnic') }}" maxlength="191"
                                        placeholder="Enter CNIC" />
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="contact">Contact<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="contact"
                                        value="{{ isset($supplier) ? $supplier->contact : old('contact') }}" maxlength="191"
                                        placeholder="Enter contact" required />
                                    @error('contact')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="company">Company</label>
                                    <input class="form-control" type="text" name="company"
                                        value="{{ isset($supplier) ? $supplier->company : old('company') }}" maxlength="191"
                                        placeholder="Enter company" />
                                    @error('company')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="type">Type<span class="text-danger">*</span> </label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="" selected disabled>--Select Type--</option>
                                        <option value="0"
                                            @if (isset($supplier)) {{ $supplier->type == 0 ? 'selected' : '' }} @endif>
                                            Supplier</option>
                                        <option value="1"
                                            @if (isset($supplier)) {{ $supplier->type == 1 ? 'selected' : '' }} @endif>
                                            Karigar</option>
                                        <option value="2"
                                            @if (isset($supplier)) {{ $supplier->type == 2 ? 'selected' : '' }} @endif>
                                            Both</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- <div class="col-md-4 form-group mb-3">
                                    <label for="account_id">Account(PKR)<span class="text-danger">*</span> </label>
                                    <select class="form-control select2" name="account_id" id="account_id" required
                                        style="width: 100%;">
                                        <option value="" selected disabled>--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($supplier) && $supplier->account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> -->
                                <!-- <div class="col-md-4 form-group mb-3">
                                    <label for="account_au_id">Account(AU)</label>
                                    <select class="form-control select2" name="account_au_id" id="account_au_id"
                                        style="width: 100%;">
                                        <option value="" selected disabled>--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($supplier) && $supplier->account_au_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <!-- <div class="col-md-4 form-group mb-3">
                                    <label for="account_dollar_id">Account($)</label>
                                    <select class="form-control select2" name="account_dollar_id" id="account_dollar_id"
                                        style="width: 100%;">
                                        <option value="" selected disabled>--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($supplier) && $supplier->account_dollar_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_dollar_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> -->
                                <div class="col-md-4 form-group mb-3">
                                    <label for="gold_waste">Waste/Tola<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="gold_waste" onkeypress="return isNumberKey(event)"
                                        value="{{ isset($supplier) ? $supplier->gold_waste : old('gold_waste') }}"
                                        maxlength="50" placeholder="Enter waste/tola" required />
                                    @error('gold_waste')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="stone_waste">Stone Waste<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="stone_waste" onkeypress="return isNumberKey(event)"
                                        value="{{ isset($supplier) ? $supplier->stone_waste : old('stone_waste') }}"
                                        maxlength="50" placeholder="Enter stone waste" required/>
                                    @error('stone_waste')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="kaat">Kaat<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="kaat" onkeypress="return isNumberKey(event)"
                                        value="{{ isset($supplier) ? $supplier->kaat : old('kaat') }}" maxlength="50"
                                        placeholder="Enter kaat" required/>
                                    @error('kaat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="bank_name">Bank Name</label>
                                    <input class="form-control" type="text" name="bank_name"
                                        value="{{ isset($supplier) ? $supplier->bank_name : old('bank_name') }}" maxlength="191"
                                        placeholder="Enter bank name" />
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="account_title">Account Title</label>
                                    <input class="form-control" type="text" name="account_title"
                                        value="{{ isset($supplier) ? $supplier->account_title : old('account_title') }}" maxlength="191"
                                        placeholder="Enter account title" />
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="account_no">Account No</label>
                                    <input class="form-control" type="text" name="account_no"
                                        value="{{ isset($supplier) ? $supplier->account_no : old('account_no') }}" maxlength="191"
                                        placeholder="Enter account no" />
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ url('suppliers') }}" class="btn btn-danger">Cancel</a>
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- end of main-content -->
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#type').select2();
            $('#account_id').select2();
            $('#account_au_id').select2();
            $('#account_dollar_id').select2();
        });

        function isNumberKey(evt) {
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>
@endsection
