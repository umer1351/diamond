@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
<style>
    @media print {
        body {
            visibility: hidden;
        }

        #section-to-print {
            position: absolute;
            left: 10px;
            top: 10px;
            width: 30mm;
            height: 10mm;
        }

        .print-min-div {
            margin: 10px;
        }
    }

    @page {
        @bottom-right {
            content: counter(page) " of " counter(pages);
        }
    }
</style>
@endsection
@section('content')
<div class="breadcrumb mt-4">
    <h1>Add Stock Taking</h1>
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
            <div class="card">
                <div class="card-header text-right bg-transparent">
                    <a type="button" class="btn  btn-primary m-1" href="{{ url('stock-taking') }}" style="position: absolute;right: 45px;"><i class="nav-icon mr-2 i-File-Horizontal-Text"></i>Back
                        to List</a>
                </div>
                <div class="card-body">
                    {{-- ADD Form  --}}
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Warehouse<span style="color:red;">*</span></label>
                            <select id="stock_warehouse" name="stock_warehouse" class="form-control show-tick" data-live-search="true" required>
                                <option value="" selected="selected" disabled="disabled">--Select Warehouse--
                                </option>
                                @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date<span style="color:red;">*</span></label>
                            <input type="date" id="date" name="date" class="form-control date" required>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary mt-4" id="n_submit">Search</button>
                            <button id="print" class="btn btn-danger mt-4">Print</button>
                            <button class="btn btn-success mt-4" id="export-excel" value="export-excel">Excel</button>
                        </div>
                        <div class="col-md-8 mt-3">
                            <a class="btn btn-info" style="color:#fff;" type="button" id="selectAll">Check
                                All</a>
                            <a class="btn btn-danger" style="color:#fff;" type="button" id="unselectAll">Uncheck All</a>
                        </div>
                    </div>

                    <form id="challan_form_validation" action="#">
                        {{ csrf_field() }}
                        <input type="hidden" name='warehouse' id='stock_n_warehouse'>
                        <input type="hidden" name='date' id='stock_n_date'>


                        <div class="row clearfix" id="printDiv">

                            <table id="exampleStock" class="table  table-striped table-hover js-exportable">
                                <thead>
                                    <tr>
                                        <th>Update</th>
                                        <th style="display:none;"></th>
                                        <th onclick="sortTable(1)" style="text-align:center">
                                            Product Name</th>
                                        <th onclick="sortTable(2)" style="text-align:center">
                                            Till Stock</th>
                                        <th style="text-align:center">New Quantity</th>
                                        <th style="text-align:center">Unit Price</th>
                                        <th style="text-align:center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><b>Total</b></td>
                                        <td></td>
                                        <td></td>
                                        <!-- <td> <input type="text" value="0" disabled id="stock_total" class="form-control text-center" style="font-weight:bold;border:none; background:none;"></td> -->
                                        <td style="display: inline-flex;"><button type="button" id="aqual_button" class="btn btn-primary" style="border: none; border-radius: 3px">SUM</button> <input type="text" value="0" disabled id="total_qty" class="text-right" style="font-weight:bold;border:none; background:none;"></td>
                                        <td></td>
                                        <td><input type="text" value="0" disabled id="total_amount" class="text-right" style="font-weight:bold;border:none; background:none;"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>

                            </table>
                            <div class="col-md-12">
                                <button id="submit" class="btn btn-primary waves-effect">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                    <!-- end of col -->
                </div>
                <!-- end of row -->
            </div>
        </div>
</section>
@endsection
@section('js')
<script>
    var url_local = "{{ url('/') }}";
</script>
<script src="{{ asset('public/assets/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('public/assets/js/datatables.script.js') }}"></script>
<script src="{{ url('stock-taking/js/stock_taking.js') }}"></script>
<script type="text/javascript">
    $("#unselectAll").hide();
    $("#selectAll").click(function() {
        $("input[type=checkbox]").prop('checked', 'checked');
        $("#selectAll").hide();
        $("#unselectAll").show();
    });
    $("#unselectAll").click(function() {
        $("input[type=checkbox]").prop('checked', '');
        $("#selectAll").show();
        $("#unselectAll").hide();
    })

    function error(message) {
        toastr.error(message, 'Error!', {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });
    }

    function success(message) {
        toastr.success(message, 'Success', {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });
    }
    $(document).ready(function() {
        const date = document.getElementById("date");

        // ✅ Using the visitor's timezone
        date.value = formatDate();

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
        date.value = new Date().toISOString().split("T")[0];
    });
    $("body").on("click", "#btnExport", function() {
        html2canvas($('#exampleStock')[0], {

            onrendered: function(canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        fit: [750, 750],
                        width: 400,
                        alignment: 'center'
                    }]
                };
                pdfMake.createPdf(docDefinition).download("Table.pdf");
            }
        });
    });
    $("#print").on("click", function(e) {
        e.preventDefault();

        var date = $('#date').val();
        var warehouse_id = $("#stock_warehouse").find(":selected").val();
        $("#preloader").show();

        if (date == '' || date == 0) {
            error('Date Field Required!');
            $("#preloader").hide();
            return false;
        }
        if (warehouse_id == '' || warehouse_id == 0) {
            error('Warehouse Field Required!');
            $("#preloader").hide();
            return false;
        }
        var data = {
            warehouse_id: warehouse_id,
            date: date

        };
        var url = "{{ url('stock-taking/print') }}?warehouse_id=" + warehouse_id + "&date=" + date;
        window.open(url, '_blank');
        $("#preloader").hide();

    });

    $("#export-excel").on("click", function(e) {
        e.preventDefault();

        var date = $('#date').val();
        var warehouse_id = $("#stock_warehouse").find(":selected").val();
        var exportExcel = $('#export-excel').val();
        $("#preloader").show();

        if (date == '' || date == 0) {
            error('Date Field Required!');
            $("#preloader").hide();
            return false;
        }
        if (warehouse_id == '' || warehouse_id == 0) {
            error('Warehouse Field Required!');
            $("#preloader").hide();
            return false;
        }
        var data = {
            warehouse_id: warehouse_id,
            date: date,
            exportExcel: exportExcel

        };
        var url = "{{ url('stock-taking/print') }}?warehouse_id=" + warehouse_id + "&date=" + date + "&exportExcel=" + exportExcel;
        window.open(url, '_blank');
        $("#preloader").hide();

    });


    function printDiv() {

        var printContents = document.getElementById('printDiv').innerHTML;
        w = window.open();

        w.document.write(printContents);
        w.document.write('<scr' + 'ipt type="text/javascript">' +
            'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');

        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10

        return true;
    }
</script>
@endsection