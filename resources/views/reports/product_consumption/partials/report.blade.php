<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Product Consumption</title>
    <link rel="stylesheet" href="{{ asset('public/css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Product Consumption</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{ config('enum.company_name') }}</b><br>
            <b>Email: </b>{{ config('enum.company_email') }} <br>
            <b>Phone No: </b>{{ config('enum.company_phone') }}
        </div>
        <div style="float: right;">
            <b>Date: </b>{{ $parms->start_date ?? '' }} To {{ $parms->end_date ?? '' }} <br>
            <b>Warehouse: </b>{{ $parms->warehouse ?? 'All' }} <br>
        </div>
    </div>
    <br><br><br><br>

    <table class="font-x-medium" border="1" style="border: 0.5px solid black; width:100%;" width="100%">
        @php
        $grand_total_issue = 0;
        $grand_total_cost = 0;
        $grand_total_consume = 0;
        $grand_total = 0;
        $grand_total_variance = 0;
        @endphp
        <thead>
            <tr class="border-black">
                <th style="text-align:center;">Sr.</th>
                <th style="text-align:center;">Product</th>
                <th style="text-align:center;">Unit</th>
                <th style="text-align:center;">Unit Price</th>
                <th style="text-align:center;">Consumption</th>
                <th style="text-align:center;">Total</th>
                <th style="text-align:center;">Issuance</th>
                <th style="text-align:center;">Total Cost</th>
                <th style="text-align:center;">Variance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parms->data as $item1)
            <tr class="border-black">
                <td style="text-align: left;" colspan="9">
                    <b>{{ ucwords($item1['warehouse_name'] ?? '') }}</b>
                </td>
            </tr>
            @php
            $total_issue = 0;
            $total_cost = 0;
            $total_consume = 0;
            $total = 0;
            $total_variance = 0;
            $i = 1;
            @endphp
            @if(Count($item1['data'])>0)
            @foreach ($item1['data'] as $item)
            @if($item->stock_in_qty>0 || $item->stock_out_qty>0)
            @php
            $total_issue = $total_issue + $item->stock_in_qty;
            $variance = $item->stock_in_qty - $item->stock_out_qty;
            $total_cost = $total_cost + $item->unit_price * $item->stock_in_qty;
            $total_consume = $total_consume + $item->stock_out_qty;
            $total = $total + $item->unit_price * $item->stock_out_qty;
            $total_variance = $total_variance + $variance;

            @endphp
            <tr>
                <td style="text-align: left;">{{ $i }}</td>
                <td style="text-align: left;">{{ ucwords($item->product ?? '') }}</td>
                <td style="text-align: left;">{{ ucwords($item->unit ?? '') }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price ?? 0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->stock_out_qty ?? 0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price * $item->stock_out_qty, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->stock_in_qty ?? 0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->unit_price * $item->stock_in_qty, 2) }}</td>
                <td style="text-align: right;">{{ number_format($variance ?? 0, 2) }}</td>
            </tr>

            @php
            $i++;
            @endphp
            @endif
            @endforeach
            <tr class="border-black" style="text-align: right; font-weight: bold;">
                <td colspan="4" style="text-align: left;">Total</td>
                <td style="text-align: right;">{{ number_format($total_consume, 2) }}</td>
                <td style="text-align: right;">{{ number_format($total, 2) }}</td>
                <td style="text-align: right;">{{ number_format($total_issue, 2) }}</td>
                <td style="text-align: right;">{{ number_format($total_cost, 2) }}</td>
                <td style="text-align: right;">{{ number_format($total_variance, 2) }}</td>
            </tr>
            @php
            // Grand Total
            $grand_total_issue += $total_issue;
            $grand_total_cost += $total_cost;
            $grand_total_consume += $total_consume;
            $grand_total += $total;
            $grand_total_variance += $total_variance;
            @endphp
            @else
            <tr>
                <td colspan="10" style="text-align: center;">Record not found!</td>
            </tr>
            @endif
        </tbody>
        @endforeach
        <tfoot>
            <tr class="border-black" style="text-align: right; font-weight: bold;">
                <td colspan="4" style="text-align: left;">Grand Total</td>
                <td style="text-align: right;">{{ number_format($grand_total_consume, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_total, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_total_issue, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_total_cost, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_total_variance, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</div>

</html>
