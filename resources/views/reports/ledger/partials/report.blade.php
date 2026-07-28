<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ledger Report</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">

    <center style="float:none;">
        <h4><b>Ledger Report</b></h4>
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
            <b>Supplier/Karigar: </b>{{ $parms->supplier ?? 'All' }}<br>
            <b>Customer: </b>{{ $parms->customer ?? 'All' }}<br>
        </div>
    </div>
    <br><br><br><br>

    <table border="1" width="100%">

        <thead>
            <tr class="border-black">
                <th style="text-align: center;">Sr.</th>
                <th style="text-align: center;">Date</th>
                <th style="text-align: center;">Voucher No</th>
                <th style="text-align: center;">Account</th>
                <th style="text-align: center;">Reference</th>
                <th style="text-align: center;">Debit</th>
                <th style="text-align: center;">Credit</th>
                <th style="text-align: center;">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sum_drbal = 0;
                $sum_crbal = 0;
                $rBal = 0;
                $opening = 0;

            @endphp
            @foreach ($parms->data['opening_balance'] as $item)
                @php
                    $opening = $opening + ($item['debit'] - $item['credit']);
                    $rBal = $opening;
                @endphp
            @endforeach
            <tr class="border-black" style="font-weight: bold;">
                <td colspan="7" style="text-align: left;">Opening Balance</td>
                <td style="color:black;text-align:right;">
                    {{ number_format($rBal, 3, '.', ',') }}
                </td>
            </tr>
            @if ($parms->data['journal_entry'] != null)
                @foreach ($parms->data['journal_entry'] as $index => $ledger)
                    @php
                        $sum_drbal += $ledger['debit'] ?? 00;
                        $sum_crbal += $ledger['credit'] ?? 0;
                        $rBal = $rBal + ($ledger['debit'] - $ledger['credit']);

                    @endphp
                    <tr>

                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $ledger['date_post'] }}</td>
                        <td id="example2">{{ $ledger['entryNum'] }}
                            <input type="hidden" value="{{ $ledger['entryNum'] }}">
                        </td>
                        <td>{{ $ledger['code'] ?? '' }} {{ $ledger['name'] ?? '' }}</td>
                        <td>{{ $ledger['reference'] ?? 'N/A' }}</td>
                        <td style="font-family:tahoma;color:black;text-align:right;">
                            {{ number_format($ledger['debit'], 3, '.', ',') }}
                        </td>
                        <td style="font-family:tahoma;color:black;text-align:right;">
                            {{ number_format($ledger['credit'], 3, '.', ',') }}
                        </td>
                        <td style="font-family:tahoma;color:black;text-align:right;">

                            {{ number_format($rBal, 3, '.', ',') }}

                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">No Data/Record found</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="5" style="text-align: left;">Total</td>
                <td style="text-align: right;"> <span
                        id='sum_debit'>{{ number_format($sum_drbal, 3, '.', ',') }}</span></td>
                <td style="text-align: right;"> <span
                        id='sum_credit'>{{ number_format($sum_crbal, 3, '.', ',') }}</span></td>
                <td style="text-align: right;"> <span id='sum_bal'>{{ number_format($rBal, 3, '.', ',') }}</span>
                </td>

            </tr>
        </tfoot>
    </table>

</div>

</html>

{{-- @php
    exit();
@endphp --}}
