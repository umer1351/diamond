<html lang="en">

<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Stock Taking</title>
      <link rel="stylesheet" href="{{ asset('public/assets/styles/css/pdf.css') }}">
</head>

<body>
      <center style="float:none; margin-top:20px;">
            <h3>Before Stock Taking</h3>
      </center>
      <div>
            <div style="float:left;">
                  <b style="font-size: 13px;">{{ $parms->restaurant->name ?? '' }}</b><br>
                  <small><b>Email: </b>{{ $parms->restaurant->contact_email ?? '' }}</small> <br>
                  <small><b>Phone No: </b>{{ $parms->restaurant->contact_number ?? '' }}</small><br>
            </div>
            <div style="float: right;">
                  <small><b>Warehouse: </b>{{ $parms->warehouse->name??'' }}</small> <br>
                  <small><b>Date: </b>{{ date('d-M-Y', strtotime($parms->date)) }} </small> <br>
            </div>
      </div>
      <br><br><br><br><br>
      <table border="1" style="border:1px dotted #000; float:none; width:100%;">
            <thead>
                  <tr style="background: #87CEFA;color: #000;">
                        <th style="text-align:center; font-size:11px;">Product Name</th>
                        <th style="text-align:center; font-size:11px;">Stock</th>
                  </tr>
            </thead>

            <tbody>
                  @php
                  $total= 0;
                  @endphp
                  @foreach ($parms->stock_detail as $item)
                  @php
                  $total +=$item['stock']??0;
                  @endphp
                  @if($item['stock']<>0)
                        <tr>
                              <td style="text-align:left; font-size:11px;">{{ $item['product_name']??'' }}</td>
                              <td style="text-align:right; font-size:11px;">{{ $item['stock']??0 }}</td>
                        </tr>
                        @endif
                        @endforeach

            </tbody>
            <tfoot>
                  <tr style="background: #87CEFA;color: #000;">
                        <td style=" font-size:11px;"><b>Total</b></td>
                        <td style="text-align:right; font-size:11px;"><b>{{number_format($total??0.00,2)}}</b></td>
                  </tr>
            </tfoot>

      </table>
</body>

</html>