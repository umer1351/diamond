@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>{{ __('app.settings') }}</h1>
            <ul>
                <li>Update</li>
                <li>Edit</li>
            </ul>
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
                    <form action="{{ url('company-setting/store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="id" value="{{ isset($company_setting) ? $company_setting->id : '' }}" />
                                <div class="col-md-6 form-group mb-3">
                                    <label for="purchase_account_id">Purchase Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="purchase_account_id" id="purchase_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->purchase_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="sale_account_id">Sale Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="sale_account_id" id="sale_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->sale_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="cash_account_id">Cash Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="cash_account_id" id="cash_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->cash_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="card_account_id">Card Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="card_account_id" id="card_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->card_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="revenue_account_id">Revenue Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="revenue_account_id" id="revenue_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->revenue_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="bank_account_id">Bank Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="bank_account_id" id="bank_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->bank_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                    <label for="customer_account_id">Customer Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="customer_account_id" id="customer_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->customer_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="supplier_account_id">Supplier/Karigar Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="supplier_account_id" id="supplier_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->supplier_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="recieving_account_id">Recieving Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="recieving_account_id" id="recieving_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->recieving_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="discount_account_id">Discount Account <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="discount_account_id" id="discount_account_id"
                                        style="width: 100%;">
                                        <option value="">--Select Account--</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ isset($company_setting) && $company_setting->discount_account_id == $account->id ? 'selected' : '' }}>
                                                {{ $account->code ?? '' }} - {{ $account->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
            $('#purchase_account_id').select2();
            $('#sale_account_id').select2();
            $('#cash_account_id').select2();
            $('#revenue_account_id').select2();
            $('#bank_account_id').select2();
            $('#card_account_id').select2();
            $('#customer_account_id').select2();
            $('#supplier_account_id').select2();
            $('#recieving_account_id').select2();
            $('#discount_account_id').select2();
        });
    </script>
@endsection
