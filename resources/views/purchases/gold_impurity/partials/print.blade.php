@extends('layouts.master')
@section('content')
<div class="breadcrumb">
    <h1>Gold Impurity Print</h1>

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
                                    <b style="font-size:18px;">Receipt To</b><br>
                                    <b>{{ $gold_impurity->customer_name->name ?? '' }}</b><br>
                                    <b>CNIC:</b> {{ $gold_impurity->customer_name->cnic ?? '' }}<br>
                                    <b>Contact #:</b> {{ $gold_impurity->customer_name->contact ?? '' }}<br>
                                    <b>Address:</b> {{ $gold_impurity->customer_name->address ?? '' }}
                                </div>
                                <div style="float: right; line-height: 25px;">
                                    <b>Receipt No:</b> {{$gold_impurity->gold_impurity_purchase_no??''}} <br>
                                    <b>Date:</b> {{date("d M Y g:h:i A", strtotime(str_replace('/', '-', $gold_impurity->created_at)))}} <br>
                                    <b>Created By:</b> {{$gold_impurity->created_by->name??''}}
                                </div>
                            </div>
                            <br><br><br><br>
                            <table border="1" class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Scale Wt</th>
                                        <th>Bead Wt</th>
                                        <th>Stone Wt</th>
                                        <th>Point</th>
                                        <th>Pure Weight</th>
                                        <th>Gold Rate/gram</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gold_impurity_detail as $index=> $item)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td style="text-align: right;">{{$item['scale_weight']??0}}</td>
                                        <td style="text-align: right;">{{$item['bead_weight']??0}}</td>
                                        <td style="text-align: right;">{{$item['stone_weight']??0}}</td>
                                        <td style="text-align: right;">{{$item['point']??0}}</td>
                                        <td style="text-align: right;">{{$item['pure_weight']??0}}</td>
                                        <td style="text-align: right;">{{$item['gold_rate']??0}}</td>
                                        <td style="text-align: right;">{{$item['total_amount']}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <br>
                            <div style="float: right;">
                                @php
                                $balance =  $gold_impurity->total - ($gold_impurity->cash_payment + $gold_impurity->bank_payment);
                                @endphp
                                <table style="width: 300px; line-height: 30px;">
                                    <tbody>
                                        <tr>
                                            <td><b>Total QTY:</b></td>
                                            <td style="text-align: right;"><b>{{$gold_impurity->total_qty??0}}</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total Weight:</b></td>
                                            <td style="text-align: right;"><b>{{$gold_impurity->total_weight??0}}</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total:</b></td>
                                            <td style="text-align: right;"><b>{{$gold_impurity->total??0}}</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Cash Payment</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($gold_impurity->cash_payment,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Bank Transfer</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($gold_impurity->bank_payment,2)}} PKR</b></td>
                                        </tr>
                                        <tr style="border-top:1px solid #444;">
                                            <td><b>Balance</b></td>
                                            <td style="text-align: right;"><b>{{number_format($balance,2)}} PKR</b></td>
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