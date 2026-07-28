<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tag History Report</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Tag History Report</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{config('enum.company_name')}}</b><br>
            <b>Email: </b>{{config('enum.company_email')}} <br>
            <b>Phone No: </b>{{config('enum.company_phone')}}
        </div>
        <div style="float: right;">
            <b>Date: </b>{{ $parms->start_date ?? '' }} To {{ $parms->end_date ?? '' }} <br>
            <b>Tag No: </b>{{ $parms->tag_no ?? 'All' }}<br>
            <b>Product: </b>{{ $parms->product ?? 'All' }}<br>
            <b>Warehouse: </b>{{ $parms->warehouse ?? 'All' }}<br>
        </div>
    </div>
    <br><br><br><br>

    @foreach ($parms->data as $item)
        <b style="font-size: 16px;font-weight: 800;">{{ $item['tag_no'] }} - {{ $item['product_name'] }}</b>
        <hr style="margin:5px;">
        <div style="width:100%;display: inline-block;">
            <div style="float:left; width:48%;">
                <b>Stock Detail</b>
                <hr style="margin:5px;">
                <table border="1" style="width:100%;">
                    <tbody>
                        <tr>
                            <td colspan="2"><b>Weight:</b></td>
                            <td colspan="2">{{ $item['gross_weight'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Wastage:</b></td>
                            <td colspan="2">{{ $item['waste'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Total Weight:</b></td>
                            <td colspan="2">{{ $item['scale_weight'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>QTY:</b></td>
                            <td colspan="2">{{ $item['quantity'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Worker:</b></td>
                            <td colspan="2">{{ $item['createdby'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Date Time:</b></td>
                            <td colspan="2">{{ $item['date_time'] }}</td>
                        </tr>
                        <tr>
                            <td><b>Beads</b></td>
                            <td><b>Weight</b></td>
                            <td><b>Rate</b></td>
                            <td><b>Total Price</b></td>
                        </tr>
                        <tr>
                            @php
                                $bead_rate =$item['f_beads']->sum('gram_rate') / (($item['f_beads']->count() > 0)? $item['f_beads']->count(): 1);

                            @endphp
                            <td>{{ $item['f_beads']->sum('beads') }}</td>
                            <td>{{ $item['bead_weight'] }}</td>
                            <td>{{ number_format($bead_rate, 3) }}</td>
                            <td>{{ $item['total_bead_price'] }}</td>
                        </tr>
                        <tr>
                            <td><b>Stones</b></td>
                            <td><b>Weight</b></td>
                            <td><b>Rate</b></td>
                            <td><b>Total Price</b></td>
                        </tr>
                        <tr>
                            @php
                                $stone_rate =$item['f_stones']->sum('gram_rate') / (($item['f_stones']->count() > 0)? $item['f_stones']->count(): 1);

                            @endphp
                            <td>{{ $item['f_stones']->sum('stones') }}</td>
                            <td>{{ $item['stones_weight'] }}</td>
                            <td>{{ number_format($stone_rate, 3) }}</td>
                            <td>{{ $item['total_stones_price'] }}</td>
                        </tr>
                        <tr>
                            <td><b>Dimonds</b></td>
                            <td><b>Weight</b></td>
                            <td><b>Rate</b></td>
                            <td><b>Total Price</b></td>
                        </tr>
                        <tr>
                            @php
                                $diamond_rate =
                                    $item['f_diamonds']->sum('carat_rate') / (($item['f_diamonds']->count() > 0)? $item['f_diamonds']->count(): 1);

                            @endphp
                            <td>{{ $item['f_diamonds']->sum('diamonds') }}</td>
                            <td>{{ $item['diamond_weight'] }}</td>
                            <td>{{ number_format($diamond_rate, 3) }}</td>
                            <td>{{ $item['total_diamond_price'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="float: right; width:48%;">
                <b> Sale Detail</b>
                <hr style="margin:5px;">
                <table border="1" style="width:100%;">
                    <tbody>
                        <tr>
                            <td colspan="2"><b>Customer Name:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->customer_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Address:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->customer_address ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Contact No:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->customer_contact ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Sale No:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->sale_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Sale Date:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->sale_date ?? '' }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Total Price:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->total ?? 0.0 }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Discount:</b></td>
                            <td colspan="2">0.000</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Net Amount:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->total ?? 0.0 }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Recieved Amount:</b></td>
                            <td colspan="2">{{ $item['sale_detail']->sale->total_received ?? 0.0 }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Balance:</b></td>
                            <td colspan="2">
                                {{ $item['sale_detail']->sale->total - $item['sale_detail']->sale->total_received }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr style="margin:5px;">
    @endforeach

</div>

</html>

{{-- @php
    exit();
@endphp --}}
