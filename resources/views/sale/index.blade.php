@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Sale</h1>
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
                        @can('sale_create')
                        <a class="btn btn-primary btn-md m-1" href="{{ url('sale/create') }}"><i
                                class="fa fa-plus text-white mr-2"></i> Add Sale</a>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select id="posted" name="posted" class="form-control">
                                        <option value="">All</option>
                                        <option value="0">Unposted</option>
                                        <option value="1">Posted</option>
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
                        <div class="row">
                            <div class="col-md-12">
                                <hr class="mt-2 mb-2">
                            </div>
                            <div class="col-md-8" id="div_post_sale">
                                <a class="btn btn-info" style="color:#fff;" type="button" id="selectAll">Check
                                    All</a>
                                <a class="btn btn-danger" style="color:#fff;" type="button" id="unselectAll">Uncheck
                                    All</a>
                                @can('sale_post')
                                <a class="btn btn-primary" style="color:#fff;" type="button" id="post_sale">Post</a>
                                @endcan
                            </div>
                        </div>
                        <div class="row mt-2" id="sale_account" style="display: none;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Revenue Account<span style="color: red;">*</span> </label>
                                    <select id="revenue_account_id" name="revenue_account_id" class="form-control"
                                        style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Revenue
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}"
                                            {{ (isset($setting) && $setting->revenue_account_id == $item->id) ? 'selected' : '' }}>
                                            {{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Discount Account<span style="color: red;">*</span> </label>
                                    <select id="discount_account_id" name="discount_account_id" class="form-control"
                                        style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Revenue
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}"
                                            {{ (isset($setting) && $setting->discount_account_id == $item->id) ? 'selected' : '' }}>
                                            {{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sale_table" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Sr#</th>
                                            <th>Date</th>
                                            <th>Sale No</th>
                                            <th>Customer Name</th>
                                            <th>Total QTY</th>
                                            <th>Subtotal</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Status</th>
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
@include('sale/Modal/PaymentModel')
@include('journal_entries/Modal/JVs')
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/momenttimezone/0.5.31/moment-timezone-with-data-2012-2022.min.js">
</script>
<script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
@include('includes.datatable', [
'columns' => "
{data: 'check_box', name: 'check_box', name: 'DT_RowIndex', orderable: false, searchable: false},
{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
{data: 'sale_date', name: 'sale_date', orderable: false, searchable: false},
{data: 'sale_no', name: 'sale_no', orderable: false, searchable: false},
{data: 'customer_name',name: 'customer_name', orderable: false, searchable: false},
{data: 'total_qty',name: 'total_qty', orderable: false, searchable: false},
{data: 'sub_total',name: 'sub_total', orderable: false, searchable: false},
{data: 'discount_amount',name: 'discount_amount', orderable: false, searchable: false},
{data: 'total',name: 'total', orderable: false, searchable: false},
{data: 'total_received',name: 'total_received', orderable: false, searchable: false},
{data: 'posted',name: 'posted', orderable: false, searchable: false},
{data: 'action',name: 'action','sortable': false,searchable: false}",
'route' => 'sale/data',
'buttons' => false,
'pageLength' => 50,
'class' => 'sale_table',
'variable' => 'sale_table',
'datefilter' => true,
'params' => "customer_id:$('#customer_id').val(),posted:$('#posted').val()",
'rowCallback' => ' rowCallback: function (row, data) {
if(data.posted.includes("Unposted"))
$(row).css("background-color", "Pink");
}',
])
<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        $("#cash_account_id").select2();
        $("#discount_account_id").select2();
        $("#revenue_account_id").select2();
        $("#bank_transfer_account_id").select2();
        $("#card_account_id").select2();
        $("#advance_account_id").select2();
        $("#gold_impurity_account_id").select2();

        $("#unselectAll").hide();
        $("#selectAll").click(function() {
            $("input[type=checkbox]").prop('checked', 'checked');
            $("#selectAll").hide();
            $("#unselectAll").show();
            $("#sale_account").show();
        });
        $("#unselectAll").click(function() {
            $("input[type=checkbox]").prop('checked', '');
            $("#selectAll").show();
            $("#unselectAll").hide();
            $("#sale_account").hide();
        })
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
        initDataTablesale_table();
        $("#div_post_sale").show();
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
    //Sale Payment Form

    function getCustomerSaleOrders(customer_id) {
        $("#preloader").show();
        $.ajax({
            url: "{{url('sale-order/by-customer')}}/" + customer_id,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(data) {
                console.log(data);
                var data = data.Data;
                $("#sale_order_id").empty();
                var sale_order =
                    '<option value="" selected>--Select Sale Orders--</option>';
                $.each(data, function(key, value) {
                    sale_order +=
                        '<option value="' +
                        value.id +
                        '">' +
                        value.sale_order_no +
                        " </option>";
                });
                $("#sale_order_id").append(sale_order);

                $("#preloader").hide();
            },
        });
    }
    $("#sale_order_id").on("change", function() {
        var sale_order_id = $("#sale_order_id").find(':selected').val();
        if (sale_order_id > 0) {
            $.ajax({
                url: "{{url('journal-entries/get-sale-order-advance')}}/" + sale_order_id,
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(data) {
                    console.log(data);
                    var data = data.Data;
                    $("#advance_amount").val(data);
                    $("#preloader").hide();
                },
            });
        }
    });
    $("body").on("click", "#createNewPayment", function() {
        $("#saveBtn").val("creating..");
        $("#saveBtn").show();
        $("#sale_id").val($(this).data("sale_id"));
        $("#sale_customer_id").val($(this).data("customer_id"));
        $("#advance_amount").val(0);
        $("#advance_reference").val('');
        $("#cash_amount").val(0);
        $("#cash_reference").val('');
        $("#card_amount").val(0);
        $("#card_reference").val('');
        $("#bank_transfer_amount").val(0);
        $("#bank_transfer_reference").val('');
        $("#modelHeading").html("Sale Payment");
        $("#paymentModel").modal("show");
        getCustomerSaleOrders($(this).data("customer_id"));
        GetSaleDetailById($(this).data("sale_id"));
    });

    function GetSaleDetailById(sale_id) {
        $("#preloader").show();
        $.ajax({
            url: "{{url('sale/get-sale-detail-by-id')}}/" + sale_id,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(data) {
                console.log(data);
                var data = data.Data;
                $("#total_amount").val(data.total);
                $("#preloader").hide();
            },
        });
    }
    $("body").on("keyup", "#advance_amount,#cash_amount,#bank_transfer_amount,#card_amount,#gold_impurity_amount", function(event) {
        var advance_amount = $("#advance_amount").val();
        var cash_amount = $("#cash_amount").val();
        var bank_transfer_amount = $("#bank_transfer_amount").val();
        var card_amount = $("#card_amount").val();
        var gold_impurity_amount = $("#gold_impurity_amount").val();
        var total_paid = (advance_amount * 1) + (cash_amount * 1) + (bank_transfer_amount * 1) + (card_amount * 1) + (gold_impurity_amount * 1);
        var total_amount = $("#total_amount").val();
        var balance = (total_amount*1) - (total_paid*1);
        $("#total_paid").val(total_paid.toFixed(3));
        $("#balance").val(balance.toFixed(3));
    });
    $("body").on("click", "#paymentSave", function(e) {
        e.preventDefault();

        $("#paymentSave").hide();
        $("#preloader").show();
        // Validation logic
        if ($("#sale_id").val() == "") {
            error("Please select sale!");
            $("#sale_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }

        if ($("#sale_customer_id").val() == "") {
            error("Please select customer!");
            $("#sale_customer_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }
        if (
            $("#advance_amount").val() == 0 &&
            $("#cash_amount").val() == 0 &&
            $("#bank_transfer_amount").val() == 0 &&
            $("#card_amount").val() == 0 &&
            $("#gold_impurity_amount").val() == 0
        ) {
            error("Please add amount!");
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }
        if (
            $("#cash_amount").val() > 0 &&
            $("#cash_account_id").find(':selected').val() == ''
        ) {
            error("Please select cash account!");
            $("#cash_account_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }
        if (
            $("#bank_transfer_amount").val() > 0 &&
            $("#bank_transfer_account_id").find(':selected').val() == ''
        ) {
            error("Please select bank transfer account!");
            $("#bank_transfer_account_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }
        if (
            $("#card_amount").val() > 0 &&
            $("#card_account_id").find(':selected').val() == ''
        ) {
            error("Please select card account!");
            $("#card_account_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }

        if (
            $("#gold_impurity_amount").val() > 0 &&
            $("#gold_impurity_account_id").find(':selected').val() == ''
        ) {
            error("Please select gold impurity account!");
            $("#gold_impurity_account_id").focus();
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }

        if (
            $("#balance").val() != 0 &&
            $("#total_amount").val() != $("#total_paid").val()
        ) {
            error("Paid Amount not equal to total amount!");
            $("#preloader").hide();
            $("#paymentSave").show();
            return false;
        }

        // Create FormData object for Ajax
        var formData = new FormData();
        formData.append("sale_id", $("#sale_id").val());
        formData.append("customer_id", $("#sale_customer_id").val());
        formData.append("sale_order_id", $("#sale_order_id").find(':selected').val());
        formData.append("advance_amount", $("#advance_amount").val());
        formData.append("advance_reference", $("#advance_reference").val());
        formData.append("cash_account_id", $("#cash_account_id").find(':selected').val());
        formData.append("cash_amount", $("#cash_amount").val());
        formData.append("cash_reference", $("#cash_reference").val());
        formData.append("bank_transfer_account_id", $("#bank_transfer_account_id").find(':selected').val());
        formData.append("bank_transfer_amount", $("#bank_transfer_amount").val());
        formData.append("bank_transfer_reference", $("#bank_transfer_reference").val());
        formData.append("card_account_id", $("#card_account_id").find(':selected').val());
        formData.append("card_amount", $("#card_amount").val());
        formData.append("card_reference", $("#card_reference").val());
        formData.append("gold_impurity_account_id", $("#gold_impurity_account_id").find(':selected').val());
        formData.append("gold_impurity_amount", $("#gold_impurity_amount").val());
        formData.append("gold_impurity_reference", $("#gold_impurity_reference").val());
        formData.append("total_received", $("#total_paid").val());

        $.ajax({
            url: "{{ url('sale/sale-payment')}}",
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
                    $("#paymentSave").show();
                    $("#paymentModel").modal("hide");
                    initDataTablesale_table();
                } else {
                    error(data.Message);
                    $("#preloader").hide();
                    $("#paymentSave").show();
                }
            },
            error: function(xhr, status, e) {
                error("An error occurred:");
                $("#preloader").hide();
                $("#paymentSave").show();
            },
        });
    });
    $("#post_sale").click(function() {
        $("#preloader").show();

        if ($("#revenue_account_id").find(":selected").val() == "" || $("#revenue_account_id").find(":selected")
            .val() == 0) {
            error("Please select revenue account!");
            $("#revenue_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#discount_account_id").find(":selected").val() == "" || $("#discount_account_id").find(":selected")
            .val() == 0) {
            error("Please select discount account!");
            $("#discount_account_id").focus();
            $("#preloader").hide();
            return false;
        }

        var sales = [];
        $(".sub_chk:checked").each(function() {

            sales.push($(this).val());
        });
        var data = {};
        data.sale = sales;
        data.discount_account_id = $("#discount_account_id").find(":selected").val();
        data.revenue_account_id = $("#revenue_account_id").find(":selected").val();
        if (sales.length <= 0) {
            $("#preloader").hide();
            error("Please select Rows!");
        } else {
            $.ajax({
                url: "{{ url('sale/post-sale') }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: data,
                dataType: "json",

                success: function(data) {
                    if ((data.Success = true)) {
                        $("#preloader").hide();
                        success(data.Message)
                        initDataTablesale_table();
                    } else {
                        error(data.Message);
                        $("#preloader").hide();
                    }
                },
                error: function(error) {
                    error(error.Message);
                    $("#preloader").hide();
                }
            });
        }
    });
    $("body").on("click", "#unpost_sale", function() {
        $("#preloader").show();
        var sale_id = $(this).data("id");

        $.ajax({
            url: "{{ url('sale/unpost-sale') }}/" + sale_id,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",

            success: function(data) {
                if ((data.Success = true)) {
                    $("#preloader").hide();
                    success(data.Message)
                    initDataTablesale_table();
                }
            },
            error: function(error) {
                error(error.Message);
                $("#preloader").hide();
            }
        });
    });
    // Delete sale Code

    $("body").on("click", "#deleteSale", function() {
        var sale_id = $(this).data("id");
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
                        url: "{{ url('sale/destroy') }}/" + sale_id,
                    }).done(function(data) {
                        if ((data.Success = true)) {
                            $("#preloader").hide();
                            success(data.Message)
                            initDataTablesale_table();
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
    $("body").on("click", ".sub_chk", function() {
        if ($("input[type=checkbox]").is(':checked')) {
            $("#sale_account").show();
        } else {
            $("#sale_account").hide();
        }
    });
</script>
@endsection