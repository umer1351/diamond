@extends('layouts.master')
@section('content')
    <div class="breadcrumb mt-4">
        <h1>Sale Order Print</h1>

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
                        <div class="row">
                            <div class="col-md-12">
                                <table style="width:100%;">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td><img src="{{ asset('assets/images/logo.png') }}"
                                                    style="width:40%; height:130px;" alt=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="width: 100%;">
                                    <div style="float: left; line-height: 25px; width:70%;">
                                        <b style="font-size:18px;">Bill To</b><br>
                                        <b>{{ $sale_order->customer_name->name ?? '' }}</b><br>
                                        <b>CNIC:</b> {{ $sale_order->customer_name->cnic ?? '' }}<br>
                                        <b>Contact #:</b> {{ $sale_order->customer_name->contact ?? '' }}<br>
                                        <b>Address:</b> {{ $sale_order->customer_name->address ?? '' }}
                                    </div>
                                    <div style="float: right; line-height: 25px;">
                                        <b>Invoice No:</b> {{$sale_order->sale_order_no??''}} <br>
                                        <b>Date:</b> {{date("d M Y g:h:i A", strtotime(str_replace('/', '-', $sale_order->sale_order_date)))}} <br>
                                        <b>Warehouse:</b> {{$sale_order->warehouse_name->name??''}} <br>
                                        <b>Created By:</b> {{$sale_order->created_by->name??''}}
                                    </div>
                                </div>
                                <br><br><br><br>
                                <table border="1" class="table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Design No</th>
                                            <th>Net Weight</th>
                                            <th>Waste</th>
                                            <th>Gross Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sale_order_detail as $index=> $item)
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{$item['product_name']}}</td>
                                                <td>{{$item['category']}}</td>
                                                <td>{{$item['design_no']??''}}</td>
                                                <td style="text-align: right;">{{number_format($item['net_weight'],3)}}</td>
                                                <td style="text-align: right;">{{number_format($item['waste'],3)}}</td>
                                                <td style="text-align: right;">{{number_format($item['gross_weight'],3)}}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <br>
                                <div style="float: right;">
                                    <table style="width: 300px; line-height: 30px;">
                                        <tbody>
                                            <tr>
                                                <td><b>Total QTY:</b></td>
                                                <td style="text-align: right;"><b>{{number_format($sale_order->total_qty,2)}}</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Gold Rate</b></td>
                                                <td style="text-align: right;"><b>{{number_format($sale_order->gold_rate,3)}}/Gram</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Rate Type</b></td>
                                                <td style="text-align: right;"><b>{{$sale_order->gold_rate_type->name??''}}</b></td>
                                            </tr>
                                            @foreach($advance as $item)
                                            <tr>
                                                <td><b>Receipt -{{$item->date_post??''}}</b></td>
                                                <td style="text-align: right;"><b>{{number_format($item->credit??0,2)}}</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div style="float: left;">
                                    <b>Signature:</b><br>
                                    <div style="height: 100px;">

                                    </div><br>
                                    <b>Name:</b> <span>........................................</span><br><br>
                                    <b>Position:</b> <span>....................................</span>
                                </div>
                            </div>
                        </div>
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
