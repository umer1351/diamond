@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Other Sale</h1>
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
                        @can('other_sale_create')
                        <a class="btn btn-primary btn-md m-1" href="{{ url('other-sale/create') }}"><i
                                class="fa fa-plus text-white mr-2"></i> Add Other Sale</a>
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
                            <div class="col-md-8" id="div_post_other_sale">
                                <a class="btn btn-info" style="color:#fff;" type="button" id="selectAll">Check
                                    All</a>
                                <a class="btn btn-danger" style="color:#fff;" type="button" id="unselectAll">Uncheck
                                    All</a>
                                @can('other_sale_post')
                                <a class="btn btn-primary" style="color:#fff;" type="button" id="post_sale">Post</a>
                                @endcan
                            </div>
                        </div>
                        <div class="row mt-2" id="other_sale_account" style="display: none;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Cash Account<span style="color: red;">*</span> </label>
                                    <select id="cash_account_id" name="cash_account_id" class="form-control" style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Cash
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Revenue Account<span style="color: red;">*</span> </label>
                                    <select id="revenue_account_id" name="revenue_account_id" class="form-control" style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Revenue
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Bank Transfer Account<span style="color: red;">*</span>
                                    </label>
                                    <select id="bank_transfer_account_id" name="bank_transfer_account_id"
                                        class="form-control" style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select
                                            Bank Transfer
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Card Account<span style="color: red;">*</span>
                                    </label>
                                    <select id="card_account_id" name="card_account_id" class="form-control"
                                        style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select
                                            Card
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Advance Account<span style="color: red;">*</span>
                                    </label>
                                    <select id="advance_account_id" name="advance_account_id" class="form-control"
                                        style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select
                                            Advance
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="other_sale_table" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Sr#</th>
                                            <th>Date</th>
                                            <th>Sale No</th>
                                            <th>Customer Name</th>
                                            <th>Total QTY</th>
                                            <th>Total Amount</th>
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
{data: 'other_sale_date', name: 'other_sale_date', orderable: false, searchable: false},
{data: 'other_sale_no', name: 'other_sale_no', orderable: false, searchable: false},
{data: 'customer_name',name: 'customer_name', orderable: false, searchable: false},
{data: 'total_qty',name: 'total_qty', orderable: false, searchable: false},
{data: 'total',name: 'total', orderable: false, searchable: false},
{data: 'posted',name: 'posted', orderable: false, searchable: false},
{data: 'action',name: 'action','sortable': false,searchable: false}",
'route' => 'other-sale/data',
'buttons' => false,
'pageLength' => 50,
'class' => 'other_sale_table',
'variable' => 'other_sale_table',
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
        $("#revenue_account_id").select2();
        $("#bank_transfer_account_id").select2();
        $("#card_account_id").select2();
        $("#advance_account_id").select2();

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
        initDataTableother_sale_table();
        $("#div_post_other_sale").show();
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

    $("#post_sale").click(function() {
        $("#preloader").show();
        if ($("#cash_account_id").find(":selected").val() == "" || $("#cash_account_id").find(":selected")
            .val() == 0) {
            error("Please select sale account!");
            $("#cash_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#revenue_account_id").find(":selected").val() == "" || $("#revenue_account_id").find(":selected")
            .val() == 0) {
            error("Please select revenue account!");
            $("#revenue_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#bank_transfer_account_id").find(":selected").val() == "" || $("#bank_transfer_account_id").find(":selected")
            .val() == 0) {
            error("Please select bank transfer account!");
            $("#bank_transfer_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#card_account_id").find(":selected").val() == "" || $("#card_account_id").find(":selected")
            .val() == 0) {
            error("Please select card account!");
            $("#card_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        if ($("#advance_account_id").find(":selected").val() == "" || $("#advance_account_id").find(":selected")
            .val() == 0) {
            error("Please select advance account!");
            $("#advance_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        var other_sales = [];
        $(".sub_chk:checked").each(function() {

            other_sales.push($(this).val());
        });
        var data = {};
        data.other_sale = other_sales;
        data.cash_account_id = $("#cash_account_id").find(":selected").val();
        data.revenue_account_id = $("#revenue_account_id").find(":selected").val();
        data.bank_transfer_account_id = $("#bank_transfer_account_id").find(":selected").val();
        data.card_account_id = $("#card_account_id").find(":selected").val();
        data.advance_account_id = $("#advance_account_id").find(":selected").val();
        if (other_sales.length <= 0) {
            $("#preloader").hide();
            error("Please select Rows!");
        } else {
            $.ajax({
                url: "{{ url('other-sale/post') }}",
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
                        initDataTableother_sale_table();
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
    $("body").on("click", "#unpost_other_sale", function() {
        $("#preloader").show();
        var sale_id = $(this).data("id");

        $.ajax({
            url: "{{ url('other-sale/unpost') }}/" + sale_id,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",

            success: function(data) {
                if ((data.Success = true)) {
                    $("#preloader").hide();
                    success(data.Message)
                    initDataTableother_sale_table();
                }
            },
            error: function(error) {
                error(error.Message);
                $("#preloader").hide();
            }
        });
    });
    // Delete other sale Code

    $("body").on("click", "#deleteOtherSale", function() {
        var other_sale_id = $(this).data("id");
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
                        url: "{{ url('other-sale/destroy') }}/" + other_sale_id,
                    }).done(function(data) {
                        if ((data.Success = true)) {
                            $("#preloader").hide();
                            success(data.Message)
                            initDataTableother_sale_table();
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
            $("#other_sale_account").show();
        } else {
            $("#other_sale_account").hide();
        }
    });
</script>
@endsection