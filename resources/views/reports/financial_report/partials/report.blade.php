<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Financial Report</title>
    <link rel="stylesheet" href="{{ asset('css/report-view.css') }}">
</head>

<div class="font-color-black">
    <center style="float:none;">
        <h4><b>Financial Report</b></h4>
    </center>
    <div>
        <div style="float:left;">
            <b>{{ config('enum.company_name') }}</b><br>
            <b>Email: </b>{{ config('enum.company_email') }} <br>
            <b>Phone No: </b>{{ config('enum.company_phone') }}
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
                <th style="text-align: center;">Code</th>
                <th style="text-align: center;">Account</th>
                <th style="text-align: center;">Debit</th>
                <th style="text-align: center;">Credit</th>
                <th style="text-align: center;">Balance</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @if ($parms->data != null)
                @foreach ($parms->data as $index => $financial)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $financial['code'] ?? '' }} </td>
                        <td>{{ $financial['name'] ?? '' }}</td>
                        <td style="font-family:tahoma;color:black;text-align:right;">
                            {{ number_format($financial['total_debit'], 3, '.', ',') }}
                        </td>
                        <td style="font-family:tahoma;color:black;text-align:right;">
                            {{ number_format($financial['total_credit'], 3, '.', ',') }}
                        </td>
                        <td style="font-family:tahoma;color:black;text-align:right;">
                            {{ number_format($financial['balance'], 3, '.', ',') }}
                        </td>
                        <td style="text-align: center;">
                            @if($financial['balance']<0)
                            <span class="badge badge-danger p-1"> <i class="i-Thumbs-Down-Smiley"></i> Payable<span>
                            @elseif($financial['balance']>0)
                            <span class="badge badge-primary p-1"> <i class="i-Thumbs-Up-Smiley"></i> Receivable<span>
                            @else
                            <span class="badge badge-success p-1"> <i class="i-Love1"></i> Equal<span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">No Data/Record found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

</html>
