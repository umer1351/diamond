@extends('layouts.master')
@section('css')
@endsection
@section('content')
    <div class="breadcrumb pt-4">
        <h1>Profit & Loss Report</h1>
        <ul>
            <li>Report</li>
            <li>View</li>
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
                    <div class="card-header bg-transparent">
                        <h4 class="card-inside-title">Filters</h4>
                    </div>
                    <div class="card-body">
                        <form id="form_filter" name="form" method="GET" target="_blank"
                            action="{{ url('reports/get-profit-loss-report') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Start Date:<span class="text-danger">*</span></label>
                                        <div class="form-line">
                                            <input type="date" id="start_date" name="start_date" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">End Date:<span class="text-danger">*</span></label>
                                        <div class="form-line">
                                            <input type="date" id="end_date" name="end_date" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Currency:<span class="text-danger">*</span> </label>
                                        <select class="form-control" id="currency" name="currency" required>
                                            <option value="" selected disabled>--Select Currency--</option>
                                            <option value="0">PKR (Rs)</option>
                                            <option value="1">Gold (AU)</option>
                                            <option value="2">Dollar ($)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="row">
                                    <div class="col-md-2 pr-0">
                                        <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                        <button class="btn btn-primary btn-block" id="search">Search</button>
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                        <button type='submit' class="btn btn-danger btn-block" name="export-pdf"
                                            value="export-pdf">Export
                                            Pdf</button>
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                        <button type='submit' class="btn btn-success btn-block" name="export-excel"
                                            value="export-excel">Export
                                            Excel</button>
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label for="select_restaurant" class="ul-form__label">&nbsp;</label>
                                        <button type='submit' class="btn btn-info btn-block" id="print-report"
                                            name="print-report" value="print-report">
                                            Print</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="result">

                        </div>
                    </div>
                    <!-- end of col -->
                </div>
                <!-- end of row -->
            </div>
        </div>
    </section>
    @include('reports/profit_loss/Modal/LedgerViewModal')
@endsection
@section('js')
    <script type="text/javascript">
        function successMessage(message) {
            toastr.success(message, "Success!", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 2e3,
            });
        }

        function errorMessage(message) {

            toastr.error(message, "Error", {
                showMethod: "slideDown",
                hideMethod: "slideUp",
                timeOut: 2e3,
            });

        }
        $(document).ready(function() {

            $("#currency").select2();

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

        $("#search").on("click", function(e) {
            e.preventDefault();

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var currency = $('#currency').val();
            $("#preloader").show();
            if (start_date == '' || start_date == 0) {
                errorMessage('Start Date Field Required!');
                $("#preloader").hide();
                return false;
            }
            if (end_date == '' || end_date == 0) {
                errorMessage('End Date Field Required!');
                $("#preloader").hide();
                return false;
            }
            if (currency == '') {
                errorMessage('Select Currency first!');
                $("#preloader").hide();
                return false;
            }

            var data = {
                start_date: start_date,
                end_date: end_date,
                currency: currency,
            };

            $.ajax({
                type: 'GET',
                url: "{{ url('reports/get-preview-profit-loss-report') }}",
                data: data,
            }).done(function(response) {
                successMessage(response.Message);
                $('.result').html('');
                $('.result').html(response);
                $("#preloader").hide();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // This will handle the error
                $('.result').html('<div class="alert alert-danger">Error: ' + errorThrown + '</div>');
                errorMessage(errorThrown);
                $("#preloader").hide();
            });
        });
        $("body").on("click", "#goToLedger", function() {
            var parent_id = $(this).data("id");
            var title = $(this).data("title");
            $("#modelHeading").html('Ledger Detail of ' + title);
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            $("#preloader").show();
            $.ajax({
                type: 'GET',
                url: "{{ url('reports/go-to-ledger-report') }}/" + parent_id,
            }).done(function(response) {
                var data = {};
                data.account_id = response.Data;
                data.start_date = start_date;
                data.end_date = end_date;
                ledgerView(data);
            });
        });

        function ledgerView(data) {
            console.log(data);
            $.ajax({
                type: 'GET',
                url: "{{ url('reports/get-ledger-report') }}",
                data: data,
                beforeSend: function() {
                    $("#preloader").show();
                },

                complete: function() {
                    $("#preloader").hide();
                },
            }).done(function(data) {
                console.log(data);
                if (data.length > 0) {
                    $("#ledgerView").html(data);
                    $("#ajaxModel").modal("show");
                    $("#preloader").hide();
                } else {
                    successMessage("No Data/Record found!");
                    $("#preloader").hide();
                }
            });
        }

        $("#print-report").on("click", function(e) {
            e.preventDefault();

            var divToPrint = document.getElementsByClassName('result')[0];

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();
        });
    </script>
@endsection
