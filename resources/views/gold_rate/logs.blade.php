@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Gold Rate Logs</h1>
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
                                <table id="gold_rate_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Date-Time</th>
                                            <th class="text-center">Carat</th>
                                            <th class="text-center">% Gold</th>
                                            <th class="text-center">% Impurity</th>
                                            <th class="text-center">Ratti of Gold</th>
                                            <th class="text-center">Ratti of Impurity</th>
                                            <th class="text-center">Rate/Tola</th>
                                            <th class="text-center">Rate/Gram</th>
                                            <th class="text-center">Created By</th>
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
                        {data: 'carat' , name: 'carat'},
                        {data: 'gold' , name: 'gold'},
                        {data: 'impurity' , name: 'impurity'},
                        {data: 'ratti' , name: 'ratti'},
                        {data: 'ratti_impurity' , name: 'ratti_impurity'},
                        {data: 'rate_tola' , name: 'rate_tola'},
                        {data: 'rate_gram' , name: 'rate_gram'},
                        {data: 'created_by' , name: 'created_by' , 'sortable': false , searchable: false},",
        'route' => 'data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'gold_rate_table',
        'variable' => 'gold_rate_table',
    ])
@endsection
