@extends('layouts.master')
@section('content')
    <div class="breadcrumb mt-4">
        <h1>Job Purchase Print</h1>

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
                            <h3>Job Purchase</h3>
                        </center>
                        <div>
                            <div style="float:left;">
                                <b>{{ config('enum.company_name') }}</b><br>
                                <b>Email: </b>{{ config('enum.company_email') }} <br>
                                <b>Phone No: </b>{{ config('enum.company_phone') }}<br>
                            </div>
                            <div style="float: right;">
                                <b>Job Purchase No: </b>{{ $job_purchase->job_purchase_no ?? '' }} <br>
                                <b>Job Purchase Date: </b>{{ date('d-M-Y', strtotime($job_purchase->job_purchase_date)) }}
                                <br>
                                <b>Purchase Order: </b>{{ $job_purchase->purchase_order->purchase_order_no ?? '' }} <br>
                                <b>Sale Order: </b>{{ $job_purchase->sale_order->sale_order_no ?? '' }} <br>
                                <b>Supplier: </b>{{ $job_purchase->supplier_name->name ?? '' }} <br>
                            </div>
                        </div>
                        <br><br><br><br><br>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            @foreach ($job_purchase_detail as $index=>$item)
                                <div style="@if(count($job_purchase_detail)>1) flex: 1 1 calc(50% - 10px); @else flex: 1 1 calc(100% - 10px); @endif">
                                    <table border="1" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td><b>Sr.</b></td>
                                                <td>{{$index+1}}</td>
                                                <td><b>Product:</b></td>
                                                <td>{{$item['product']['name']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Category:</b></td>
                                                <td>{{$item['category']??''}}</td>
                                                <td><b>Design:</b></td>
                                                <td>{{$item['design_no']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>With Polish Wt:</b></td>
                                                <td style="text-align: right;">{{$item['polish_weight']??''}}</td>
                                                <td><b>Ratti Waste:</b></td>
                                                <td style="text-align: right;">{{$item['waste_ratti']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Waste:</b></td>
                                                <td style="text-align: right;">{{$item['waste']??''}}</td>
                                                <td><b>Total Wt:</b></td>
                                                <td style="text-align: right;">{{$item['total_weight']??''}}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td><b>Mail:</b></td>
                                                <td>{{$item['mail']??''}} Mail</td>
                                                <td><b>Mail Ratti:</b></td>
                                                <td style="text-align: right;">{{$item['mail_weight']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Pure Wt:</b></td>
                                                <td style="text-align: right;">{{$item['pure_weight']??''}}</td>
                                                <td><b>Stone Fitting Waste:</b></td>
                                                <td style="text-align: right;">{{$item['stone_waste']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Stone Adjustment:</b></td>
                                                <td style="text-align: right;">{{$item['stone_adjustment']??''}}</td>
                                                <td><b>Bead Wt:</b></td>
                                                <td style="text-align: right;">{{$item['bead_weight']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Stone Wt:</b></td>
                                                <td style="text-align: right;">{{$item['stones_weight']??''}}</td>
                                                <td><b>Diamond Carat:</b></td>
                                                <td style="text-align: right;">{{$item['diamond_carat']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>With Stone Wt:</b></td>
                                                <td style="text-align: right;">{{$item['with_stone_weight']??''}}</td>
                                                <td><b>Actual Recieved Wt:</b></td>
                                                <td style="text-align: right;">{{$item['recieved_weight']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Stone Waste:</b></td>
                                                <td style="text-align: right;">{{$item['stone_waste_weight']??''}}</td>
                                                <td><b>Total Recieved Wt:</b></td>
                                                <td style="text-align: right;">{{$item['total_recieved_weight']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Payable/Recieveable Wt:</b></td>
                                                <td style="text-align: right;">{{$item['payable_weight']??''}}</td>
                                                <td><b>Bead Amount:</b></td>
                                                <td style="text-align: right;">{{$item['total_bead_amount']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Stone Amount:</b></td>
                                                <td style="text-align: right;">{{$item['total_stones_amount']??''}}</td>
                                                <td><b>Diamond Amount:</b></td>
                                                <td style="text-align: right;">{{$item['total_diamond_amount']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Laker:</b></td>
                                                <td style="text-align: right;">{{$item['laker']??''}}</td>
                                                <td><b>RP:</b></td>
                                                <td style="text-align: right;">{{$item['rp']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Wax:</b></td>
                                                <td style="text-align: right;">{{$item['wax']??''}}</td>
                                                <td><b>Other:</b></td>
                                                <td style="text-align: right;">{{$item['other']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Final Pure Wt:</b></td>
                                                <td style="text-align: right;">{{$item['final_pure_weight']??''}}</td>
                                                <td><b>Total ($):</b></td>
                                                <td style="text-align: right;">{{$item['total_dollar']??''}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Amount:</b></td>
                                                <td style="text-align: right;" colspan="3">{{$item['total_amount']??''}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table border="1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="8"><b>Beads:</b></th>
                                            </tr>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Type</th>
                                                <th>Beads</th>
                                                <th>Gram</th>
                                                <th>Carat</th>
                                                <th>Rate/Gram</th>
                                                <th>Rate/Carat</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($item['bead_detail'])>0)
                                            @foreach ($item['bead_detail'] as $index1=>$item1)
                                            <tr>
                                                <td>{{$index1+1}}</td>
                                                <td>{{$item1['type']??''}}</td>
                                                <td>{{$item1['beads']??''}}</td>
                                                <td style="text-align: right;">{{$item1['gram']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat']??''}}</td>
                                                <td style="text-align: right;">{{$item1['gram_rate']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat_rate']??''}}</td>
                                                <td style="text-align: right;">{{$item1['total_amount']??''}}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="8" style="text-align: center;">Record not found!</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <table border="1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="9"><b>Stones:</b></th>
                                            </tr>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Stones</th>
                                                <th>Gram</th>
                                                <th>Carat</th>
                                                <th>Rate/Gram</th>
                                                <th>Rate/Carat</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($item['stone_detail'])>0)
                                            @foreach ($item['stone_detail'] as $index1=>$item1)
                                            <tr>
                                                <td>{{$index1+1}}</td>
                                                <td>{{$item1['category']??''}}</td>
                                                <td>{{$item1['type']??''}}</td>
                                                <td>{{$item1['stones']??''}}</td>
                                                <td style="text-align: right;">{{$item1['gram']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat']??''}}</td>
                                                <td style="text-align: right;">{{$item1['gram_rate']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat_rate']??''}}</td>
                                                <td style="text-align: right;">{{$item1['total_amount']??''}}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="9" style="text-align: center;">Record not found!</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <table border="1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th colspan="10"><b>Diamonds:</b></th>
                                            </tr>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Type</th>
                                                <th>Diamonds</th>
                                                <th>Color</th>
                                                <th>Cut</th>
                                                <th>Clarity</th>
                                                <th>Carat</th>
                                                <th>Rate/Carat</th>
                                                <th>Amount</th>
                                                <th>$</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($item['diamond_detail'])>0)
                                            @foreach ($item['diamond_detail'] as $index1=>$item1)
                                            <tr>
                                                <td>{{$index1+1}}</td>
                                                <td>{{$item1['type']??''}}</td>
                                                <td>{{$item1['diamonds']??''}}</td>
                                                <td>{{$item1['color']??''}}</td>
                                                <td>{{$item1['cut']??''}}</td>
                                                <td>{{$item1['clarity']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat']??''}}</td>
                                                <td style="text-align: right;">{{$item1['carat_rate']??''}}</td>
                                                <td style="text-align: right;">{{$item1['total_amount']??''}}</td>
                                                <td style="text-align: right;">{{$item1['total_dollar']??''}}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="10" style="text-align: center;">Record not found!</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                        <br>
                        <b>Generate By:</b> {{ $job_purchase->created_by->name ?? '' }}
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
