@extends('layouts.master')
@section('content')
    <div class="breadcrumb">
        <h1>Sale Print</h1>

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
                                        <b>{{ $other_sale->customer_name ?? '' }}</b><br>
                                        <b>CNIC:</b> {{ $other_sale->customer_cnic ?? '' }}<br>
                                        <b>Contact #:</b> {{ $other_sale->customer_contact ?? '' }}<br>
                                        <b>Address:</b> {{ $other_sale->customer_address ?? '' }}
                                    </div>
                                    <div style="float: right; line-height: 25px;">
                                        <b>Invoice No:</b> {{$other_sale->other_sale_no??''}} <br>
                                        <b>Date:</b> {{date("d M Y g:h:i A", strtotime(str_replace('/', '-', $other_sale->other_sale_date)))}} <br>
                                        <b>Created By:</b> {{$other_sale->created_by->name??''}}
                                    </div>
                                </div>
                                <br><br><br><br>
                                <table border="1" class="table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Unit</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_qty=0;
                                        @endphp
                                        @foreach ($other_sale_detail as $index=> $item)
                                        @php
                                            $total_qty = $total_qty + $item['qty'];
                                        @endphp
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td><b>{{$item['code']}} {{$item['product']}}</b></td>
                                                <td>{{$item['unit']??''}}</td>
                                                <td style="text-align: right;">{{number_format($item['unit_price'],2)}}</td>
                                                <td style="text-align: right;">{{number_format($item['qty'],2)}}</td>
                                                <td style="text-align: right;">{{number_format($item['total_amount'],2)}}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <br>
                                <div style="float: right;">
                                    @php
                                        $change = ($other_sale->cash_amount + $other_sale->bank_transfer_amount + $other_sale->card_amount + $other_sale->advance_amount) - $other_sale->total;
                                    @endphp
                                    <table style="width: 300px; line-height: 30px;">
                                        <tbody>
                                            <tr>
                                                <td><b>Total QTY:</b></td>
                                                <td style="text-align: right;"><b>{{number_format($total_qty,2)}}</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Total:</b></td>
                                                <td style="text-align: right;"><b>{{number_format($other_sale->total,2)}} PKR</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Cash Amount</b></td>
                                                <td style="text-align: right;"><b>(-) {{number_format($other_sale->cash_amount,2)}} PKR</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Bank Transfer</b></td>
                                                <td style="text-align: right;"><b>(-) {{number_format($other_sale->bank_transfer_amount,2)}} PKR</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Card Amount</b></td>
                                                <td style="text-align: right;"><b>(-) {{number_format($other_sale->card_amount,2)}} PKR</b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Advance Amount</b></td>
                                                <td style="text-align: right;"><b>(-) {{number_format($other_sale->advance_amount,2)}} PKR</b></td>
                                            </tr>
                                            <tr style="border-top:1px solid #444;">
                                                <td><b>Change</b></td>
                                                <td style="text-align: right;"><b>{{number_format($change,2)}} PKR</b></td>
                                            </tr>
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
