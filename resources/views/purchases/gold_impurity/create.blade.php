@extends('layouts.master')
@section('content')
<div class="main-content pt-4">
    <div class="row pl-2">
        <div class="col-md-8 breadcrumb">
            <h1>Gold Impurity</h1>
            @if (isset($gold_impurity))
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
            <input type="text" name="gold_impurity_purchase_no" id="gold_impurity_purchase_no"
                value="{{ isset($gold_impurity) ? $gold_impurity->gold_impurity_purchase_no : 'GIP-05102024-0009' }}"
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

                    <form id="gold_impurityForm" action="#" method="POST" enctype="multipart/form-data">
                        <div class="card-body" style="font-size: 14px;">
                            {{-- Edit Form  --}}
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
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
                                <div class="col-md-12">
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
                                <input type="hidden" class="form-control"
                                    value="{{ isset($gold_impurity) ? $gold_impurity->id : '' }}" id="id"
                                    name="id">
                                <div class="col-md-12">
                                    <hr class="mb-2 mt-2">
                                    <b>Add Purchase Detail:</b>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Scale Weight:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="scale_weight" id="scale_weight"
                                                    class="form-control" required onkeypress="return isNumberKey(event)"
                                                    maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Bead Weight:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="bead_weight" id="bead_weight" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Stone Weight:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="stone_weight" id="stone_weight" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Net Weight:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="net_weight" id="net_weight" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" readonly maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Points:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="point" id="point" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Pure Weight:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="pure_weight" id="pure_weight" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" readonly maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Gold Rate/Gram:<span style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="gold_rate" id="gold_rate" class="form-control"
                                                    required onkeypress="return isNumberKey(event)" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Total Amount:<span
                                                style="color:red;">*</span></label>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="total_amount" id="total_amount"
                                                    class="form-control" required readonly
                                                    onkeypress="return isNumberKey(event)" maxlength="10">
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
                                                <th>Scale Wt</th>
                                                <th>Bead Wt</th>
                                                <th>Stone Wt</th>
                                                <th>Net Wt</th>
                                                <th>Points</th>
                                                <th>Pure Wt</th>
                                                <th>Gold Rate</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gold_impurity_products">

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
                                                <label class="form-label font-weight-bold">Grand Weight:<span
                                                        class="text-danger">*</span> </label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="total_weight"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->total_weight : '0' }}"
                                                            id="total_weight" class="form-control font-weight-bold"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Grand Qty:<span
                                                        class="text-danger">*</span> </label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="total_qty"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->total_qty : '0' }}"
                                                            id="total_qty" class="form-control font-weight-bold"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Grand Total(PKR):<span
                                                        class="text-danger">*</span> </label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="total"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->total : '0' }}"
                                                            id="total" class="form-control font-weight-bold"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Cash Payment:</label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="cash_payment"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->cash_payment : '0' }}"
                                                            id="cash_payment" class="form-control font-weight-bold">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Bank Transafer:</label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="bank_payment"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->bank_payment : '0' }}"
                                                            id="bank_payment"
                                                            class="form-control font-weight-bold">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label font-weight-bold">Balance:</label>
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="balance"
                                                            value="{{ isset($gold_impurity) ? $gold_impurity->balance : '0' }}"
                                                            id="balance" class="form-control font-weight-bold">
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
@include('purchases/gold_impurity/Modal/CustomerModal')
@endsection
@section('js')
<script src="{{ url('gold-impurity/js/gold_impurity.js') }}"></script>
<script type="text/javascript">
    var url_local = "{{ url('/') }}";
    var gold_impurity_id = "{{ isset($gold_impurity) ? $gold_impurity->id : '' }}";
</script>

<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        getCustomer();
    });
</script>
@endsection