@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Job Task</h1>
            <ul>
                <li>List</li>
                <li>All</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <!-- end of row -->
        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card text-left">
                        <div class="card-header text-right bg-transparent">
                        </div>
                        <div class="card-body">

                            <h4 class="card-inside-title mt-2">Filters</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">From:<span class="text-danger">*</span></label>
                                        <input type="date" id="start_date" name="start_date" class="form-control date">
                                    </div>
                                </div>
                                <div class="col-md-2" id="div_end_date">
                                    <div class="form-group">
                                        <label for="">To:<span class="text-danger">*</span></label>
                                        <input type="date" id="end_date" name="end_date" class="form-control date">
                                    </div>
                                </div>
                                @if(auth()->user()->roles[0]->name!='Supplier/Karigar User')
                                <div class="col-md-3" id="div_supplier">
                                    <div class="form-group">
                                        <label for="">Supplier</label>
                                        <select id="supplier_id" name="supplier_id" class="form-control">
                                            <option value="">--Select Supplier--</option>
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary waves-effect" id="search_button"
                                            type="submit">Search</button>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="job_task_table" class="display table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Job Task No</th>
                                                <th>Delivery Date</th>
                                                <th>Supplier Name</th>
                                                <th>Purchase Order</th>
                                                <th>Warehouse</th>
                                                <th>Total QTY</th>
                                                <th>Complete</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- end of col -->
                    </div>
                    <!-- end of row -->
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/momenttimezone/0.5.31/moment-timezone-with-data-2012-2022.min.js">
    </script>
    <script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
    @include('includes.datatable', [
        'columns' => "
                                    {data: 'job_task_date', name: 'job_task_date'},
                                    {data: 'job_task_no', name: 'job_task_no', orderable: false, searchable: false},
                                    {data: 'delivery_date', name: 'delivery_date'},
                                    {data: 'supplier_name',name: 'supplier_name', orderable: false, searchable: false},
                                    {data: 'purchase_order',name: 'purchase_order', orderable: false, searchable: false},
                                    {data: 'warehouse_name',name: 'warehouse_name', orderable: false, searchable: false},
                                    {data: 'total_qty',name: 'total_qty'},
                                    {data: 'is_complete',name: 'is_complete', orderable: false, searchable: false},
                                    {data: 'action',name: 'action','sortable': false,searchable: false}",
        'route' => 'job-task/data',
        'buttons' => false,
        'pageLength' => 50,
        'class' => 'job_task_table',
        'variable' => 'job_task_table',
        'datefilter' => true,
        'params' => "supplier_id:$('#supplier_id').val()",
    ])
    <script>
        $(document).ready(function() {
            $('#supplier_id').select2();

            const startDate = document.getElementById("start_date");
            const endDate = document.getElementById("end_date");

            // ✅ Using the visitor's timezone
            startDate.value = formatDate();
            endDate.value = formatDate();

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
            startDate.value = new Date().toISOString().split("T")[0];
            endDate.value = new Date().toISOString().split("T")[0];


        });

        function isNumberKey(evt) {
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>
    <script type="text/javascript">
        $('#search_button').click(function() {
            $("#preloader").show();
            initDataTablejob_task_table();
            $("#preloader").hide();
        });

        function error(message) {
            toastr.error(message, "Error!", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 2e3,
            });
        }

        function success(message) {
            toastr.success(message, "Success!", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 2e3,
            });
        }
        // Delete Code

        $("body").on("click", "#deleteJobTask", function() {
            var job_task_id = $(this).data("id");
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
                            url: "{{ url('job-task/destroy') }}/" + job_task_id,
                        }).done(function(data) {
                            if ((data.Success = true)) {
                                $("#preloader").hide();
                                success(data.Message)
                                initDataTablejob_task_table();
                            } else {
                                error(data.Message);
                            }

                        })
                        .catch(function(err) {
                            error(err.Message);
                        });
                }
            });
        });

    </script>
@endsection
