<html lang="en">

<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Journal Entry</title>
      <link rel="stylesheet" href="{{ asset('public/assets/styles/css/pdf.css') }}">
</head>

<body>
      <center style="float:none; margin-top:20px;">
            <h3>Journal Entry</h3>
      </center>
      <div>
            <div style="float:left;">
                  <b style="font-size: 13px;">{{ $journal_entry->restaurant_name->name ?? '' }}</b><br>
                  <small><b>Email: </b>{{ $journal_entry->restaurant_name->contact_email ?? '' }}</small> <br>
                  <small><b>Phone No.: </b>{{ $journal_entry->restaurant_name->contact_number ?? '' }}</small>
            </div>
            <div style="float: right;">
                  <small><b>Entry No.: </b>{{ $journal_entry->entryNum??'' }}</small> <br>
                  <small><b>Entry Date: </b>{{ date('d-M-Y', strtotime($journal_entry->date_post)) }} </small> <br>
                  <small><b>Journal: </b>{{ $journal_entry->journal_name->name??'' }} </small> <br>
                  <small><b>Vendor: </b>{{ $journal_entry->vendor_name->first_name??'' }} {{ $journal_entry->vendor_name->last_name??'' }}</small> <br>
                  <!-- <small><b>Project: </b>{{ $purchase->project->title??'' }}</small> -->
            </div>
      </div>
      <br><br><br><br><br>
      <table border="1" style="border:1px dotted #000; float:none; width:100%;">
            <thead>
                  <tr style="background: #87CEFA;color: #000;">
                        <th style="text-align:center; font-size:11px;">Sr. No.</th>
                        <th style="text-align:center; font-size:11px;">Bill No.</th>
                        <th style="text-align:center; font-size:11px;">Check No.</th>
                        <th style="text-align:center; font-size:11px;">Account</th>
                        <th style="text-align:right; font-size:11px;">Debit</th>
                        <th style="text-align:right; font-size:11px;">Credit</th>
                        <th style="text-align:center; font-size:11px;">Explanation</th>
                  </tr>
            </thead>

            <tbody>
                  @foreach ($journal_entry_detail as $index=>$item)
                  <tr>
                        <td style="text-align:center; font-size:10px;">{{ $index+1 }}</td>
                        <td style="text-align:center; font-size:10px;">{{ $item->bill_no??'' }}</td>
                        <td style="text-align:center; font-size:10px;">{{ $item->check_no??'' }}</td>
                        <td style="text-align:center; font-size:10px;">{{$item->account_code??''}} {{ $item->account_name->name??'' }}</td>
                        <td style="text-align:right; font-size:10px;">{{ number_format($item->debit??0.00,2) }}</td>
                        <td style="text-align:right; font-size:10px;">{{ number_format($item->credit??0.00,2) }}</td>
                        <td style="text-align:center; font-size:10px;">{{ $item->explanation??'' }}</td>
                  </tr>
                  @endforeach

            </tbody>

      </table>
      <small><b>Reference:</b> {{ $journal_entry->reference??'' }}</small><br>
      <small><b>Prepared By:</b> {{ $journal_entry->restaurant_name->name ?? '' }}</small>
</body>

</html>