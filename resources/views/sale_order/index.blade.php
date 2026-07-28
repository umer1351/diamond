@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Sale Order</h1>
        <ul>
            <li>List</li>
            <li>All</li>
        </ul>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
    @endif
    <!-- end of row -->
    <section class="contact-list">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card text-left">
                    <div class="card-header text-right bg-transparent">
                        @can('sale_order_create')
                        <a class="btn btn-primary btn-md m-1" href="{{ url('sale-order/create') }}"><i
                                class="fa fa-plus text-white mr-2"></i> Add Sale Order</a>
                        @endcan
                    </div>
                    <div class="card-body">

                        <h4 class="card-inside-title mt-2">Filters</h4>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">From:<span class="text-danger">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control date">
                                </div>
                            </div>
                            <div class="col-md-2" id="div_end_date">
                                <div class="form-group">
                                    <label for="">To:<span class="text-danger">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control date">
                                </div>
                            </div>
                            <div class="col-md-3" id="div_vendor">
                                <div class="form-group">
                                    <label for="">Customer</label>
                                    <select id="customer_id" name="customer_id" class="form-control">
                                        <option value="">--Select Customer--</option>
                                        @foreach ($customers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <button class="btn btn-primary waves-effect" id="search_button"
                                        type="submit">Search</button>
                                </div>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sale_order_table" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Sale Order No</th>
                                            <th>Customer Name</th>
                                            <th>Gold Rate</th>
                                            <th>Rate Type</th>
                                            <th>Warehouse</th>
                                            <th>Total QTY</th>
                                            <th>Delivery Date</th>
                                            <th>Advance</th>
                                            <th>Purchase</th>
                                            <th>Complete</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end of col -->
                </div>
                <!-- end of row -->
            </div>
        </div>
    </section>
</div>
@include('sale_order/Modal/CustomerPayment')
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/momenttimezone/0.5.31/moment-timezone-with-data-2012-2022.min.js">
</script>
<script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
@include('includes.datatable', [
'columns' => "
{data: 'sale_order_date', name: 'sale_order_date'},
{data: 'sale_order_no', name: 'sale_order_no', orderable: false, searchable: false},
{data: 'customer_name',name: 'customer_name', orderable: false, searchable: false},
{data: 'gold_rate',name: 'gold_rate'},
{data: 'gold_rate_type',name: 'gold_rate_type', orderable: false, searchable: false},
{data: 'warehouse_name',name: 'warehouse_name', orderable: false, searchable: false},
{data: 'total_qty',name: 'total_qty'},
{data: 'delivery_date',name: 'delivery_date'},
{data: 'advance',name: 'advance', orderable: false, searchable: false},
{data: 'is_purchased',name: 'is_purchased', orderable: false, searchable: false},
{data: 'is_complete',name: 'is_complete', orderable: false, searchable: false},
{data: 'action',name: 'action','sortable': false,searchable: false}",
'route' => 'sale-order/data',
'buttons' => false,
'pageLength' => 50,
'class' => 'sale_order_table',
'variable' => 'sale_order_table',
'datefilter' => true,
'params' => "customer_id:$('#customer_id').val()",
])
<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        $('#recieving_account_id').select2();

        const startDate = document.getElementById("start_date");
        const endDate = document.getElementById("end_date");

        // ✅ Using the visitor's timezone
        startDate.value = formatDate();
        endDate.value = formatDate();

        console.log(formatDate());

        function padTo2Digits(num) {
            return num.toString().padStart(2, "0");
        }

        function formatDate(date = new Date()) {
            return [
                padTo2Digits(date.getMonth() + 1),
                padTo2Digits(date.getDate()),
                date.getFullYear(),
            ].join("/");
        }
        startDate.value = new Date().toISOString().split("T")[0];
        endDate.value = new Date().toISOString().split("T")[0];


    });

    function isNumberKey(evt) {
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
<script type="text/javascript">
    $('#search_button').click(function() {
        $("#preloader").show();
        initDataTablesale_order_table();
        $("#preloader").hide();
    });

    function error(message) {
        toastr.error(message, "Error!", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });
    }

    function success(message) {
        toastr.success(message, "Success!", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });
    }
    //Customer Payment Form
    $("body").on("click", "#createNewPayment", function() {
        $("#saveBtn").val("creating..");
        $("#saveBtn").show();
        $("#sale_order_id").val($(this).data("sale_order_id"));
        $("#sale_order_customer_id").val($(this).data("customer_id"));
        $("#amount").val(0);
        $("#reference").val('');
        $("#modelHeading").html("Sale Order Advance");
        $("#ajaxModel").modal("show");
    });
    $("body").on("click", "#submit", function(e) {
        e.preventDefault();

        $("#preloader").show();
        // Validation logic
        if ($("#sale_order_id").val() == "") {
            error("Please select sale order!");
            $("#sale_order_id").focus();
            $("#preloader").hide();
            return false;
        }

        if ($("#sale_order_customer_id").val() == "") {
            error("Please select customer!");
            $("#sale_order_customer_id").focus();
            $("#preloader").hide();
            return false;
        }

        if (
            $("#amount").val() == "" ||
            $("#amount").val() == 0
        ) {
            error("Please add amount!");
            $("#amount").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#recieving_account_id").find(':selected').val() == "" || $("#recieving_account_id").find(':selected').val() == 0) {
            error("Please select recieving account!");
            $("#recieving_account_id").focus();
            $("#preloader").hide();
            return false;
        }

        // Create FormData object for Ajax
        var formData = new FormData();
        formData.append("id", $("#id").val());
        formData.append("sale_order_id", $("#sale_order_id").val());
        formData.append("customer_id", $("#sale_order_customer_id").val());
        formData.append("recieving_account_id", $("#recieving_account_id").find(':selected').val());
        formData.append("amount", $("#amount").val());
        formData.append("reference", $("#reference").val());

        $.ajax({
            url: "{{ url('/customer-payment/advance')}}",
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                if (data.Success) {
                    success(data.Message);
                    $("#preloader").hide();
                    $("#submit").prop("disabled", true);
                    $("#ajaxModel").modal("hide");
                    initDataTablesale_order_table();
                } else {
                    error(data.Message);
                    $("#preloader").hide();
                }
            },
            error: function(xhr, status, e) {
                error("An error occurred:");
                $("#preloader").hide();
            },
        });
    });
    // Delete other sale Code

    $("body").on("click", "#deleteSaleOrder", function() {
        var sale_order_id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                        type: "get",
                        url: "{{ url('sale-order/destroy') }}/" + sale_order_id,
                    }).done(function(data) {
                        if ((data.Success = true)) {
                            $("#preloader").hide();
                            success(data.Message)
                            initDataTablesale_order_table();
                        } else {
                            error(data.Message);
                        }

                    })
                    .catch(function(err) {
                        error(err.Message);
                    });
            }
        });
    });
</script>
@endsection