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
                                    <b>{{ $sale->customer_name ?? '' }}</b><br>
                                    <b>CNIC:</b> {{ $sale->customer_cnic ?? '' }}<br>
                                    <b>Contact #:</b> {{ $sale->customer_contact ?? '' }}<br>
                                    <b>Address:</b> {{ $sale->customer_address ?? '' }}
                                </div>
                                <div style="float: right; line-height: 25px;">
                                    <b>Invoice No:</b> {{$sale->sale_no??''}} <br>
                                    <b>Date:</b> {{date("d M Y g:h:i A", strtotime(str_replace('/', '-', $sale->sale_date)))}} <br>
                                    <b>Created By:</b> {{$sale->created_by->name??''}}
                                </div>
                            </div>
                            <br><br><br><br>
                            <table border="1" class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item &amp; Description</th>
                                        <th>Making</th>
                                        <th>Other Charges</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale_detail as $index=> $item)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>
                                            <b>{{$item['tag_no']}} {{$item['product']}}</b><br>
                                            <small><b>Karat:</b>{{$item['gold_carat']}}, <b>Scale Wt:</b>{{$item['scale_weight']}},
                                                <b>Net Wt:</b>{{$item['net_weight']}}, <b>Waste:</b>{{$item['waste']}},
                                                <b>Gross Wt:</b>{{$item['gross_weight']}} </small> <br>
                                            <small>
                                                @if(count($item['bead_detail'])>0)
                                                <b>Beads:</b><br>
                                                @foreach ($item['bead_detail'] as $item1)
                                                <b>Type:</b>{{$item1['type']??''}}, <b>QTY:</b>{{$item1['beads']}}, <b>Bead Wt:</b>{{$item1['gram']}}, <b>Amount:</b>{{number_format($item1['total_amount'],3)}} <br>
                                                @endforeach
                                                @endif
                                                @if(count($item['stone_detail'])>0)
                                                <b>Stones:</b><br>
                                                @foreach ($item['stone_detail'] as $item1)
                                                <b>Category:</b>{{$item1['category']??''}},<b>Type:</b>{{$item1['type']??''}}, <b>QTY:</b>{{$item1['stones']}}, <b>Stone Wt:</b>{{$item1['gram']}}, <b>Amount:</b>{{number_format($item1['total_amount'],3)}} <br>
                                                @endforeach

                                                @endif
                                                @if(count($item['diamond_detail'])>0)
                                                <b>Diamonds:</b><br>
                                                @foreach ($item['diamond_detail'] as $item1)
                                                <b>Type:</b>{{$item1['type']??''}}, <b>QTY:</b>{{$item1['diamonds']}}, <b>Color:</b>{{$item1['color']}}, <b>Cut:</b>{{$item1['cut']}}, <b>Clarity:</b>{{$item1['clarity']}}, <b>Carat:</b>{{$item1['carat']}}, <b>Amount:</b>{{number_format($item1['total_amount'],3)}} <br>
                                                @endforeach
                                                @endif
                                            </small>
                                        </td>
                                        <td style="text-align: right;">{{number_format($item['making'],2)}}</td>
                                        <td style="text-align: right;">{{number_format($item['other_amount'],2)}}</td>
                                        <td style="text-align: right;">{{number_format($item['total_amount'],2)}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <br>
                            <div style="float: right;">
                                @php
                                $change = ($sale->cash_amount + $sale->bank_transfer_amount + $sale->card_amount + $sale->advance_amount + $sale->gold_impure_amount) - $sale->total;
                                @endphp
                                <table style="width: 300px; line-height: 30px;">
                                    <tbody>
                                        <tr>
                                            <td><b>Subtotal:</b></td>
                                            <td style="text-align: right;"><b>{{number_format($sale->sub_total,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Discount:</b></td>
                                            <td style="text-align: right;"><b>{{number_format($sale->discount_amount,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Total:</b></td>
                                            <td style="text-align: right;"><b>{{number_format($sale->total,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Cash Amount</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($sale->cash_amount,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Bank Transfer</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($sale->bank_transfer_amount,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Card Amount</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($sale->card_amount,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Advance Amount</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($sale->advance_amount,2)}} PKR</b></td>
                                        </tr>
                                        <tr>
                                            <td><b>Gold Impurity</b></td>
                                            <td style="text-align: right;"><b>(-) {{number_format($sale->gold_impure_amount ,2)}} PKR</b></td>
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