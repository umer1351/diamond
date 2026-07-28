@extends('layouts.master')
@section('content')
<div class="breadcrumb mt-4">
    <h1>Product Consumption</h1>
    <ul>
        <li>Report</li>
        <li>View</li>
    </ul>
</div>
<div class="separator-breadcrumb border-top"></div>
@if(session()->has('error'))
<div class="alert alert-danger">
    {{ session()->get('error') }}
</div>
@endif
<!-- end of row -->
<section class="contact-list">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-header bg-transparent">
                    <h4 class="card-inside-title">Filters</h4>
                </div>

                <div class="card-body">
                    <form id="form_filter" name="form" method="GET" action="{{ url('reports/get-product-consumption-report') }}" target="_blank">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Warehouse:<span class="text-danger">*</span> </label>
                                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                                        <option value="">All</option>
                                        @foreach ($warehouses as $item)
                                        <option value="{{ $item->id }}" data-id="{{ $item->name??'' }}">
                                            {{ $item->name??'' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Start Date:<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="date" id="start_date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">End Date:<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="date" id="end_date" name="end_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 pr-0">
                                <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                <button class="btn btn-primary btn-block" id="search">Search</button>
                            </div>

                            <div class="col-md-2 pr-0">
                                <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                <button type='submit' class="btn btn-danger btn-block" name="export-pdf" value="export-pdf">Export
                                    Pdf</button>
                            </div>

                            <div class="col-md-2 pr-0">
                                <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                <button type='submit' class="btn btn-success btn-block" name="export-excel" value="export-excel">Export
                                    Excel</button>
                            </div>

                            <div class="col-md-2 pr-0">
                                <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                <button type='submit' class="btn btn-info btn-block" id="print-report" name="print-report" value="print-report">
                                    Print</button>
                            </div>

                        </div>
                    </form>
                    <div class="result">

                    </div>
                </div>
                <!-- end of col -->
            </div>
            <!-- end of row -->
        </div>
    </div>
</section>
@endsection
@section('js')
<script type="text/javascript">
    function errorMessage(message) {

        toastr.error(message, "Error", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });

    }

    $(document).ready(function() {

        $("#warehouse_id").select2();
    });
    var url_local = "{{ url('/') }}";

    $("#search").on("click", function(e) {
        e.preventDefault();

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var warehouse_id = $('#warehouse_id').val();
        $("#preloader").show();
        if (start_date == '' || start_date == 0) {
            errorMessage('Start Date Field Required!');
            $("#preloader").hide();
            return false;
        }
        if (end_date == '' || end_date == 0) {
            errorMessage('End Date Field Required!');
            $("#preloader").hide();
            return false;
        }

        var data = {
            warehouse_id: warehouse_id,
            start_date: start_date,
            end_date: end_date
        };

        $.ajax({
            type: 'GET',
            url: "{{ url('reports/get-preview-product-consumption-report') }}",
            data: data,
        }).done(function(response) {
            $('.result').html('');
            $('.result').html(response);
            $("#preloader").hide();
        });
    });

    $("#print-report").on("click", function(e) {
        e.preventDefault();

        var divToPrint = document.getElementsByClassName('result')[0];

        var newWin = window.open('', 'Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

        newWin.document.close();

        // setTimeout(function(){newWin.close();},10);
    });
</script>
@endsection