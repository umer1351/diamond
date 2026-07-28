@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="row pl-2">
            <div class="col-md-8 breadcrumb">
                <h1>Purchase Order</h1>
                @if (isset($purchase_order))
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
                <input type="text" name="Purchase_no" id="Purchase_no"
                    value="{{ isset($purchase_order) ? $purchase_order->purchase_order_no : 'OSL-05102024-0009' }}"
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

                        <form id="purchaseForm" action="#" method="POST" enctype="multipart/form-data">
                            <div class="card-body" style="font-size: 14px;">
                                {{-- Edit Form  --}}
                                @csrf
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Purchase Order Date:<span
                                                            style="color:red;">*</span></label>
                                                    <input type="date" name="purchase_order_date"
                                                        id="purchase_order_date" class="form-control"
                                                        value="{{ isset($purchase_order) ? $purchase_order->purchase_order_date : old('purchase_order_date') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Reference:<span
                                                            style="color:red;">*</span></label>
                                                    <input type="text" name="reference_no"
                                                        id="reference_no" class="form-control"
                                                        value="{{ isset($purchase_order) ? $purchase_order->reference_no : old('purchase_order_date') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Delivery Date:<span
                                                            style="color:red;">*</span></label>
                                                    <input type="datetime-local" name="delivery_date" id="delivery_date"
                                                        class="form-control"
                                                        value="{{ isset($purchase_order) ? $purchase_order->delivery_date : old('purchase_order_date') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Supplier: <span
                                                            class="text-danger">*</span></label>
                                                    <select id="supplier_id" name="supplier_id"
                                                        class="form-control show-tick">
                                                        <option value="" selected disabled>--Select Supplier--
                                                        </option>
                                                        @foreach ($suppliers as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name ?? '' }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Warehouse:<span
                                                            class="text-danger">*</span></label>
                                                    <select id="warehouse_id" name="warehouse_id"
                                                        class="form-control show-tick" required>
                                                        <option value="" selected disabled>--Select Warehouse--
                                                        </option>
                                                        @foreach ($warehouses as $item)
                                                            <option value="{{ $item->id }}"
                                                                @if (isset($purchase_order)) {{ $purchase_order->warehouse_id == $item->id ? 'selected' : '' }} @endif>
                                                                {{ $item->name ?? '' }}
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
                                                <b>Sale Order Detail</b>
                                            </div>
                                            <div class="card-body" style="min-height: 60px; overflow: auto; padding: 8px;"
                                                id="sale_order">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" class="form-control"
                                        value="{{ isset($purchase_order) ? $purchase_order->id : '' }}" id="id"
                                        name="id">
                                    <input type="hidden" class="form-control"
                                        value="{{ isset($purchase_order) ? $purchase_order->sale_order_id : '' }}"
                                        id="sale_order_id" name="sale_order_id">
                                    <div class="col-md-12">
                                        <hr class="mb-2 mt-2">
                                        <b>Add Purchase Detail:</b>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Category:<span class="text-danger">*</span></label>
                                            <input type="text" name="category" id="category"
                                                value="{{ old('category') }}" class="form-control"
                                                placeholder="Enter category" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Disign No:</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="design_no" id="design_no"
                                                        class="form-control" placeholder="Enter design no">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Net Weight:<span style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="net_weight" id="net_weight"
                                                        class="form-control" required
                                                        onkeypress="return isNumberKey(event)" maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description:<span
                                                    style="color:red;">*</span></label>
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="description" id="description"
                                                        class="form-control" placeholder="Enter description" required>
                                                </div>
                                            </div>
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
                                                    <th>Category</th>
                                                    <th>Design No</th>
                                                    <th>Net Weight</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_order_products">

                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
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
@endsection
@section('js')
    <script src="{{ url('purchase-order/js/purchase_order.js') }}"></script>
    <script type="text/javascript">
        var url_local = "{{ url('/') }}";
    </script>

    <script>
        $(document).ready(function() {
            $('#supplier_id').select2();
            $('#warehouse_id').select2();
            const purchase_order_date = document.getElementById("purchase_order_date");

            // ✅ Using the visitor's timezone
            purchase_order_date.value = formatDate();

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
            purchase_order_date.value = new Date().toISOString().split("T")[0];
        });
    </script>
@endsection
