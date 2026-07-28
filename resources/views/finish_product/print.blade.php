<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <style>
            body {
                  font-family: Arial, sans-serif;
                  display: flex;
                  flex-wrap: wrap;
                  justify-content: center;
                  margin: 20px;
            }

            

      </style>
</head>

<body>
      <table>
            <tbody>
                  <tr>
                        <td></td>
                        <td></td>
                        <td style="min-width: 150px;text-align: left;"><small>{{$finish_product_1->product->name??''}}</small></td>
                        <td><img style="width:140px; height: 20px;" src="{{ asset($finish_product_1->barcode) }}" alt=""></td>
                  </tr>
                  <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;"><small>Al Saeed Jewellers</small></td>
                  </tr>
                  <tr>
                        <td style="display: flex
;
    flex-direction: column;
    transform: translateY(-42%) rotate(-90deg);
    transform-origin: center;">
                              <small>{{ $finish_product_1->tag_no ?? '' }}</small> 
                              <small>Ntw {{$finish_product_1->net_weight??0.00}}</small>
                              <small>Wst {{$finish_product_1->waste??0.00}}</small> 
                              <small>Gw {{$finish_product_1->gross_weight??0.00}}</small> <br>
                              <small>Dw {{$finish_product_1->diamond_weight??0.00}} Ct</small> <br>
                              <small>Kt {{$finish_product_1->waste_per??0.00}}</small>
                        </td>
                        <td></td>
                        <td></td>
                        <td style="display: flex; flex-direction: column; transform: translateY(33%) rotate(-270deg); transform-origin: center;">
                              <small>{{ $finish_product_2->tag_no ?? '' }}</small> 
                              <small>Ntw {{$finish_product_2->net_weight??0.00}}</small>
                              <small>Wst {{$finish_product_2->waste??0.00}}</small> 
                              <small>Gw {{$finish_product_2->gross_weight??0.00}}</small> <br>
                              <small>Dw {{$finish_product_2->diamond_weight??0.00}} Ct</small> <br>
                              <small>Kt {{$finish_product_2->waste_per??0.00}}</small>
                        </td>
                  </tr>
                  <tr>
                        <td><img style="width:140px; height: 20px;" src="{{ asset($finish_product_2->barcode) }}" alt=""></td>
                        <td style="min-width: 150px;text-align: right;"><small>{{$finish_product_2->product->name??''}}</small></td>
                        <td></td>
                        <td></td>
                  </tr>
                  <tr>
                        <td style="text-align: center;"><small>Al Saeed Jewellers</small></td>
                        <td></td>
                        <td></td>
                        <td></td>
                  </tr>
            </tbody>
      </table>
      <!-- <div id="printableArea" class="print-area">
            <div class="tag">
                  <div class="barcode"></div>
                  <div class="weight-container">
                        <div>{{ $finish_product->tag_no ?? '' }}</div>
                        <div>Ntw 5.342</div>
                        <div>Wst 1.200</div>
                        <div>Gw 6.542</div>
                        <div>DW 0.00 Ct</div>
                        <div>Kt 21</div>
                  </div>
                  <div class="product-name">Ladies Rings</div>
                  <div class="store">Al Saeed Jewellers</div>
            </div>

            <div class="tag">
                  <div class="barcode">*LR00011*</div>
                  <div class="weight-container">
                        <div>LR00011</div>
                        <div>Ntw 6.746</div>
                        <div>Wst 1.349</div>
                        <div>Gw 8.095</div>
                        <div>DW 0.00 Ct</div>
                        <div>Kt 21</div>
                  </div>
                  <div class="product-name">Ladies Rings</div>
                  <div class="store">Al Saeed Jewellers</div>
            </div>
      </div> -->
      <!-- <button onclick="printDiv('printableArea')">Print</button> -->

      <script>
            function printDiv(divId) {
                  var printContents = document.getElementById(divId).innerHTML;
                  var originalContents = document.body.innerHTML;

                  document.body.innerHTML = printContents;
                  window.print();
                  document.body.innerHTML = originalContents;
                  location.reload(); // Refresh to restore the original page
            }
      </script>
</body>

</html>