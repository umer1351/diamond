@extends('layouts.master')
@section('content')
<div class="breadcrumb mt-4">
      <h1>Stock Taking</h1>

      <ul>
            <li>List</li>
            <li>All</li>
      </ul>
</div>
<div class="separator-breadcrumb border-top"></div>
<!-- end of row -->
<section class="contact-list">
      <div class="row">

            <div class="col-md-12 mb-4">

                  <div class="card text-left">
                        <div class="card-header text-right bg-transparent">
                                    <button class="btn btn-info" onclick="printDiv('printData')" type="submit"><span class="fas fa-print"></span> Print</button>
                        </div>
                        <div class="card-body" id="printData">
                              <div class="row">
                                    <div class="col-md-12">
                                          <center style="float:none;">
                                                <h4><b>Stock Taking</b></h4>
                                          </center>
                                          <div>
                                                <div style="float:left;">
                                                      <b style="font-size: 13px;">{{config('enum.company_name')}}</b><br>
                                                      <b>Email: </b>{{config('enum.company_email')}} <br>
                                                      <b>Phone No: </b>{{config('enum.company_phone')}}<br>
                                                </div>
                                                <div style="float: right;">
                                                      <b>Warehouse: </b>{{ $stock_taking->warehouse_name->name??'' }} <br>
                                                      <b>Date: </b>{{ date('d-M-Y', strtotime($stock_taking->stock_date)) }} <br>
                                                      <b>Creation Date: </b>{{ date('d-M-Y', strtotime($stock_taking->created_at)) }} <br>
                                                      <b>Type: </b>{{ ($stock_taking->is_opening_stock==1)?'Opening Stock':'Stock Taking' }} <br>
                                                </div>
                                          </div>
                                          <br><br><br><br>
                                          <table border="1" style="border:1px dotted #000; float:none; width:100%;">
                                                <thead>
                                                      <tr style="background: #87CEFA;color: #000;">
                                                            <th style="text-align:center;">
                                                                  Product
                                                            </th>
                                                            <th style="text-align:center;">
                                                                  QTY in Stock
                                                            </th>
                                                            <th style="text-align:center;">
                                                                  Actual QTY
                                                            </th>
                                                            <th style="text-align:center;">
                                                                  Unit Price
                                                            </th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      @foreach ($stock_taking_detail as $item)
                                                      <tr>
                                                            <td style="text-align:center;">
                                                                  {{$item['code']??''}} - {{$item['product_name']??''}}
                                                            </td>
                                                            <td style="text-align:right; padding-right:3px;">
                                                                  {{$item['quantity_in_stock']??0}}
                                                            </td>
                                                            <td style="text-align:right; padding-right:3px;">
                                                                  {{$item['actual_quantity']??0}}
                                                            </td>
                                                            <td style="text-align:right; padding-right:5px;">
                                                                  {{$item['unit_price']??''}}
                                                            </td>
                                                      </tr>
                                                      @endforeach

                                                </tbody>

                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>
@endsection
@section('page-js')
<script>
      function printDiv(e) {
            var divToPrint = document.getElementById(e);

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                  newWin.close();
            }, 10);
      }
</script>
@endsection