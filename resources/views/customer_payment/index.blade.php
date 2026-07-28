@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Customer Payments</h1>

            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>

        <div class="separator-breadcrumb border-top"></div>
        <!-- end of row -->
        <section class="contact-list">
            <div class="row">

                <div class="col-md-12 mb-4">

                    <div class="card text-left">
                        <div class="card-header text-right bg-transparent">
                            @can('customer_payment_create')
                            <a type="button" href="javascript:void(0)" id="createNewPayment"
                                class="btn btn-primary btn-md m-1"><i class="fa fa-plus text-white mr-2"></i> Add Payment</a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <b>Filter</b>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Start Date:<span class="text-danger">*</span></label>
                                        <div class="form-line">
                                            <input type="date" id="start_date" name="start_date" class="form-control"
                                                required value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">End Date:<span class="text-danger">*</span></label>
                                        <div class="form-line">
                                            <input type="date" id="end_date" name="end_date" class="form-control"
                                                required value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Customer:</label>
                                        <div class="form-line">
                                            <select name="customer" class="form-control" id="customer">
                                                <option value="">All</option>
                                                @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group">
                                        <button class="btn btn-primary" id="search">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr style="margin:0px;">
                                </div>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="customer_payment_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Currency</th>
                                            <th>Tax(%)</th>
                                            <th>Tax Amount</th>
                                            <th>Sub Total</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- end of col -->
                    </div>
                    <!-- end of row -->
                </div>
            </div>
        </section>
    </div>
    @include('customer_payment/Modal/CustomerPaymentForm')

    @include('journal_entries/Modal/JVs')
@endsection
@section('js')
    <script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
    <script src="{{ asset('js/common-methods/http-requests.js') }}" type="module"></script>
    <script src="{{ url('customer-payment/js/CustomerPaymentForm.js') }}" type="module"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
        $(document).on('click', '#search', function() {
            initDataTablecustomer_payment_table();
        });
    </script>
    @include('includes.datatable', [
        'columns' => "
        {data: 'customer' , name: 'customer'},
        {data: 'date' , name: 'date'},
        {data: 'currency' , name: 'currency'},
        {data: 'tax' , name: 'tax'},
        {data: 'tax_amount' , name: 'tax_amount'},
        {data: 'sub_total' , name: 'sub_total'},
        {data: 'total' , name: 'total'},
        {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'customer-payment/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'customer_payment_table',
        'variable' => 'customer_payment_table',
        'datefilter' => true,
        'params' => "customer_id:$('#customer').val()",
    ])

    <script>
        $("body").on("click", "#Ref", function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            var jvs = $(this).data("filter");
            $("#preloader").show();

            $.ajax({
                type: "get",
                url: "{{ url('journal-entries/all-jvs') }}?" + jvs,
            }).done(function(response) {
                $('#result').html('');
                $('#result').html(response);
                $("#jvs_modalHeading").html("Journal Entry");
                $("#jvs_modal").modal("show");
                $("#preloader").hide();
            });
        });
    </script>
@endsection
