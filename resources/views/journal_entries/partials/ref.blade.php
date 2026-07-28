<html lang="en">

<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Journal Entry</title>
</head>

<body>

      @foreach ($journal_entries as $journal_entry)
      <div>
            <div style="float:left;">
                  <b>Entry No.: </b>{{ $journal_entry['entryNum'] }} <br>
                  <b>Entry Date: </b>{{ $journal_entry['date_post'] }} <br>

            </div>
            <div style="float: right;">
                  <b>Journal: </b>{{ $journal_entry['journal_name'] }} <br>
                  <b>Supplier/Karigar: </b>{{ $journal_entry['supplier_name'] }} <br>
            </div>
      </div>
      <br><br>
      <table border="1" style="border:1px dotted #000; float:none; width:100%;">
            <thead>
                  <tr style="background: #87CEFA;color: #000;">
                        <th style="text-align:center; font-size:12px;">Sr. No.</th>
                        <th style="text-align:center; font-size:12px;">Bill No.</th>
                        <th style="text-align:center; font-size:12px;">Currency</th>
                        <th style="text-align:center; font-size:12px;">Account</th>
                        <th style="text-align:center; font-size:12px;">Explanation</th>
                        <th style="text-align:right; font-size:12px;">Debit</th>
                        <th style="text-align:right; font-size:12px;">Credit</th>
                  </tr>
            </thead>

            <tbody>
                  @php
                  $total_debit=0;
                  $total_credit=0;
                  @endphp
                  @foreach ($journal_entry['journal_entry_detail'] as $index=>$item)
                  @php
                  $total_debit+=$item->debit??0.00;
                  $total_credit+=$item->credit??0.00;
                  @endphp
                  <tr>
                        <td style="text-align:center; font-size:12px;">{{ $index+1 }}</td>
                        <td style="text-align:center; font-size:12px;">{{ $item->bill_no??'' }}</td>
                        <td style="text-align:center; font-size:12px;">{{ Currency($item->currency??'') }}</td>
                        <td style="text-align:left; font-size:12px;">{{$item->account_code??''}} {{ $item->account_name->name??'' }}</td>
                        <td style="text-align:left; font-size:12px;">{{ $item->explanation??'' }}</td>
                        <td style="text-align:right; font-size:12px;">{{ number_format($item->debit??0.000,3) }}</td>
                        <td style="text-align:right; font-size:12px;">{{ number_format($item->credit??0.000,3) }}</td>
                  </tr>
                  @endforeach
                  <tr>
                        <td colspan="5" style="text-align:left; font-weight: bold; font-size:12px;">Total</td>
                        <td style="text-align:right; font-weight: bold; font-size:12px;">{{ number_format($total_debit??0.000,3) }}</td>
                        <td style="text-align:right; font-weight: bold; font-size:12px;">{{ number_format($total_credit??0.000,3) }}</td>
                        <td></td>
                  </tr>

            </tbody>

      </table>
      <b>Reference:</b> {{ $journal_entry['reference'] }}
      <hr>
      @endforeach
</body>

</html>