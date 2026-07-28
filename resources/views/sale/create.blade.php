@php
$gold_rate = GoldRate();
@endphp
@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="row pl-2">
        <div class="col-md-8 breadcrumb">
            <h1>Sale</h1>
            @if (isset($sale))
            <ul>
                <li>Edit</li>
                <li>Update</li>
            </ul>
            @else
            <ul>
                <li>Create</li>
                <li>Save</li>
            </ul>
            @endif
        </div>
        <div class="col-md-4">
            <input type="text" name="sale_no" id="sale_no"
                value="{{ isset($sale) ? $sale->sale_no : 'SL-05102024-0009' }}"
                style="border:none; background:none; font-size:20px; font-weight:bold;"
                class="form-control text-center bg-light">
        </div>
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <!-- end of row -->
    <section class="contact-list">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-transparent text-right">

                    </div>

                    <form id="saleForm" action="#" method="POST" enctype="multipart/form-data">
                        <div class="card-body" style="font-size: 14px;">
                            {{-- Edit Form  --}}
                            @csrf
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Sale Date:<span
                                                        style="color:red;">*</span></label>
                                                <input type="date" name="sale_date" id="sale_date"
                                                    class="form-control"
                                                    value="{{ isset($sale) ? $sale->sale_date : old('sale_date') }}"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Customer: <span class="text-danger">*</span>
                                                    <a href="javascript:void(0)" id="CustomerButton"
                                                        style="border: 1px solid #000; border-radius: 50%;padding: 3px 5px 3px 5px;"
                                                        class="btn-primary"><i class="fa fa-plus text-white"></i></a>
                                                </label>
                                                <select id="customer_id" name="customer_id"
                                                    class="form-control show-tick">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Search By Tag No:</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Search By Tag No" name="search_tag_no"
                                                    id="search_tag_no">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select By Tag No:</label>
                                                <select id="select_tag_no" name="select_tag_no"
                                                    class="form-control show-tick">
                                                    @foreach ($finish_product as $item)
                                                    <option value="{{ $item->tag_no }}"
                                                        @if (isset($sale)) {{ $sale->finish_product_id == $item->id ? 'selected' : '' }} @endif>
                                                        {{ $item->tag_no ?? '' }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="card" style="border-radius:0px;">
                                        <div class="card-header bg-transparent"
                                            style="border-radius:0px; padding: 6px;">
                                            <b>Customer Detail</b>
                                        </div>
                                        <div class="card-body" style="min-height: 60px; overflow: auto; padding: 8px;"
                                            id="customer">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" class="form-control" value="{{ isset($sale) ? $sale->id : '' }}"
                                    id="id" name="id">
                                <div class="col-md-12">
                                    <hr class="mb-2 mt-2">
                                    <b>Add Sale Detail:</b>
                                </div>
                                <input type="hidden" name="finish_product_id" id="finish_product_id" value="">
                                <input type="hidden" name="ratti_kaat_id" id="ratti_kaat_id" value="">
                                <input type="hidden" name="ratti_kaat_detail_id" id="ratti_kaat_detail_id" value="">
                                <input type="hidden" name="job_purchase_detail_id" id="job_purchase_detail_id" value="">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Tag No:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="tag_no" id="tag_no" class="form-control"
                                                    required maxlength="10" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="product_id" id="product_id">
                                    <div class="form-group">
                                        <label class="form-label">Product:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="product" id="product"
                                                    class="form-control" required
                                                    onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Karat:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="gold_carat" id="gold_carat"
                                                    class="form-control" required
                                                    onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Scale Weight:<span
                                                style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="scale_weight" id="scale_weight"
                                                    class="form-control" required
                                                    onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Bead Weight:<span class="text-danger">*</span>
                                            <a href="javascript:void(0)" id="BeadWeightButton"
                                                style="border: 1px solid #000; border-radius: 50%;padding: 3px 5px 3px 5px;"
                                                class="btn-primary"><i class="fa fa-eye text-white"></i></a>
                                        </label>
                                        <input type="text" id="bead_weight" name="bead_weight"
                                            class="form-control" onkeypress="return isNumberKey(event)"
                                            value="0" readonly required min="0" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Stone Weight:<span class="text-danger">*</span>
                                            <a href="javascript:void(0)" id="StonesWeightButton"
                                                style="border: 1px solid #000; border-radius: 50%;padding: 3px 5px 3px 5px;"
                                                class="btn-primary"><i class="fa fa-eye text-white"></i></a>
                                        </label>
                                        <input type="text" id="stones_weight" name="stones_weight"
                                            class="form-control" value="0" readonly
                                            onkeypress="return isNumberKey(event)" required min="0" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Diamond Weight:<span class="text-danger">*</span>
                                            <a href="javascript:void(0)" id="DiamondCaratButton"
                                                style="border: 1px solid #000; border-radius: 50%;padding: 3px 5px 3px 5px;"
                                                class="btn-primary"><i class="fa fa-eye text-white"></i></a>
                                        </label>
                                        <input type="text" id="diamond_weight" name="diamond_weight"
                                            class="form-control" readonly value="0"
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Net Weight:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="net_weight" name="net_weight" class="form-control"
                                            readonly value="0" onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Gross Weight:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="gross_weight" name="gross_weight"
                                            class="form-control" readonly value="0"
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Waste:<span class="text-danger">*</span></label>
                                        <input type="text" id="waste" name="waste" min="10"
                                            class="form-control" onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Making:<span class="text-danger">*</span></label>
                                        <input type="text" id="making" name="making" class="form-control"
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Bead Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="total_bead_price" name="total_bead_price"
                                            class="form-control" readonly value="0"
                                            onkeypress="return isNumberKey(event)" required min="0" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Stones Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="total_stones_price" name="total_stones_price"
                                            class="form-control" readonly value="0"
                                            onkeypress="return isNumberKey(event)" required min="0" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Diamond Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="total_diamond_price" name="total_diamond_price"
                                            class="form-control" readonly value="0"
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Other Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="other_amount" name="other_amount"
                                            class="form-control" value="0"
                                            onkeypress="return isNumberKey(event)" required min="0" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Gold Rate/Gram:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $gold_rate->rate_gram }}" id="gold_rate"
                                            name="gold_rate" class="form-control"
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Gold Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="0" id="total_gold_price"
                                            name="total_gold_price" class="form-control"
                                            onkeypress="return isNumberKey(event)" readonly required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Amount:<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="total_amount" name="total_amount"
                                            class="form-control" value="0" readonly
                                            onkeypress="return isNumberKey(event)" required />
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-2 mt-4">
                                    <a class="btn btn-primary waves-effect text-center text-white" style="width:100%;"
                                        onclick="addProduct()">
                                        <i class="fa fa-plus"></i> Add
                                    </a>
                                </div>

                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table style="min-height: 200px;"
                                        class="table table-striped table-hover dataTable js-exportable mb-3">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Tag No</th>
                                                <th>Item</th>
                                                <th>Karat</th>
                                                <th>Scale Wt</th>
                                                <th>Beads Wt</th>
                                                <th>Stones Wt</th>
                                                <th>Diamond Carat</th>
                                                <th>Net Wt</th>
                                                <th>Waste</th>
                                                <th>Gross Wt</th>
                                                <th>Gold Rate/Gram</th>
                                                <th>Gold Value</th>
                                                <th>Making</th>
                                                <th>Other Chargs</th>
                                                <th>Total(PKR)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products">

                                        </tbody>

                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <div class="row">
                                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Subtotal(PKR):<span class="text-danger">*</span> </label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="grand_total"
                                                            value="{{ isset($sale) ? $sale->sub_total : '0' }}"
                                                            id="grand_total" class="form-control font-weight-bold"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Discount:</label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="discount_amount"
                                                            value="{{ isset($sale) ? $sale->discount_amount : 0 }}"
                                                            id="discount_amount" class="form-control font-weight-bold">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Total(PKR):<span class="text-danger">*</span> </label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="grand_total_total"
                                                            value="{{ isset($sale) ? $sale->total : '0' }}"
                                                            id="grand_total_total" class="form-control font-weight-bold"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-md-12">
                                <button class="btn btn-primary" id="submit" accesskey="s"
                                    tabindex="10">SAVE</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end of col -->
            </div>
            <!-- end of row -->
        </div>
    </section>

