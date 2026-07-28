@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Supplier/Karigar</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <div class="card text-left">
                    <div class="card-header text-right bg-transparent">
                        @can('suppliers_create')
                            <a class="btn btn-primary btn-md m-1" href="{{ url('suppliers/create') }}" id="createNewProject"><i
                                    class="fa fa-plus text-white mr-2"></i> Add Supplier/Karigar</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="supplier_table" class="table table-striped display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">CNIC</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Balance(PKR)</th>
                                        <th scope="col">Balance(AU)</th>
                                        <th scope="col">Balance($)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of col-->
        </div>
        <!-- end of row-->
    </div>
@endsection
@section('js')
    @include('includes.datatable', [
        'columns' => "
                                     {data: 'name' , name: 'name'},
                                     {data: 'cnic' , name: 'cnic'},
                                     {data: 'contact' , name: 'contact'},
                                     {data: 'company' , name: 'company'},
                                    {data: 'type' , name: 'type' , 'sortable': false , searchable: false},
                                    {data: 'status' , name: 'status' , 'sortable': false , searchable: false},
                                    {data: 'balance' , name: 'balance' , 'sortable': false , searchable: false},
                                    {data: 'balance_au' , name: 'balance_au' , 'sortable': false , searchable: false},
                                    {data: 'balance_dollar' , name: 'balance_dollar' , 'sortable': false , searchable: false},
                                    {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'suppliers/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'supplier_table',
        'variable' => 'supplier_table',
    ])

    <script>
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
        $("body").on("click", "#status", function() {
            var supplier_id = $(this).data("id");
            $.ajax({
                    type: "get",
                    url: "{{ url('suppliers/status') }}/" + supplier_id,
                })
                .done(function(data) {
                    if (data.Success) {
                        successMessage(data.Message);
                        initDataTablesupplier_table();
                    } else {
                        errorMessage(data.Message);
                    }
                })
                .catch(function(err) {
                    errorMessage(err.Message);
                });
        });
        $("body").on("click", "#deleteSupplier", function() {
            var supplier_id = $(this).data("id");
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
                            url: "{{ url('suppliers/destroy') }}/" + supplier_id,
                        })
                        .done(function(data) {
                            if (data.Success) {
                                successMessage(data.Message);
                                initDataTablesupplier_table();
                            } else {
                                errorMessage(data.Message);
                            }
                        })
                        .catch(function(err) {
                            errorMessage(err.Message);
                        });
                }
            });
        });
    </script>
@endsection
