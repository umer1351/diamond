@extends('layouts.master')
@section('css')
@endsection
@section('content')
<div class="breadcrumb mt-4">
    <h1>Product Ledger Report</h1>
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
                    <form id="form_filter" name="form" method="GET" action="{{ url('reports/get-stock-ledger-report') }}" target="_blank">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Start Date:<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="date" id="start_date" name="start_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">End Date:<span class="text-danger">*</span></label>
                                    <div class="form-line">
                                        <input type="date" id="end_date" name="end_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Product:<span class="text-danger">*</span></label>
                                    <select class="form-control" id="other_product_id" name="other_product_id">
                                        <option value="0">All</option>
                                        @foreach ($other_products as $item)
                                        <option value="{{ $item->id }}" data-id="{{ $item->name }}">
                                            {{ $item->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Warehouse:<span class="text-danger">*</span></label>
                                    <select class="form-control" id="warehouse_id" name="warehouse_id" required>
                                        <option value="0">All</option>
                                        @foreach ($warehouses as $item)
                                        <option value="{{ $item->id }}" data-id="{{ $item->name }}">
                                            {{ $item->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <button class="btn btn-primary" id="search">Search</button>
                                    <button class="btn btn-info" type="submit">Print</button>

                                </div>
                            </div>
                        </div>
                        <div class="result">

                        </div>
                    </form>
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

        $("#other_product_id").select2();
        $("#warehouse_id").select2();
    });
    var url_local = "{{ url('/') }}";

    $("#search").on("click", function(e) {
        e.preventDefault();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var other_product_id = $('#other_product_id').val();
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
        if (start_date > end_date) {
            errorMessage('Start Date shouldnot be previous than End Date!');
            return false;
        }
        var data = {
            start_date: start_date,
            end_date: end_date,
            other_product_id: other_product_id,
            warehouse_id: warehouse_id
        };

        $.ajax({
            type: 'GET',
            url: "{{ url('reports/get-preview-product-ledger-report') }}",
            data: data,
        }).done(function(response) {
            $('.result').html('');
            $('.result').html(response);
            $("#preloader").hide();
        });
    });
</script>
@endsection