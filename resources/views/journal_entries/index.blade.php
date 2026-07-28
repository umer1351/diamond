@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Journal Entries</h1>
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
                            @can('journal_entries_create')
                                <a class="btn btn-primary btn-md m-1" href="{{ url('journal-entries/create') }}"
                                    id="createNewJournal"><i class="fa fa-plus text-white mr-2"></i> Add Journal Entry</a>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <b>Filter</b>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">From Date</label>
                                        <input type="date" name="from_date" class="form-control" id="from_date" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">To Date</label>
                                        <input type="date" name="to_date" class="form-control" id="to_date" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Journal</label>
                                        <select name="journal_id" id="journal_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($journals as $item)
                                                <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($suppliers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Customer</label>
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button class="mt-4 btn btn-primary" id="search">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr style="margin-top:0px;">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="journal_entry_table" class="table display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Voucher</th>
                                            <th>Journal</th>
                                            <th>Reference</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
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
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/common-methods/toasters.js') }}" type="module"></script>
    <script src="{{ asset('js/common-methods/http-requests.js') }}" type="module"></script>
    <script src="{{ url('journal-entries/js/JournalEntryForm.js') }}" type="module"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
        $(document).ready(function() {
            $("#journal_id").select2();
            $("#supplier_id").select2();
            $("#customer_id").select2();
        });
    </script>
    @include('includes.datatable', [
        'columns' => "
    {data: 'date_post' , name: 'date_post'},
    {data: 'entryNum' , name: 'entryNum'},
    {data: 'journal' , name: 'journal','sortable': false , searchable: false},
    {data: 'reference' , name: 'reference' , 'sortable': false},
    {data: 'debit' , name: 'debit' , 'sortable': false},
    {data: 'credit' , name: 'credit' , 'sortable': false},
    {data: 'action' , name: 'action' , 'sortable': false , searchable: false},",
        'route' => 'journal-entries/data',
        'buttons' => false,
        'pageLength' => 10,
        'class' => 'journal_entry_table',
        'variable' => 'journal_entry_table',
        'datefilter' => true,
        'params' =>"from_date:$('#from_date').val(),to_date:$('#to_date').val(),journal_id:$('#journal_id').val(),supplier_id:$('#supplier_id').val(),customer_id:$('#customer_id').val()",
    ]);

    <script>
        $(document).ready(function() {
            const fromDate = document.getElementById("from_date");
            const toDate = document.getElementById("to_date");

            // ✅ Using the visitor's timezone
            fromDate.value = formatDate();
            toDate.value = formatDate();

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
            fromDate.value = new Date().toISOString().split("T")[0];
            toDate.value = new Date().toISOString().split("T")[0];

            initDataTablejournal_entry_table();
        });

        $(document).on('click', '#search', function() {
            initDataTablejournal_entry_table();
        });
    </script>
@endsection
