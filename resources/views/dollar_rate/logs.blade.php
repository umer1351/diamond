@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Dollar Rate Logs</h1>
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
                            <div class="table-responsive">
                                <table id="dollar_rate_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Date-Time</th>
                                            <th class="text-left">Rate</th>
                                            <th class="text-left">Created By</th>
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
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
    </script>
    @include('includes.datatable', [
        'columns' => "
                        {data: 'created_at' , name: 'created_at' , 'sortable': false , searchable: false},
                        {data: 'rate' , name: 'rate'},
                        {data: 'created_by' , name: 'created_by' , 'sortable': false , searchable: false},",
        'route' => 'data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'dollar_rate_table',
        'variable' => 'dollar_rate_table',
    ])
@endsection
