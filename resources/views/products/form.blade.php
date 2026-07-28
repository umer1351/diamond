@extends('layouts.master')

@section('content')
    @php
        $isEdit = isset($finish_product) && $finish_product;
    @endphp
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>Finished Products</h1>
            <ul>
                <li>{{ $isEdit ? 'Edit' : 'Create' }}</li>
                <li>Save</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-transparent text-right">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ url('products') }}">
                                <i class="fa fa-arrow-left mr-1"></i> Back
                            </a>
                        </div>
                        <form action="{{ url('products/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{ old('id', $finish_product->id ?? '') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tag No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tag_no" value="{{ old('tag_no', $finish_product->tag_no ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" class="form-control" name="product_name" value="{{ old('product_name', $finish_product->product_name ?? '') }}" placeholder="Finished product name">
                                            <small class="text-muted">This is the name shown on product listings and storefront.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" name="product_id" id="product_id">
                                                <option value="">-- Select Category --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" @selected((string) old('product_id', $finish_product->product_id ?? '') === (string) $product->id)>
                                                        {{ $product->name }} {{ $product->prefix ? '(' . $product->prefix . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Choose the category first, or create a new one below.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>New Category Name</label>
                                            <input type="text" class="form-control" name="new_product_name" id="new_product_name" value="{{ old('new_product_name') }}" placeholder="Create from scratch">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>New Category Prefix</label>
                                            <input type="text" class="form-control" name="new_product_prefix" id="new_product_prefix" value="{{ old('new_product_prefix') }}" maxlength="3" placeholder="Optional">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Warehouse <span class="text-danger">*</span></label>
                                            <select class="form-control" name="warehouse_id" required>
                                                <option value="">-- Select Warehouse --</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}" @selected((string) old('warehouse_id', $finish_product->warehouse_id ?? '') === (string) $warehouse->id)>
                                                        {{ $warehouse->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Picture {{ $isEdit ? '' : '*' }}</label>
                                            <input type="file" class="form-control" name="picture" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                                            @if($isEdit && filled($finish_product->picture))
                                                <small class="text-muted d-block mt-1">Current: {{ $finish_product->picture }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Short Description</label>
                                            <textarea class="form-control" name="short_description" rows="2" placeholder="Short storefront summary">{{ old('short_description', $finish_product->short_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Long Description</label>
                                            <textarea class="form-control" name="long_description" rows="2" placeholder="Detailed product description">{{ old('long_description', $finish_product->long_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tags</label>
                                            <input type="text" class="form-control" name="tags" value="{{ old('tags', $finish_product->tags ?? '') }}" placeholder="hot sale, necklace">
                                        </div>
                                    </div>

                                    <div class="col-md-3"><div class="form-group"><label>Gold Carat</label><input type="number" step="0.001" min="0" class="form-control" name="gold_carat" value="{{ old('gold_carat', $finish_product->gold_carat ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Scale Weight</label><input type="number" step="0.001" min="0" class="form-control" name="scale_weight" value="{{ old('scale_weight', $finish_product->scale_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Bead Weight</label><input type="number" step="0.001" min="0" class="form-control" name="bead_weight" value="{{ old('bead_weight', $finish_product->bead_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Stone Weight</label><input type="number" step="0.001" min="0" class="form-control" name="stones_weight" value="{{ old('stones_weight', $finish_product->stones_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Diamond Weight</label><input type="number" step="0.001" min="0" class="form-control" name="diamond_weight" value="{{ old('diamond_weight', $finish_product->diamond_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Net Weight</label><input type="number" step="0.001" min="0" class="form-control" name="net_weight" value="{{ old('net_weight', $finish_product->net_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Waste %</label><input type="number" step="0.001" min="0" class="form-control" name="waste_per" value="{{ old('waste_per', $finish_product->waste_per ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Waste</label><input type="number" step="0.001" min="0" class="form-control" name="waste" value="{{ old('waste', $finish_product->waste ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Gross Weight</label><input type="number" step="0.001" min="0" class="form-control" name="gross_weight" value="{{ old('gross_weight', $finish_product->gross_weight ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Making/Gram</label><input type="number" step="0.001" min="0" class="form-control" name="making_gram" value="{{ old('making_gram', $finish_product->making_gram ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Making</label><input type="number" step="0.001" min="0" class="form-control" name="making" value="{{ old('making', $finish_product->making ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Laker</label><input type="number" step="0.001" min="0" class="form-control" name="laker" value="{{ old('laker', $finish_product->laker ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Total Bead Amount</label><input type="number" step="0.001" min="0" class="form-control" name="total_bead_price" value="{{ old('total_bead_price', $finish_product->total_bead_price ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Total Stones Amount</label><input type="number" step="0.001" min="0" class="form-control" name="total_stones_price" value="{{ old('total_stones_price', $finish_product->total_stones_price ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Total Diamond Amount</label><input type="number" step="0.001" min="0" class="form-control" name="total_diamond_price" value="{{ old('total_diamond_price', $finish_product->total_diamond_price ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Other Amount</label><input type="number" step="0.001" min="0" class="form-control" name="other_amount" value="{{ old('other_amount', $finish_product->other_amount ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Gold Rate</label><input type="number" step="0.001" min="0" class="form-control" name="gold_rate" value="{{ old('gold_rate', $finish_product->gold_rate ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Total Gold Amount</label><input type="number" step="0.001" min="0" class="form-control" name="total_gold_price" value="{{ old('total_gold_price', $finish_product->total_gold_price ?? 0) }}"></div></div>
                                    <div class="col-md-3"><div class="form-group"><label>Total Amount</label><input type="number" step="0.001" min="0" class="form-control" name="total_amount" value="{{ old('total_amount', $finish_product->total_amount ?? 0) }}"></div></div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="is_active">
                                                <option value="1" @selected((string) old('is_active', $finish_product->is_active ?? 1) === '1')>Active</option>
                                                <option value="0" @selected((string) old('is_active', $finish_product->is_active ?? 1) === '0')>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update' : 'Save' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    <script>
        $('#product_id').on('change', function () {
            if ($(this).val()) {
                $('#new_product_name').val('');
                $('#new_product_prefix').val('');
            }
        });

        $('#new_product_name').on('input', function () {
            if ($(this).val().trim() !== '') {
                $('#product_id').val('');
            }
        });

        $('#new_product_prefix').on('input', function () {
            if ($(this).val().trim() !== '') {
                $('#product_id').val('');
            }
        });
    </script>
@endsection
