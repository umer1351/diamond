@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>{{ __('app.customers') }}</h1>
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
                        @can('customers_create')
                            <a class="btn btn-primary btn-md m-1" href="{{ url('customers/create') }}" id="createNewProject"><i
                                    class="fa fa-plus text-white mr-2"></i> Add Customer</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="customer_table" class="table table-striped display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">CNIC</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Balance</th>
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
                                     {data: 'email' , name: 'email'},
                                    {data: 'address' , name: 'address'},
                                    {data: 'status' , name: 'status' , 'sortable': false , searchable: false},
                                    {data: 'balance' , name: 'balance' , 'sortable': false , searchable: false},
                                    {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'customers/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'customer_table',
        'variable' => 'customer_table',
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
            var customer_id = $(this).data("id");
            $.ajax({
                    type: "get",
                    url: "{{ url('customers/status') }}/" + customer_id,
                })
                .done(function(data) {
                    if (data.Success) {
                        successMessage(data.Message);
                        initDataTablecustomer_table();
                    } else {
                        errorMessage(data.Message);
                    }
                })
                .catch(function(err) {
                    errorMessage(err.Message);
                });
        });
        $("body").on("click", "#deleteCustomer", function() {
            var customer_id = $(this).data("id");
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
                            url: "{{ url('customers/destroy') }}/" + customer_id,
                        })
                        .done(function(data) {
                            if (data.Success) {
                                successMessage(data.Message);
                                initDataTablecustomer_table();
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
