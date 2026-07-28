@extends('layouts.master')
@section('content')
    <div class="breadcrumb mt-4">
        <h1>Job Task Print</h1>

        <ul>
            <li>View</li>
            <li>Print</li>
        </ul>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <!-- end of row -->
    <section class="contact-list">
        <div class="row">

            <div class="col-md-12 mb-4">

                <div class="card text-left">
                    <div class="card-header text-right bg-transparent">
                        <button class="btn btn-info" onclick="printDiv('printData')" type="submit"><span
                                class="fas fa-print"></span> Print</button>
                    </div>
                    <div class="card-body" id="printData">
                        <center style="float:none; margin-top:20px;">
                            <h3>Job Task</h3>
                        </center>
                        <div>
                            <div style="float:left;">
                                <b>{{config('enum.company_name')}}</b><br>
                                <b>Email: </b>{{config('enum.company_email')}} <br>
                                <b>Phone No: </b>{{config('enum.company_phone')}}<br>
                            </div>
                            <div style="float: right;">
                                <b>Job Task No: </b>{{ $job_task->job_task_no??'' }} <br>
                                <b>Job Task Date: </b>{{ date('d M Y', strtotime($job_task->job_task_date)) }}  <br>
                                <b>Supplier: </b>{{ $job_task->supplier_name->name??'' }} <br>
                                <b>Warehouse: </b>{{ $job_task->warehouse_name->name??'' }} <br>
                                <b>Delivery Date: </b>{{ date('d M Y H:i:s', strtotime($job_task->delivery_date??'')) }}<br>
                            </div>
                        </div>
                        <br><br><br><br><br>
                        <table border="1" style="border:1px dotted #000; float:none; width:100%;">
                            <thead>
                                <tr style="background: #87CEFA;color: #000;">
                                    <th style="text-align:center;">Sr.No</th>
                                    <th style="text-align:center;">Category</th>
                                    <th style="text-align:center;">Design No</th>
                                    <th style="text-align:center;">Net Weight</th>
                                    <th style="text-align:center;">Description</th>
                                </tr>
                            </thead>
                    
                            <tbody>
                                @foreach ($job_task_detail as $index=>$item)
                                <tr>
                                    <td style="text-align:center;">{{ $index+1 }}</td>
                                    <td style="text-align:center;">{{ $item['category']??'' }}</td>
                                    <td style="text-align:center;">{{ $item['design_no']??'' }}</td>
                                    <td style="text-align:right;">{{ number_format($item['net_weight']??0.000,3) }}</td>
                                    <td style="text-align:left;">{{ $item['description']??'' }}</td>
                                </tr>
                                @endforeach
                    
                            </tbody>
                    
                        </table>
                        <br>
                        <b>Generate By:</b> {{ $job_task->created_by->name ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function printDiv(e) {
            var divToPrint = document.getElementById(e);

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                newWin.close();
            }, 10);
        }
    </script>
@endsection
