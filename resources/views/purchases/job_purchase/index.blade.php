@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="breadcrumb">
        <h1>Job Purchase</h1>
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
                                    <label for="">Supplier/Karigar</label>
                                    <select id="supplier_id" name="supplier_id" class="form-control">
                                        <option value="">--Select Supplier--</option>
                                        @foreach ($suppliers as $item)
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
                            <div class="col-md-8" id="div_post_job_purchase">
                                <a class="btn btn-info" style="color:#fff;" type="button" id="selectAll">Check
                                    All</a>
                                <a class="btn btn-danger" style="color:#fff;" type="button" id="unselectAll">Uncheck
                                    All</a>
                                @can('job_purchase_post')
                                <a class="btn btn-primary" style="color:#fff;" type="button" id="post">Post</a>
                                @endcan
                            </div>
                        </div>
                        <div class="row mt-2" id="job_purchase_account" style="display: none;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Purchase Account<span style="color: red;">*</span> </label>
                                    <select id="purchase_account_id" name="purchase_account_id" class="form-control" style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Purchase
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}" {{(isset($setting) && $setting->purchase_account_id==$item->id)?'selected':''}}>{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--<div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Recieved AU Account<span style="color: red;">*</span> </label>
                                    <select id="recieved_au_account_id" name="recieved_au_account_id" class="form-control" style="width:100%;">
                                        <option value="0" disabled selected="selected">--Select Recieved
                                            Account--</option>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}" {{(isset($setting) && $setting->recieved_account_id==$item->id)?'selected':''}}>{{ $item->code ?? '' }} -
                                            {{ $item->name ?? '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>--}}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="job_purchase_table" class="display table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Sr#</th>
                                            <th>Date</th>
                                            <th>Job Purchase No</th>
                                            <th>Purchase Order No</th>
                                            <th>Sale Order No</th>
                                            <th>Customer Name</th>
                                            <th>Supplier Name</th>
                                            <th>Total Recieved Wt</th>
                                            <th>Total Amount</th>
                                            <th>Saled</th>
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
{data: 'job_purchase_date', name: 'job_purchase_date', orderable: false, searchable: false},
{data: 'job_purchase_no', name: 'job_purchase_no', orderable: false, searchable: false},
{data: 'purchase_order', name: 'purchase_order', orderable: false, searchable: false},
{data: 'sale_order', name: 'sale_order', orderable: false, searchable: false},
{data: 'customer_name',name: 'customer_name', orderable: false, searchable: false},
{data: 'supplier_name',name: 'supplier_name', orderable: false, searchable: false},
{data: 'total_recieved_au',name: 'total_recieved_au', orderable: false, searchable: false},
{data: 'total',name: 'total', orderable: false, searchable: false},
{data: 'saled',name: 'saled', orderable: false, searchable: false},
{data: 'posted',name: 'posted', orderable: false, searchable: false},
{data: 'action',name: 'action','sortable': false,searchable: false}",
'route' => 'job-purchase/data',
'buttons' => false,
'pageLength' => 50,
'class' => 'job_purchase_table',
'variable' => 'job_purchase_table',
'datefilter' => true,
'params' => "supplier_id:$('#supplier_id').val(),posted:$('#posted').val()",
'rowCallback' => ' rowCallback: function (row, data) {
if(data.posted.includes("Unposted"))
$(row).css("background-color", "Pink");
}',
])
<script>
    $(document).ready(function() {
        $('#supplier_id').select2();
        $("#purchase_account_id").select2();
        $("#recieved_au_account_id").select2();

        $("#unselectAll").hide();
        $("#selectAll").click(function() {
            $("input[type=checkbox]").prop('checked', 'checked');
            $("#selectAll").hide();
            $("#unselectAll").show();
            $("#job_purchase_account").show();
        });
        $("#unselectAll").click(function() {
            $("input[type=checkbox]").prop('checked', '');
            $("#selectAll").show();
            $("#unselectAll").hide();
            $("#job_purchase_account").hide();
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
        initDataTablejob_purchase_table();
        $("#div_post_job_purchase").show();
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

    $("#post").click(function() {
        $("#preloader").show();
        if ($("#purchase_account_id").find(":selected").val() == "" || $("#purchase_account_id").find(":selected")
            .val() == 0) {
            error("Please select purchase account!");
            $("#purchase_account_id").focus();
            $("#preloader").hide();
            return false;
        }
        // if ($("#recieved_au_account_id").find(":selected").val() == "" || $("#recieved_au_account_id").find(":selected")
        //     .val() == 0) {
        //     error("Please select recieved account!");
        //     $("#recieved_au_account_id").focus();
        //     $("#preloader").hide();
        //     return false;
        // }

        var job_purchases = [];
        $(".sub_chk:checked").each(function() {

            job_purchases.push($(this).val());
        });
        var data = {};
        data.job_purchase = job_purchases;
        data.purchase_account_id = $("#purchase_account_id").find(":selected").val();
        // data.recieved_au_account_id = $("#recieved_au_account_id").find(":selected").val();
        if (job_purchases.length <= 0) {
            $("#preloader").hide();
            error("Please select Rows!");
        } else {
            $.ajax({
                url: "{{ url('job-purchase/post') }}",
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
                        initDataTablejob_purchase_table();
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
    $("body").on("click", "#unpost", function() {
        $("#preloader").show();
        var job_purchase_id = $(this).data("id");

        $.ajax({
            url: "{{ url('job-purchase/unpost') }}/" + job_purchase_id,
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            dataType: "json",

            success: function(data) {
                if ((data.Success = true)) {
                    $("#preloader").hide();
                    success(data.Message)
                    initDataTablejob_purchase_table();
                }
            },
            error: function(error) {
                error(error.Message);
                $("#preloader").hide();
            }
        });
    });
    // Delete sale Code

    $("body").on("click", "#deleteJobPurchase", function() {
        var job_purchase_id = $(this).data("id");
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
                        url: "{{ url('job-purchase/destroy') }}/" + job_purchase_id,
                    }).done(function(data) {
                        if ((data.Success = true)) {
                            $("#preloader").hide();
                            success(data.Message)
                            initDataTablejob_purchase_table();
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
            $("#job_purchase_account").show();
        } else {
            $("#job_purchase_account").hide();
        }
    });
</script>
@endsection