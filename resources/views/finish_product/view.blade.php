@extends('layouts.master')
@section('content')
    <div class="main-content pt-4">
        <div class="breadcrumb">
            <h1>View Tagging</h1>
            <ul>
                <li>View</li>
                <li>Show</li>
            </ul>
        </div>
        <div class="separator-breadcrumb border-top"></div>
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        <!-- end of row -->
        <section class="contact-list">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-transparent text-right">

                        </div>
                        <div class="card-body" style="font-size: 14px;">
                            <div class="row">
                                <div class="col-md-9">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">Product:</td>
                                                <td>{{ $finish_product->product->name ?? '' }}
                                                    {{ $finish_product->product->prefix ?? '' }}</td>
                                                <td class="font-weight-bold">Purchase:</td>
                                                <td>{{ $finish_product->ratti_kaat->ratti_kaat_no ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Tag No:</td>
                                                <td>{{ $finish_product->tag_no ?? '' }}</td>
                                                <td class="font-weight-bold">Warehouse:</td>
                                                <td>{{ $finish_product->warehouse->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Karat:</td>
                                                <td>{{ $finish_product->gold_carat ?? '' }}</td>
                                                <td class="font-weight-bold">Scale Weight:</td>
                                                <td>{{ $finish_product->scale_weight ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Bead Weight:</td>
                                                <td>{{ $finish_product->bead_weight ?? '' }}</td>
                                                <td class="font-weight-bold">Stones Weight:</td>
                                                <td>{{ $finish_product->stones_weight ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Diamond Weight:</td>
                                                <td>{{ $finish_product->diamond_weight ?? '' }}</td>
                                                <td class="font-weight-bold">Net Weight:</td>
                                                <td>{{ $finish_product->net_weight ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Waste (%):</td>
                                                <td>{{ $finish_product->waste_per ?? '' }}</td>
                                                <td class="font-weight-bold">Waste:</td>
                                                <td>{{ $finish_product->waste ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Gross Weight:</td>
                                                <td>{{ $finish_product->gross_weight ?? '' }}</td>
                                                <td class="font-weight-bold">Laker:</td>
                                                <td>{{ $finish_product->laker ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Making/gram:</td>
                                                <td>{{ $finish_product->making_gram ?? '' }}</td>
                                                <td class="font-weight-bold">Making:</td>
                                                <td>{{ $finish_product->making ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Total Bead Price:</td>
                                                <td>{{ $finish_product->total_bead_price ?? '' }}</td>
                                                <td class="font-weight-bold">Total Stones Price:</td>
                                                <td>{{ $finish_product->total_stones_price ?? '' }}</td>

                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Total Diamond Price:</td>
                                                <td>{{ $finish_product->total_diamond_price ?? '' }}</td>
                                                <td class="font-weight-bold">Gold Rate/gram:</td>
                                                <td>{{ $finish_product->gold_rate ?? '' }}</td>

                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Total Gold Price:</td>
                                                <td>{{ $finish_product->total_gold_price ?? '' }}</td>
                                                <td class="font-weight-bold">Other Amount:</td>
                                                <td>{{ $finish_product->other_amount ?? '' }}</td>

                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Total Amount:</td>
                                                <td>{{ $finish_product->total_amount ?? '' }}</td>
                                                <td class="font-weight-bold">Saled:</td>
                                                <td>@if($finish_product->is_saled==1)<span class=" badge badge-success mr-3">Yes</span> @else <span class=" badge badge-danger mr-3">No</span>@endif</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><img src="{{ asset($finish_product->barcode) }}"
                                                        alt=""><br><span
                                                        style="font-family: fantasy;">{{ $finish_product->tag_no ?? '' }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h5 class="font-weight-bold mb-0">Product Images:</h5>
                                    </div>
                                    <div class="d-flex flex-wrap" style="gap:14px">
                                        @forelse ($finish_product->images as $image)
                                            <div class="border rounded p-2 text-center" style="width:150px;position:relative">
                                                <img src="{{ asset($image->path) }}" alt=""
                                                    style="width:100%;height:120px;object-fit:cover;border-radius:4px">
                                                <a href="{{ url('finish-product/images/' . $image->id . '/delete') }}"
                                                    class="btn btn-sm btn-danger btn-block mt-2"
                                                    onclick="return confirm('Delete this image? This cannot be undone.');">
                                                    <i class="i-Close"></i> Delete
                                                </a>
                                            </div>
                                        @empty
                                            <p class="text-muted">No images attached yet.</p>
                                        @endforelse
                                    </div>

                                    <form action="{{ url('finish-product/images/' . $finish_product->id) }}"
                                        method="POST" enctype="multipart/form-data" class="mt-3">
                                        @csrf
                                        <div class="form-row align-items-center">
                                            <div class="col-auto">
                                                <input type="file" name="images[]" class="form-control-file"
                                                    accept="image/*" multiple required>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary">Add Image(s)</button>
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted">You can select multiple images. JPEG, PNG, JPG, GIF or WEBP, up to 8 MB each.</small>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if ($finish_product_bead->count() > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="font-weight-bold">Bead Detail:</h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Type</th>
                                                    <th>Beads</th>
                                                    <th>Gram</th>
                                                    <th>Carat</th>
                                                    <th>Rate/Gram</th>
                                                    <th>Rate/Carat</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($finish_product_bead as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->type ?? '' }}</td>
                                                        <td>{{ $item->beads ?? 0 }}</td>
                                                        <td>{{ $item->gram ?? 0 }}</td>
                                                        <td>{{ $item->carat ?? 0 }}</td>
                                                        <td>{{ $item->gram_rate ?? 0 }}</td>
                                                        <td>{{ $item->carat_rate ?? 0 }}</td>
                                                        <td>{{ $item->total_amount ?? 0 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if ($finish_product_stone->count() > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="font-weight-bold">Stone Detail:</h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Type</th>
                                                    <th>Stones</th>
                                                    <th>Gram</th>
                                                    <th>Carat</th>
                                                    <th>Rate/Gram</th>
                                                    <th>Rate/Carat</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($finish_product_stone as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->type ?? '' }}</td>
                                                        <td>{{ $item->stones ?? 0 }}</td>
                                                        <td>{{ $item->gram ?? 0 }}</td>
                                                        <td>{{ $item->carat ?? 0 }}</td>
                                                        <td>{{ $item->gram_rate ?? 0 }}</td>
                                                        <td>{{ $item->carat_rate ?? 0 }}</td>
                                                        <td>{{ $item->total_amount ?? 0 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            @if ($finish_product_diamond->count() > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="font-weight-bold">Diamond Detail:</h5>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Type</th>
                                                    <th>Diamonds</th>
                                                    <th>Color</th>
                                                    <th>Cut</th>
                                                    <th>Clarity</th>
                                                    <th>Carat</th>
                                                    <th>Rate/Carat</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($finish_product_diamond as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->type ?? '' }}</td>
                                                        <td>{{ $item->diamonds ?? 0 }}</td>
                                                        <td>{{ $item->color ?? '' }}</td>
                                                        <td>{{ $item->cut ?? '' }}</td>
                                                        <td>{{ $item->clarity ?? '' }}</td>
                                                        <td>{{ $item->carat ?? '' }}</td>
                                                        <td>{{ $item->carat_rate ?? 0 }}</td>
                                                        <td>{{ $item->total_amount ?? 0 }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- end of col -->
                </div>
                <!-- end of row -->
            </div>
        </section>
    </div>
@endsection
@section('js')
@endsection
