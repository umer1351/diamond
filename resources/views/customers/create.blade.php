@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Customer</h1>
        @if (isset($customer))
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
                <form action="{{ url('customers/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{ isset($customer) ? $customer->id : '' }}" />
                            <div class="col-md-6 form-group mb-3">
                                <label for="name">Name<span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ isset($customer) ? $customer->name : old('name') }}" maxlength="191"
                                    placeholder="Enter name" required />
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="cnic">CNIC<small class="text-danger">(unique)</small></label>
                                <input class="form-control" type="text" name="cnic"
                                    value="{{ isset($customer) ? $customer->cnic : old('cnic') }}" maxlength="191"
                                    placeholder="Enter CNIC" />
                                @error('cnic')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="contact">Contact<span class="text-danger">**</span></label>
                                <input class="form-control" type="text" name="contact"
                                    value="{{ isset($customer) ? $customer->contact : old('contact') }}" maxlength="191"
                                    placeholder="Enter contact" />
                                @error('contact')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ isset($customer) ? $customer->email : old('email') }}" maxlength="191"
                                    placeholder="Enter email" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="address">Address</label>
                                <input class="form-control" type="text" name="address"
                                    value="{{ isset($customer) ? $customer->address : old('address') }}" maxlength="191"
                                    placeholder="Enter address" />
                            </div>
                            <!-- <div class="col-md-6 form-group mb-3">
                                <label for="account_id">Account (COA)</label>
                                <select class="form-control select2" name="account_id" id="account_id"
                                    style="width: 100%;">
                                    <option value="">--Select Account--</option>
                                    @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}"
                                        {{ isset($customer) && $customer->account_id == $account->id ? 'selected' : '' }}>
                                        {{ $account->code ?? '' }} - {{ $account->name ?? '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div> -->
                            <div class="col-md-3 form-group mb-3">
                                <label for="date_of_birth">Date of Birth</label>
                                <input class="form-control" type="date" name="date_of_birth"
                                    value="{{ isset($customer) ? $customer->date_of_birth : old('date_of_birth') }}" />
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label for="anniversary_date">Anniversary Date</label>
                                <input class="form-control" type="date" name="anniversary_date"
                                    value="{{ isset($customer) ? $customer->anniversary_date : old('anniversary_date') }}" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="ring_size">Ring Size</label>
                                <input class="form-control" type="text" name="ring_size"
                                    value="{{ isset($customer) ? $customer->ring_size : old('ring_size') }}" maxlength="191"
                                    placeholder="Enter ring size" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="bangle_size">Bangle Size</label>
                                <input class="form-control" type="text" name="bangle_size"
                                    value="{{ isset($customer) ? $customer->bangle_size : old('bangle_size') }}" maxlength="191"
                                    placeholder="Enter bangle size" />
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="bank_name">Bank Name</label>
                                <input class="form-control" type="text" name="bank_name"
                                    value="{{ isset($customer) ? $customer->bank_name : old('bank_name') }}" maxlength="191"
                                    placeholder="Enter bank name" />
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="account_title">Account Title</label>
                                <input class="form-control" type="text" name="account_title"
                                    value="{{ isset($customer) ? $customer->account_title : old('account_title') }}" maxlength="191"
                                    placeholder="Enter account title" />
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="account_no">Account No</label>
                                <input class="form-control" type="text" name="account_no"
                                    value="{{ isset($customer) ? $customer->account_no : old('account_no') }}" maxlength="191"
                                    placeholder="Enter account no" />
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="cnic_images">Images </label>
                                <input class="form-control" type="file" name="cnic_images[]" accept=".jpg, .jpeg, .png" multiple />
                                @error('cnic_images')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="reference">Reference</label>
                                <input class="form-control" type="text" name="reference"
                                    value="{{ isset($customer) ? $customer->reference : old('reference') }}" maxlength="191"
                                    placeholder="Enter reference" />
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="comment">Comment</label>
                                <input class="form-control" type="text" name="comment"
                                    value="{{ isset($customer) ? $customer->comment : old('comment') }}" maxlength="191"
                                    placeholder="Enter comment" />
                            </div>
                            @if(isset($customer))
                            <div class="col-md-12 form-group mb-3">
                                <b>Old CNIC Images <small>(if add then change)</small></b><br>
                                @php
                                $images = json_decode($customer->cnic_images, true);
                                @endphp
                                @if(isset($images))
                                @foreach ($images as $image)
                                <a href="{{ asset($image) }}" target="_blank"><img src="{{ asset($image) }}" style="width:100px; height: 100px;" alt="CNIC Image"></a>
                                @endforeach
                                @else
                                <p>No Image</p>
                                @endif
                            </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url('customers') }}" class="btn btn-danger">Cancel</a>
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
        $('#account_id').select2();
    });

    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
@endsection