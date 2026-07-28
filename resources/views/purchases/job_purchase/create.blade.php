@php
    $dollar_rate = DollarRate();
@endphp
@extends('layouts.master')
@section('css')
    <style>
        .job_task_detail_active {
            background: green;
            color: #fff;

        }
    </style>
@endsection
@section('content')
    <div class="main-content pt-4">
        <div class="row pl-2">
            <div class="col-md-4 breadcrumb">
                <h1>Job Purchase</h1>
                <ul>
                    <li>Create</li>
                    <li>Save</li>
                </ul>
            </div>
            <div class="col-md-4">
                <input type="text" name="job_purchase_no" id="job_purchase_no"
                    value="{{ isset($purchase_order) ? $purchase_order->purchase_order_no : 'POO-05102024-0001' }}"
                    style="border:none; background:none; font-size:20px; font-weight:bold;"
                    class="form-control text-center bg-light">
            </div>
            <div class="col-md-4">
                <input type="text" name="job_purchase_no" id="job_purchase_no"
                    value="{{ isset($job_purchase_no) ? $job_purchase_no : 'JP-05102024-0001' }}"
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
                                                    <label class="form-label">Job Purchase Date:<span
                                                            style="color:red;">*</span></label>
                                                    <input type="date" name="job_purchase_date" id="job_purchase_date"
                                                        class="form-control" value="{{ old('sale_date') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Reference:<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" name="reference"
                                                        id="reference" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card" style="border-radius:0px;">
                                            <div class="card-header bg-transparent"
                                                style="border-radius:0px; padding: 6px;">
                                                <b>Purchase Order Detail</b>
                                            </div>
                                            <div class="card-body" style="min-height: 60px; overflow: auto; padding: 8px;">
                                                <table class="table" style="width: 100%;">
                                                    <tbody>
                                                        @foreach ($job_task_detail as $item)
                                                            <tr>
                                                                <td style="padding: 3px;" class="mb-1">
                                                                    <a id="JobTaskDetail" href="javascript:void(0)"
                                                                        data-toggle="tooltip"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-product_id="{{ $item['product_id'] ?? '' }}"
                                                                        data-product="{{ $item['product'] ?? '' }}"
                                                                        data-category="{{ $item['category'] ?? '' }}"
                                                                        data-design_no="{{ $item['design_no'] ?? '' }}"
                                                                        data-net_weight="{{ $item['net_weight'] ?? '' }}"
                                                                        data-purchase_order_id="{{ $item['purchase_order_id'] ?? '' }}"
                                                                        data-sale_order_id="{{ $item['sale_order_id'] ?? '' }}"
                                                                        data-warehouse_id="{{ $item['warehouse_id'] ?? '' }}"
                                                                        data-original-title="Job task detail">
                                                                        <b>{{ $item['product'] ?? '' }},C:{{ $item['category'] ?? '' }},D:{{ $item['design_no'] ?? '' }}</b>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="mb-2 mt-2">
                                        <b>Add Product Detail:</b>
                                    </div>
                                    <input type="hidden" name="purchase_order_detail_id" id="purchase_order_detail_id"
                                        value="">
                                        <input type="hidden" name="job_task_id" id="job_task_id" value="{{$job_task_id}}">
                                    <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="">
                                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="">
                                    <input type="hidden" name="sale_order_id" id="sale_order_id" value="">
                                    <input type="hidden" name="product_id" id="product_id" value="">
                                    <input type="hidden" name="supplier_id" id="supplier_id" value="{{$supplier->id}}">
                                    <input type="hidden" name="approvedby_id" id="approvedby_id" value="">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Product:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="product" id="product" class="form-control"
                                                        required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Category:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="product_category" id="product_category"
                                                        class="form-control" required readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Design No:</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="design_no" id="design_no"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">With Polish Weight:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="polish_weight" id="polish_weight"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Waste Ratti:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="waste_ratti" id="waste_ratti"
                                                        class="form-control" required value="{{$supplier->gold_waste??0}}"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Waste:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="waste" id="waste"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Total Weight:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="total_weight" id="total_weight"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mail:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <select name="mail" id="mail" class="form-control">
                                                    <option value="Upper" selected>Upper Mail</option>
                                                    <option value="Inner">Inner Mail</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Mail Ratti:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="mail_weight" id="mail_weight"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Pure Weight:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="pure_weight" id="pure_weight"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Stone Fitting Waste:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="stone_waste" id="stone_waste"
                                                        class="form-control" required value="{{$supplier->stone_waste??0}}"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Stone Adjustment:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="stone_adjustement" id="stone_adjustement"
                                                        class="form-control" value="0" required
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
                                            <label class="form-label">With Stone Weight:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="with_stone_weight" value="0" name="with_stone_weight"
                                                class="form-control" readonly onkeypress="return isNumberKey(event)"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Actual Recieved Weight:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="recieved_weight" name="recieved_weight"
                                                class="form-control" value="0"
                                                onkeypress="return isNumberKey(event)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Stone Waste:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="stone_waste_weight" name="stone_waste_weight"
                                                min="10" class="form-control" value="0"
                                                onkeypress="return isNumberKey(event)" readonly required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Total Recieved Weight:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="total_recieved_weight" name="total_recieved_weight"
                                                class="form-control" readonly value="0"
                                                onkeypress="return isNumberKey(event)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Payable/Recieveable Weight:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="payable_weight" name="payable_weight"
                                                class="form-control" readonly value="0"
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
                                            <label class="form-label">Laker:<span class="text-danger">*</span></label>
                                            <input type="text" id="laker" name="laker" value="0" class="form-control"
                                                onkeypress="return isNumberKey(event)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">RP:<span class="text-danger">*</span></label>
                                            <input type="text" id="rp" name="rp" value="0" class="form-control"
                                                onkeypress="return isNumberKey(event)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Wax:<span class="text-danger">*</span></label>
                                            <input type="text" id="wax" name="wax" value="0" class="form-control"
                                                onkeypress="return isNumberKey(event)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Other:<span class="text-danger">*</span></label>
                                            <input type="text" id="other" name="other" class="form-control"
                                                value="0" onkeypress="return isNumberKey(event)" required
                                                min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Final Pure Weight:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="final_pure_weight" id="final_pure_weight"
                                                        class="form-control" required value="0"
                                                        onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Total Amount ($):<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="total_diamond_dollar" name="total_diamond_dollar"
                                                class="form-control" value="0" readonly
                                                onkeypress="return isNumberKey(event)" required />
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
                                                    <th>Product</th>
                                                    <th>Category</th>
                                                    <th>Design</th>
                                                    <th>After Polish</th>
                                                    <th>Waste Ratti</th>
                                                    <th>Waste</th>
                                                    <th>Total Wt</th>
                                                    <th>Mail</th>
                                                    <th>Mail Wt</th>
                                                    <th>Pure Wt</th>
                                                    <th>Fitting Waste</th>
                                                    <th>Stone Adjust.</th>
                                                    <th>Bead Wt</th>
                                                    <th>Stone Wt</th>
                                                    <th>Diamond Wt</th>
                                                    <th>With Stone Wt</th>
                                                    <th>Actual Recieved Wt</th>
                                                    <th>Stone Waste</th>
                                                    <th>Total Recieved Wt</th>
                                                    <th>Payable/Recieved Wt</th>
                                                    <th>Bead Amount</th>
                                                    <th>Stone Amount</th>
                                                    <th>Diamond Amount</th>
                                                    <th>Laker</th>
                                                    <th>RP</th>
                                                    <th>Wax</th>
                                                    <th>Other</th>
                                                    <th>Final Pure Wt</th>
                                                    <th>Total($)</th>
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bold">Total Recieved (AU):<span
                                                            class="text-danger">*</span> </label>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="total_recieved_au" value="0"
                                                                id="total_recieved_au"
                                                                class="form-control font-weight-bold" readonly required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bold">Total (AU):<span
                                                            class="text-danger">*</span> </label>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="total_au" value="0"
                                                                id="total_au" class="form-control font-weight-bold"
                                                                readonly required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bold">Total ($):</label>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="total_dollar" value="0"
                                                                id="total_dollar" class="form-control font-weight-bold">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bold">Total (PKR):</label>
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="total" value="0"
                                                                id="total" class="form-control font-weight-bold">
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
    @include('purchases/job_purchase/Modal/BeadModal')
    @include('purchases/job_purchase/Modal/DiamondModal')
    @include('purchases/job_purchase/Modal/StonesModal')
@endsection
@section('js')
    <script src="{{ url('job-purchase/js/job_purchase.js') }}"></script>
    {{-- <script src="{{ url('job-purchase/js/job_purchase_detail.js') }}"></script> --}}
    <script src="{{ url('job-purchase/js/bead_weight.js') }}"></script>
    <script src="{{ url('job-purchase/js/stone_weight.js') }}"></script>
    <script src="{{ url('job-purchase/js/diamond_carat.js') }}"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
        var dollar_rate = "{{$dollar_rate->rate}}";
    </script>

    <script>
        $(document).ready(function() {
            const sale_date = document.getElementById("job_purchase_date");

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
