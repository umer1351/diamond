@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>{{ $job_task->job_task_no }} Activity</h1>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <form id="job_task_activityForm" action="#" method="POST"
                                    enctype="multipart/form-data">
                                        <div class="card-body" style="font-size: 14px;">
                                            @csrf
                                            <input type="hidden" name="job_task_id" id="job_task_id"
                                                value="{{ $job_task->id }}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Category:<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" id="category" name="category"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Design No:</label>
                                                        <input type="text" id="design_no" name="design_no"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Weight:<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" id="weight" name="weight"
                                                            class="form-control" onkeypress="return isNumberKey(event)"
                                                            required />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Picture:</label>
                                                        <input type="file" class="form-control" name="picture"
                                                            id="picture" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Description:<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" id="description" name="description"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary" id="submit"
                                                        accesskey="s">SAVE</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="job_task_activity_table" class="table table-striped display"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Job Task</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Design</th>
                                                        <th scope="col">Weight</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Picture</th>
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
<script src="{{ url('job-task-activity/js/job_task_activity.js') }}"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
    </script>
    @include('includes.datatable', [
        'columns' => "
                {data: 'date' , name: 'date' , 'sortable': false , searchable: false},
                {data: 'job_task' , name: 'job_task' , 'sortable': false , searchable: false},
                {data: 'category' , name: 'category'},
                {data: 'design_no' , name: 'design_no'},
                {data: 'weight' , name: 'weight'},
                {data: 'description' , name: 'description'},
                {data: 'image' , name: 'image' , 'sortable': false , searchable: false},
                {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'job_task_activity_table',
        'variable' => 'job_task_activity_table',
        'params' => "job_task_id:$('#job_task_id').val()",
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
        $("body").on("click", "#deleteJobTaskActivity", function() {
            var job_task_activity_id = $(this).data("id");
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
                            url: "{{ url('job-task-activity/destroy') }}/" + job_task_activity_id,
                        })
                        .done(function(data) {
                            if (data.Success) {
                                successMessage(data.Message);
                                initDataTablejob_task_activity_table();
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
