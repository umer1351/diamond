@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Gold Chart</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <div class="card text-left">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped display font-weight-bold" style="width:100%">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="text-center">Carat</th>
                                        <th class="text-center">% Gold</th>
                                        <th class="text-center">% Impurity</th>
                                        <th class="text-center">Ratti of Gold</th>
                                        <th class="text-center">Ratti of Impurity</th>
                                        <th class="text-center">Rate/Tola</th>
                                        <th class="text-center">Rate/Gram</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 24; $i >= 1; $i--)
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td class="text-center">{{ $gold_array[$i] }}</td>
                                            <td class="text-center">{{ $impurity_array[$i] }}</td>
                                            <td class="text-center">{{ $ratti_array[$i] }}</td>
                                            <td class="text-center">{{ $ratti_impurity_array[$i] }}</td>
                                            <td class="text-center">{{ $rate_array[$i] }}</td>
                                            <td class="text-center">{{ $rate_gram_array[$i] }}</td>
                                        </tr>
                                    @endfor
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
                                        {data: 'account' , name: 'account' , 'sortable': false , searchable: false},
                                        {data: 'status' , name: 'status' , 'sortable': false , searchable: false},
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
