@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Categories</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-header text-right bg-transparent">
                            @can('products_create')
                                <a class="btn btn-primary btn-md m-1" href="javascript:void(0)" id="createNewProductCategory">
                                    <i class="fa fa-plus text-white mr-2"></i> Add Category
                                </a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="product_category_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Prefix</th>
                                            <th>Image</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('product_category/Modal/ProductCategoryForm')
@endsection
@section('js')
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
    </script>
    @include('includes.datatable', [
        'columns' => "
        {data: 'name' , name: 'name'},
        {data: 'prefix' , name: 'prefix'},
        {data: 'image' , name: 'image', orderable: false, searchable: false},
        {data: 'stock' , name: 'stock'},
        {data: 'status' , name: 'status' , 'sortable': false , searchable: false},
        {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'product-categories/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'product_category_table',
        'variable' => 'product_category_table',
    ])
    <script>
        function errorMessage(message) {
            toastr.error(message, 'Error', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                timeOut: 2000,
            });
        }

        function successMessage(message) {
            toastr.success(message, 'Success', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                timeOut: 2000,
            });
        }

        $("body").on("click", "#status", function () {
            var product_category_id = $(this).data("id");
            $.ajax({
                type: "get",
                url: "{{ url('product-categories/status') }}/" + product_category_id,
            })
            .done(function (data) {
                if (data.Success) {
                    successMessage(data.Message);
                    initDataTableproduct_category_table();
                } else {
                    errorMessage(data.Message);
                }
            })
            .catch(function (err) {
                errorMessage(err.Message || err.message || 'Request failed');
            });
        });

        $("body").on("click", "#deleteProductCategory", function () {
            var product_category_id = $(this).data("id");
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
                        url: "{{ url('product-categories/destroy') }}/" + product_category_id,
                    })
                    .done(function (data) {
                        if (data.Success) {
                            successMessage(data.Message);
                            initDataTableproduct_category_table();
                        } else {
                            errorMessage(data.Message);
                        }
                    })
                    .catch(function (err) {
                        errorMessage(err.Message || err.message || 'Request failed');
                    });
                }
            });
        });
    </script>
    <script src="{{ url('product-categories/js/ProductCategoryForm.js') }}" type="module"></script>
@endsection
