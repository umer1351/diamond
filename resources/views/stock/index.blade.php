@extends('layouts.master')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection
@section('content')
<div class="breadcrumb mt-4">
    <h1>Stock</h1>

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
                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Filter</b>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="warehouse_id">Warehouse<span style="color:red;">*</span></label>
                                <select name="warehouse_id" id="warehouse_id" class="form-control">
                                    @foreach ($warehouses as $item)
                                    <option value="{{$item->id}}">{{$item->name??''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr style="margin:0px;">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="stock_table" class="table display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Warehouse</th>
                                    <th>Stock</th>
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
@endsection
@section('js')
@include('includes.datatable', [
'columns' => "
{data: 'name' , name: 'name'},
{data: 'unit' , name: 'unit'},
{data: 'warehouse' , name: 'warehouse'},
{data: 'stock' , name: 'stock'},",
'route' => 'stock/data',
'buttons' => false,
'pageLength' => 10,
'class' => 'stock_table',
'variable' => 'stock_table',
'params' => "warehouse_id:$('#warehouse_id').val()"
])

<script>
    $(document).ready(function() {
        $('#warehouse_id').select2();

    });

    function errorMessage(message) {

        toastr.error(message, "Error", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });

    }

    function successMessage(message) {

        toastr.success(message, "Success", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3,
        });

    }
    $(document).on('change', '#warehouse_id', function() {
        $("#preloader").show();
        initDataTablestock_table();
        $("#preloader").hide();
    });
</script>
@endsection