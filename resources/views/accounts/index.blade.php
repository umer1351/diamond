@extends('layouts.master')
@section('css')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Chart of Accounts</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <div class="card text-left">
                    <div class="card-header text-right bg-transparent">

                        @can('accounts_create')
                            <a type="button" href="#" id="openAddEditAccountModal" class="btn btn-primary btn-md m-1">
                                <i class="fa fa-plus text-white mr-2"></i> Add Account Head</a>
                        @endcan
                    </div>

                    <div class="card-body" id="appendMainAccounts">
                        
                    </div>

                </div>

            </div>

        </div>

        </section>

        @include('accounts.modals.add_edit_child_form')
        @include('accounts.modals.add_edit_form')
    @endsection

    @section('js')
        <script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
        <script src="{{ asset('js/common-methods/http-requests.js') }}" type="module"></script>

        <script src="{{ url('/accounts/js/accounts.js') }}" type="module"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    @endsection
