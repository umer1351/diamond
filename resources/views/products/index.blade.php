@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Finished Products</h1>
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
        @if(session('import_errors'))
            <div class="alert alert-warning">
                <strong>Skipped rows:</strong>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $importError)
                        <li>{{ $importError }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-header bg-transparent">
                            @can('products_create')
                                <form class="form-inline float-left" method="post" action="{{ route('products.import-csv') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input class="form-control form-control-sm mr-2" type="file" name="csv_file" accept=".csv,text/csv" required>
                                    <button class="btn btn-outline-primary btn-sm mr-2" type="submit">
                                        <i class="fa fa-upload mr-1"></i> Import CSV
                                    </button>
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('products.import-sample') }}">
                                        <i class="fa fa-download mr-1"></i> Sample
                                    </a>
                                </form>
                                <small class="text-muted d-block mt-2">Use `category_name` for category mapping, `product_name` for the finished item, and `image_filename` for files already placed in public/pictures.</small>
                            @endcan
                            <div class="text-right">
                                @can('products_create')
                                    <a class="btn btn-primary btn-md m-1" href="{{ url('products/create') }}">
                                        <i class="fa fa-plus text-white mr-2"></i> Add Finished Product
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="product_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Tag No</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Warehouse</th>
                                            <th>Gross Wt</th>
                                            <th>Net Wt</th>
                                            <th>Total Amount</th>
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
@endsection
@section('js')
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
    </script>
    @include('includes.datatable', [
        'columns' => "
        {data: 'tag_no' , name: 'tag_no'},
        {data: 'product' , name: 'product', orderable: false, searchable: false},
        {data: 'category' , name: 'category', orderable: false, searchable: false},
        {data: 'warehouse' , name: 'warehouse', orderable: false, searchable: false},
        {data: 'gross_weight' , name: 'gross_weight'},
        {data: 'net_weight' , name: 'net_weight'},
        {data: 'total_amount' , name: 'total_amount'},
        {data: 'status' , name: 'status' , 'sortable': false , searchable: false},
        {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'products/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'product_table',
        'variable' => 'product_table',
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
            var product_id = $(this).data("id");
            $.ajax({
                type: "get",
                url: "{{ url('products/status') }}/" + product_id,
            })
            .done(function (data) {
                if (data.Success) {
                    successMessage(data.Message);
                    initDataTableproduct_table();
                } else {
                    errorMessage(data.Message);
                }
            })
            .catch(function (err) {
                errorMessage(err.Message || err.message || 'Request failed');
            });
        });

        $("body").on("click", "#deleteProduct", function () {
            var product_id = $(this).data("id");
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
                        url: "{{ url('products/destroy') }}/" + product_id,
                    })
                    .done(function (data) {
                        if (data.Success) {
                            successMessage(data.Message);
                            initDataTableproduct_table();
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
@endsection
