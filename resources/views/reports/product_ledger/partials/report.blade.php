<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Product Ledger</title>
    <link rel="stylesheet" href="{{ asset('public/css/report-view.css') }}">
</head>

<div class="font-color-black">


    <center style="float:none;">
        <h4><b>Product ledger</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{config('enum.company_name')}}</b><br>
            <b>Email: </b>{{config('enum.company_email')}} <br>
            <b>Phone No: </b>{{config('enum.company_phone')}}
        </div>
        <div style="float: right;">
            <b>Date: </b>{{ $start ?? '' }} To {{ $end ?? '' }} <br>
            <b>Warehouse: </b>{{ $warehouse->name ?? 'All' }}<br>
        </div>
    </div>
    <br><br><br>
    <table class="font-color-black" border="1" style="border:0.5px solid #000; float:none; width:100%;">
        @php
        $grand_stock = 0.0;
        $grand_total_stock_in = 0.0;
        $grand_total_stock_out = 0.0;

        @endphp
        @foreach ($data as $item1)
        <thead>
            <tr class="border-black">
                <th style="text-align: left;" colspan="6"><b>{{$item1['product_name']??''}}</b></th>
            </tr>
            <tr class="border-black">
                <th style="text-align: center;">Date</th>
                <th style="text-align: center;">Warehouse</th>
                <th style="text-align: center;">Type</th>
                <th style="text-align: center;">Stock In</th>
                <th style="text-align: center;">Stock Out</th>
                <th style="text-align: center;">Stock</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5"><b>Opening Stock</b></td>
                <td style="text-align: right;"><b>{{ number_format($item1['opening_stock']??0, 2) }}</b></td>
            </tr>
            @foreach ($item1['report'] as $item)
            <tr>
                <td style="text-align: left;">{{ $item['date'] ?? '' }}</td>
                <td style="text-align: left;">{{$item['warehouse']??''}}</td>
                <td style="text-align: left;">{{$item['type']??''}}</td>
                <td style="text-align: right;">{{ number_format($item['stock_in']??0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item['stock_out']??0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item['stock']??0, 2) }}</td>
            </tr>

            @endforeach
            @php
            $grand_stock += $item1['total_stock']??0;
            $grand_total_stock_in += $item1['total_stock_in']??0;
            $grand_total_stock_out += $item1['total_stock_out']??0;

            @endphp

            <tr style="text-align: right;font-weight: bold; border:1px solid #000;">
                <td colspan="3" style="text-align: left;">Total</td>
                <td style="text-align: right;">{{ number_format($item1['total_stock_in']??0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item1['total_stock_out']??0, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item1['total_stock']??0, 2) }}</td>
            </tr>
        </tbody>
        @endforeach
        <tfoot>
            <tr style="text-align: right;font-weight: bold;">
                <td colspan="3" style="text-align: left;">Grand Total</td>
                <td style="text-align: right;">{{ number_format($grand_total_stock_in, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_total_stock_out, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grand_stock, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</div>

</html>