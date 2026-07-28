<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Stock Ledger</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Stock ledger</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{config('enum.company_name')}}</b><br>
            <b>Email: </b>{{config('enum.company_email')}}<br>
            <b>Phone No: </b>{{config('enum.company_phone')}}
        </div>
        <div style="float: right;">
            <b>Date: </b>{{ $parms->start_date ?? '' }} To {{ $parms->end_date ?? '' }} <br>
            <b>Warehouse: </b>{{ $parms->warehouse ?? '' }} <br>
        </div>
    </div>
    <br><br><br>

    <div class="tableFixHead font-color-black">

        <table class="font-x-medium" border="1" style="border: 0.5px solid black;" width="100%">
            @php
                $grand_total_opening_qty = 0;
                $grand_total_opening_amount = 0;
                $grand_stock_in_qty = 0;
                $grand_total_stock_in_amount = 0;
                $grand_stock_out_qty = 0;
                $grand_total_stock_out_amount = 0;
                $grand_total_closing_qty = 0;
                $grand_total_closing_amount = 0;
            @endphp
            @foreach ($parms->data as $warehouse)
                <thead>
                    <tr style=" border:1px solid #000;">
                        <td colspan="11"><b>{{ $warehouse['warehouse_name'] }}</b></td>
                    </tr>
                    <tr style=" border:1px solid #000;">
                        <th colspan="3"></th>
                        <th colspan="2" class="text-center">Opening</th>
                        <th colspan="2" class="text-center">Stock In</th>
                        <th colspan="2" class="text-center">Stock Out</th>
                        <th colspan="2" class="text-center">Closing</th>
                    </tr>
                    <tr style=" border:1px solid #000;">
                        <th class="text-center">Product</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_opening_qty = 0;
                        $total_opening_amount = 0;
                        $stock_in_qty = 0;
                        $total_stock_in_amount = 0;
                        $stock_out_qty = 0;
                        $total_stock_out_amount = 0;
                        $total_closing_qty = 0;
                        $total_closing_amount = 0;

                    @endphp
                    @foreach ($warehouse['data'] as $item)
                        @php

                            $sub_closing_qty = 0;
                            $sub_closing_amount = 0;

                            $item = (array) $item;
                            $opening_qty = $item['opening_qty'];
                            $opening_amount = $item['unit_price'] * $item['opening_qty'];
                            $total_opening_qty += $opening_qty;
                            $total_opening_amount += $opening_amount;
                            $stock_in_qty += $item['stock_in_qty'];
                            $stock_in_amount = $item['unit_price'] * $item['stock_in_qty'];
                            $total_stock_in_amount += $stock_in_amount;
                            $stock_out_qty += $item['stock_out_qty'];
                            $stock_out_amount = $item['unit_price'] * $item['stock_out_qty'];
                            $total_stock_out_amount += $stock_out_amount;

                            $sub_closing_qty = $item['opening_qty'] + $item['stock_in_qty'] - $item['stock_out_qty'];
                            $sub_closing_amount = $opening_amount + $stock_in_amount - $stock_out_amount;

                            $total_closing_qty += $sub_closing_qty;
                            $total_closing_amount += $sub_closing_amount;

                        @endphp
                        @if ($opening_qty != 0 || $item['stock_in_qty'] != 0 || $item['stock_out_qty'] != 0 || $sub_closing_qty != 0)
                            <tr>
                                <td class="text-left">{{ $item['product'] ?? '' }}</td>
                                <td class="text-left">{{ $item['unit'] ?? '' }}</td>
                                <td class="text-right">{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($opening_qty, 2) }}</td>
                                <td class="text-right">{{ number_format($opening_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($item['stock_in_qty'], 2) }}</td>
                                <td class="text-right">{{ number_format($stock_in_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($item['stock_out_qty'], 2) }}</td>
                                <td class="text-right">{{ number_format($stock_out_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($sub_closing_qty, 2) }}</td>
                                <td class="text-right">{{ number_format($sub_closing_amount, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @php
                        $grand_total_opening_qty += $total_opening_qty;
                        $grand_total_opening_amount += $total_opening_amount;
                        $grand_stock_in_qty += $stock_in_qty;
                        $grand_total_stock_in_amount += $total_stock_in_amount;
                        $grand_stock_out_qty += $stock_out_qty;
                        $grand_total_stock_out_amount += $total_stock_out_amount;
                        $grand_total_closing_qty += $total_closing_qty;
                        $grand_total_closing_amount += $total_closing_amount;
                    @endphp
                    <tr style="text-align: right;font-weight: bold; border:1px solid #000;">
                        <td colspan="3" style="text-align: left;">Total</td>
                        <td style="text-align: right;">{{ number_format($total_opening_qty, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($total_opening_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($stock_in_qty, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($total_stock_in_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($stock_out_qty, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($total_stock_out_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($total_closing_qty, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($total_closing_amount, 2) }}</td>
                    </tr>
                </tbody>
            @endforeach
            <tfoot>
                <tr style="text-align: right;font-weight: bold; border:1px solid #000;">
                    <td colspan="3" style="text-align: left;">Grand Total</td>
                    <td style="text-align: right;">{{ number_format($grand_total_opening_qty, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_total_opening_amount, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_stock_in_qty, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_total_stock_in_amount, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_stock_out_qty, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_total_stock_out_amount, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_total_closing_qty, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($grand_total_closing_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

</html>
