<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    use JsonResponse;

    public function index()
    {
        abort_if(Gate::denies('products_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('product_category.index');
    }

    public function getData()
    {
        abort_if(Gate::denies('products_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = Product::withCount(['finishProducts as stock_count' => function ($query) {
            $query->where('is_deleted', 0)->where('is_active', 1);
        }])->where('is_deleted', 0);

        return DataTables::of($model)
            ->addColumn('stock', function ($item) {
                $manualStock = $item->getRawOriginal('stock');

                return $manualStock !== null && $manualStock !== ''
                    ? (int) $manualStock
                    : (int) ($item->stock_count ?? 0);
            })
            ->addColumn('image', function ($item) {
                $path = trim((string) ($item->image_path ?? ''));

                if ($path === '') {
                    return '';
                }

                if (Storage::disk('public')->exists($path)) {
                    return '<img src="' . Storage::url($path) . '" alt="' . e($item->name) . '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">';
                }

                $normalized = ltrim(str_replace('\\', '/', $path), '/');

                if (is_file(public_path($normalized))) {
                    return '<img src="' . asset($normalized) . '" alt="' . e($item->name) . '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">';
                }

                return '';
            })
            ->addColumn('status', function ($item) {
                if (! Auth::user()->can('products_status')) {
                    return 'N/A';
                }

                if ($item->is_active == 1) {
                    return '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                }

                return '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
            })
            ->addColumn('action', function ($item) {
                $actionColumn = '';
                $editColumn = "<a class='text-success mr-2' id='editProductCategory' href='javascript:void(0)' data-toggle='tooltip' data-id='" . $item->id . "' data-original-title='Edit'><i title='Edit' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $deleteColumn = "<a class='text-danger mr-2' id='deleteProductCategory' href='javascript:void(0)' data-toggle='tooltip' data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('products_edit')) {
                    $actionColumn .= $editColumn;
                }

                if (Auth::user()->can('products_delete')) {
                    $actionColumn .= $deleteColumn;
                }

                return $actionColumn;
            })
            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('products_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');

        $validation = Validator::make(
            $request->all(),
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('products', 'name')
                        ->ignore($id)
                        ->where(function ($query) {
                            return $query->where('is_deleted', 0);
                        }),
                ],
                'prefix' => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('products', 'prefix')
                        ->ignore($id)
                        ->where(function ($query) {
                            return $query->where('is_deleted', 0);
                        }),
                ],
                'description' => ['nullable', 'string'],
                'image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            return $this->validationResponse(implode(' ', $validation->errors()->all()));
        }

        try {
            $product = $id ? Product::find($id) : new Product();

            if (! $product) {
                return $this->error(config('enum.error'));
            }

            $imagePath = $product->image_path ?? null;

            if ($request->hasFile('image_file')) {
                if (! empty($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $imagePath = $request->file('image_file')->store('product-categories', 'public');
            } elseif ($request->boolean('remove_image')) {
                // Delete just the thumbnail while keeping the category.
                if (! empty($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }

                $imagePath = null;
            }

            $prefix = strtoupper(trim((string) $request->input('prefix', '')));
            if ($prefix === '') {
                $prefix = Str::upper(Str::substr(trim((string) $request->input('name')), 0, 3));
            }

            $payload = [
                'name' => trim((string) $request->input('name')),
                'prefix' => $prefix,
                'image_path' => $imagePath,
                'description' => $request->input('description'),
                'tags' => $request->input('tags'),
                'attributes' => $request->input('attributes'),
                'stock' => $request->input('stock', null),
                'is_active' => $request->boolean('is_active', true) ? 1 : 0,
                'is_deleted' => 0,
            ];

            if ($id) {
                $payload['updatedby_id'] = Auth::id();
                $product->update($payload);
                $message = config('enum.updated');
            } else {
                $payload['createdby_id'] = Auth::id();
                $payload['updatedby_id'] = Auth::id();
                $product = Product::create($payload);
                $message = config('enum.saved');
            }

            return $this->success($message, $product);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage() ?: config('enum.error'));
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('products_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product = Product::where('is_deleted', 0)->find($id);

        if (! $product) {
            return $this->error(config('enum.error'));
        }

        $product->image_url = $this->imageUrl($product->image_path);

        return $this->success(config('enum.success'), $product);
    }

    public function update(Request $request)
    {
        return $this->store($request);
    }

    public function status($id)
    {
        abort_if(Gate::denies('products_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $product = Product::where('is_deleted', 0)->find($id);

            if (! $product) {
                return $this->error(config('enum.error'));
            }

            $product->is_active = $product->is_active == 0 ? 1 : 0;
            $product->updatedby_id = Auth::id();
            $product->save();

            return $this->success(config('enum.status'), $product, true);
        } catch (\Throwable $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('products_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $product = Product::where('is_deleted', 0)->find($id);

            if (! $product) {
                return $this->error(config('enum.error'));
            }

            if (! empty($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->is_deleted = 1;
            $product->deletedby_id = Auth::id();
            $product->save();

            return $this->success(config('enum.delete'), $product, true);
        } catch (\Throwable $e) {
            return $this->error(config('enum.error'));
        }
    }

    private function imageUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (is_file(public_path($normalized))) {
            return asset($normalized);
        }

        return null;
    }
}