</div>
@include('sale/Modal/CustomerModal')
@include('sale/Modal/BeadModal')
@include('sale/Modal/DiamondModal')
@include('sale/Modal/StonesModal')
@include('sale/Modal/DetailModal')
@endsection
@section('js')
<script src="{{ url('sale/js/sale.js') }}"></script>
<script src="{{ url('sale/js/beadWeight.js') }}"></script>
<script src="{{ url('sale/js/stoneWeight.js') }}"></script>
<script src="{{ url('sale/js/diamondCarat.js') }}"></script>
<script type="text/javascript">
    var url_local = "{{ url('/') }}";
    var sale_id = "{{ isset($sale) ? $sale->id : '' }}";
    var gold_rate = "{{ $gold_rate->rate_tola ?? 0 }}";
</script>

<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        $('#select_tag_no').select2();
        getCustomer();
        const sale_date = document.getElementById("sale_date");

        // ✅ Using the visitor's timezone
        sale_date.value = formatDate();

        console.log(formatDate());

        function padTo2Digits(num) {
            return num.toString().padStart(2, "0");
        }

        function formatDate(date = new Date()) {
            return [
                padTo2Digits(date.getMonth() + 1),
                padTo2Digits(date.getDate()),
                date.getFullYear(),
            ].join("/");
        }
        sale_date.value = new Date().toISOString().split("T")[0];
    });
</script>
@endsection