@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection
@section('content')
    <div class="breadcrumb mt-4">
        <h1>Stock Taking</h1>

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
                        @can('stock_taking_create')
                            <a class="btn btn-primary btn-md m-1" href="{{ url('stock-taking/create') }}"><i
                                    class="i-Add text-white mr-2"></i> Stock Taking</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <h4 class="card-inside-title mt-2">Filters</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Date:<span class="text-danger">*</span></label>
                                    <input type="date" id="stock_date" name="stock_date" class="form-control date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Warehouse</label>
                                    <select id="warehouse_id" name="warehouse_id" class="form-control">
                                        <option value="">--Select Warehouse--</option>
                                        @foreach ($warehouses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select id="is_opening_stock" name="is_opening_stock" class="form-control">
                                        <option value="">All</option>
                                        <option value="0">Stock Taking</option>
                                        <option value="1">Opening Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <button class="btn btn-primary waves-effect" id="search_button"
                                        type="submit">Search</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr class="mt-2 mb-2">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="stock_taking_table" class="table display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Taking Date</th>
                                        <th>Warehouse</th>
                                        <th>Type</th>
                                        <th>Creation Date</th>
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
@endsection
@section('js')
    <script src="{{ asset('js/common-methods/toaster.js') }}" type="module"></script>
    <script src="{{ asset('js/common-methods/http-requests.js') }}" type="module"></script>
    <script src="{{ asset('/assets/js/vendor/datatables.min.js') }}"></script>
    @include('includes.datatable', [
        'columns' => "
        {data: 'stock_date' , name: 'stock_date'},
        {data: 'warehouse' , name: 'warehouse' , 'sortable': false , searchable: false},
        {data: 'type' , name: 'type' , 'sortable': false , searchable: false},
        {data: 'created_at' , name: 'created_at', 'sortable': false , searchable: false},
        {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'stock-taking/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'stock_taking_table',
        'variable' => 'stock_taking_table',
        'params' =>
            "stock_date:$('#stock_date').val(),warehouse_id:$('#warehouse_id').val(),is_opening_stock:$('#is_opening_stock').val()",
    ])

    <script>
        $(document).ready(function() {
            $('#warehouse_id').select2();
            $('#is_opening_stock').select2();

            const stock_date = document.getElementById("stock_date");

            // ✅ Using the visitor's timezone
            stock_date.value = formatDate();

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
            stock_date.value = new Date().toISOString().split("T")[0];

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
        $('#search_button').click(function() {
            initDataTablestock_taking_table();
        });
        // Delete Stock Code

        $("body").on("click", "#deleteStockTaking", function() {
            var stock_taking_id = $(this).data("id");
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
                            url: "{{ url('stock-taking/destroy') }}/" + stock_taking_id,
                        }).done(function(data) {
                            successMessage(data.Message);
                            initDataTablestock_taking_table();
                        })
                        .catch(function(err) {
                            errorMessage(err.Message);
                        });
                }
            });
        });
    </script>
@endsection
