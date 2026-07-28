<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Profit & Loss Report</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Profit & Loss Report</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{config('enum.company_name')}}</b><br>
            <b>Email: </b>{{config('enum.company_email')}} <br>
            <b>Phone No: </b>{{config('enum.company_phone')}}
        </div>
        <div style="float: right;">
            <b>Date: </b>{{ $parms->start_date ?? '' }} To {{ $parms->end_date ?? '' }} <br>
            <b>Currency: </b>{{ $parms->currency ?? '' }}<br>
        </div>
    </div>
    <br><br><br><br>
    @php
        $profit = 0.0;
    @endphp
    <div class="tableFixHead font-color-black border-black" style="padding:10px;">

        <b style="border-bottom: 1px solid #000; font-size: 14px;">Revenue</b>
        <table class="font-12" width="100%">
            <tbody>
                @php
                    $income_total = 0;

                @endphp
                @foreach ($parms->data['revenueAccounts'] as $income)
                    @php
                        $income_total += $income->Credit - $income->Debit;

                    @endphp
                    <tr>
                        <td class="text-left" style=" width:80%;"><span class="link-black" id="goToLedger"
                                href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $income->head_id }}"
                                data-title="{{ $income->code ?? '' }}-{{ $income->name ?? '' }}">{{ $income->code ?? '' }}-{{ $income->name ?? '' }}</span>
                        </td>
                        <td class="text-right" style=" width:20%;">
                            {{ number_format($income->Credit - $income->Debit, 2) }}</td>
                    </tr>
                @endforeach

                @php
                    $profit = $income_total;
                @endphp
            </tbody>
            <tfoot>
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Net Sale:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($profit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        <b style="border-bottom: 1px solid #000;font-size: 14px;">Cost of sales</b>
        <table class="font-12" width="100%">
            <tbody>
                @php
                    $cost_total = 0;

                @endphp
                @foreach ($parms->data['costRevenueAccounts'] as $cost)
                    @php
                        $cost_total += $cost->Debit - $cost->Credit;

                    @endphp
                    <tr>
                        <td class="text-left" style=" width:80%;"><span class="link-black" id="goToLedger"
                                href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $cost->head_id }}"
                                data-title="{{ $cost->code ?? '' }}-{{ $cost->name ?? '' }}">{{ $cost->code ?? '' }}-{{ $cost->name ?? '' }}</span>
                        </td>
                        <td class="text-right" style=" width:20%;">{{ number_format($cost->Debit - $cost->Credit, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Total:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($cost_total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Start Opening & Closing Stock Cost -->
        {{-- <table class="font-12" width="100%">
            <tbody>
                @php
                    $opening_stock_cost = number_format($parms->data['opening_stock_amount'] ?? 0, 2);
                    $closing_stock_cost = number_format($parms->data['closing_stock_amount'] ?? 0, 2);
                @endphp
                <tr>
                    <td class="text-left" style=" width:80%;">Opening Stock Cost</td>
                    <td class="text-right" style=" width:20%;">{{ $opening_stock_cost }}</td>
                </tr>
                <tr>
                    <td class="text-left" style=" width:80%;">Closing Stock Cost</td>
                    <td class="text-right" style=" width:20%;">{{ $closing_stock_cost }}</td>
                </tr>
                @php
                    $opening_stock_cost = str_replace(',', '', $opening_stock_cost);
                    $opening_stock_cost = floatval($opening_stock_cost);

                    $closing_stock_cost = str_replace(',', '', $closing_stock_cost);
                    $closing_stock_cost = floatval($closing_stock_cost);

                    $contribution_margin = $opening_stock_cost + $cost_total - $closing_stock_cost;
                    $profit = $profit - $contribution_margin;
                @endphp
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Cost of good sold:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($contribution_margin, 2) }}</td>
                </tr>
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Contribution Margin:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($profit, 2) }}</td>
                </tr>
            </tbody>
        </table> --}}
        <!-- End Opening & Closing Stock Cost -->

        <!-- Start direct Expense -->
        <b style="border-bottom: 1px solid #000;font-size: 14px;">Direct Expense</b>
        <table class="font-12" width="100%">
            <tbody>
                @php
                    $direct_expense_total = 0;

                @endphp
                @foreach ($parms->data['directExpenseAccounts'] as $direct_expense)
                    @php
                        $direct_expense_total += $direct_expense->Debit;

                    @endphp
                    <tr>
                        <td class="text-left" style=" width:80%;"><span class="link-black" id="goToLedger"
                                href="javascript:void(0)" data-toggle="tooltip"
                                data-id="{{ $direct_expense->head_id }}"
                                data-title="{{ $direct_expense->code ?? '' }}-{{ $direct_expense->name ?? '' }}">{{ $direct_expense->code ?? '' }}-{{ $direct_expense->name ?? '' }}</span>
                        </td>
                        <td class="text-right" style=" width:20%;">{{ number_format($direct_expense->Debit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php

                    // Less direct expenses cost
                    $profit = $profit - $direct_expense_total;
                @endphp
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Total:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($direct_expense_total, 2) }}</td>
                </tr>
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Gross Profit:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($profit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        <!-- End direct Expense -->
        <!-- Start indirect expenses -->
        <b style="border-bottom: 1px solid #000;font-size: 14px;">Indirect Expenses</b>
        <table class="font-12" width="100%">
            <tbody>
                @php
                    $indirect_expense_total = 0;

                @endphp
                @foreach ($parms->data['indirectExpenseAccounts'] as $indirect_expense)
                    @php
                        $indirect_expense_total += $indirect_expense->Debit;

                    @endphp
                    <tr>
                        <td class="text-left" style=" width:80%;"><span class="link-black" id="goToLedger"
                                href="javascript:void(0)" data-toggle="tooltip"
                                data-id="{{ $indirect_expense->head_id }}"
                                data-title="{{ $indirect_expense->code ?? '' }}-{{ $indirect_expense->name ?? '' }}">{{ $indirect_expense->code ?? '' }}-{{ $indirect_expense->name ?? '' }}</span>
                        </td>
                        <td class="text-right" style=" width:20%;">{{ number_format($indirect_expense->Debit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php

                    // Less indirect expenses cost
                    $profit = $profit - $indirect_expense_total;
                @endphp
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Total:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($indirect_expense_total, 2) }}</td>
                </tr>
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">Net Profit With Tax:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($profit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        <!-- End indirect expenses -->
        <b style="border-bottom: 1px solid #000;font-size: 14px;">Other Income</b>
        <table class="font-12" width="100%">
            <tbody>
                @php
                    $other_income_total = 0;

                @endphp
                @foreach ($parms->data['otherIncomeAccounts'] as $other_income)
                    @php
                        $other_income_total += $other_income->Credit - $other_income->Debit;

                    @endphp
                    <tr>
                        <td class="text-left" style=" width:80%;"><span class="link-black" id="goToLedger"
                                href="javascript:void(0)" data-toggle="tooltip"
                                data-id="{{ $other_income->head_id }}"
                                data-title="{{ $other_income->code ?? '' }}-{{ $other_income->name ?? '' }}">{{ $other_income->code ?? '' }}-{{ $other_income->name ?? '' }}</span>
                        </td>
                        <td class="text-right" style=" width:20%;">
                            {{ number_format($other_income->Credit - $other_income->Debit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php

                    // Add other income
                    $profit = $profit + $other_income_total;
                @endphp
                <tr style="text-align: right;font-weight: bold;">
                    <td style="text-align: right; width:80%;">With Other:</td>
                    <td style="text-align: right; border-bottom: 1px solid #000; width:20%;">
                        {{ number_format($profit, 2) }}</td>
                </tr>
            </tfoot>
        </table>


    </div>

</div>

</html>
