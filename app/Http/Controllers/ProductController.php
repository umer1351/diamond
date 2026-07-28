<?php

namespace App\Http\Controllers;

use App\Models\FinishProduct;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\Concrete\FinishProductService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use JsonResponse;

    protected $finish_product_service;

    public function __construct(FinishProductService $finish_product_service)
    {
        $this->finish_product_service = $finish_product_service;
    }

    public function index()
    {
        abort_if(Gate::denies('products_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('products.index');
    }

    public function getData()
    {
        abort_if(Gate::denies('products_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = FinishProduct::with(['product', 'warehouse'])
            ->where('is_deleted', 0);

        return DataTables::of($model)
            ->addColumn('product', function ($item) {
                return $item->product_name ?: trim((string) ($item->product->name ?? '') . ' ' . (string) ($item->product->prefix ?? ''));
            })
            ->addColumn('category', function ($item) {
                return trim((string) ($item->product->name ?? '') . ' ' . (string) ($item->product->prefix ?? ''));
            })
            ->addColumn('warehouse', function ($item) {
                return $item->warehouse->name ?? '';
            })
            ->addColumn('stock', function ($item) {
                return $item->is_active ? 'Active' : 'Inactive';
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
                $action_column = '';
                $edit_column = "<a class='text-success mr-2' href='" . url('products/edit/' . $item->id) . "'><i title='Edit' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $view_column = "<a class='text-warning mr-2' href='" . url('finish-product/view/' . $item->id) . "'><i title='View' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $delete_column = "<a class='text-danger mr-2' id='deleteProduct' href='javascript:void(0)' data-toggle='tooltip' data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('products_edit')) {
                    $action_column .= $edit_column;
                }

                if (Auth::user()->can('products_delete')) {
                    $action_column .= $delete_column;
                }

                if (Auth::user()->can('products_access')) {
                    $action_column .= $view_column;
                }

                return $action_column;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        abort_if(Gate::denies('products_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('products.form', [
            'finish_product' => null,
            'products' => Product::where('is_deleted', 0)->where('is_active', 1)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_deleted', 0)->orderBy('name')->get(),
            'pageTitle' => 'Create Finished Product',
        ]);
    }

    public function edit($id)
    {
        abort_if(Gate::denies('products_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finish_product = FinishProduct::with(['product', 'warehouse'])->findOrFail($id);

        return view('products.form', [
            'finish_product' => $finish_product,
            'products' => Product::where('is_deleted', 0)->where('is_active', 1)->orderBy('name')->get(),
            'warehouses' => Warehouse::where('is_deleted', 0)->orderBy('name')->get(),
            'pageTitle' => 'Edit Finished Product',
        ]);
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('products_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $finishProduct = $request->filled('id')
            ? FinishProduct::find($request->input('id'))
            : null;

        $validation = Validator::make(
            $request->all(),
            [
                'tag_no' => ['required', 'string', 'max:255'],
                'product_id' => ['nullable', 'integer', 'exists:products,id'],
                'product_name' => ['nullable', 'string', 'max:255'],
                'new_product_name' => ['nullable', 'string', 'max:255'],
                'new_product_prefix' => ['nullable', 'string', 'max:3'],
                'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
                'picture' => $finishProduct ? ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'] : ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
                'short_description' => ['nullable', 'string'],
                'long_description' => ['nullable', 'string'],
                'tags' => ['nullable', 'string', 'max:1000'],
                'gold_carat' => ['nullable', 'numeric'],
                'scale_weight' => ['nullable', 'numeric'],
                'bead_weight' => ['nullable', 'numeric'],
                'stones_weight' => ['nullable', 'numeric'],
                'diamond_weight' => ['nullable', 'numeric'],
                'net_weight' => ['nullable', 'numeric'],
                'waste_per' => ['nullable', 'numeric'],
                'waste' => ['nullable', 'numeric'],
                'gross_weight' => ['nullable', 'numeric'],
                'making_gram' => ['nullable', 'numeric'],
                'making' => ['nullable', 'numeric'],
                'laker' => ['nullable', 'numeric'],
                'total_bead_price' => ['nullable', 'numeric'],
                'total_stones_price' => ['nullable', 'numeric'],
                'total_diamond_price' => ['nullable', 'numeric'],
                'other_amount' => ['nullable', 'numeric'],
                'gold_rate' => ['nullable', 'numeric'],
                'total_gold_price' => ['nullable', 'numeric'],
                'total_amount' => ['nullable', 'numeric'],
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {
            $productId = $this->resolveProductId($request);

            $picturePath = $finishProduct?->picture;

            if ($request->hasFile('picture')) {
                File::ensureDirectoryExists(public_path('pictures'));
                $file = $request->file('picture');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('pictures'), $filename);
                $picturePath = 'pictures/' . $filename;
            }

            $barcodePath = 'barcodes/' . $request->input('tag_no') . '.png';
            File::ensureDirectoryExists(public_path('barcodes'));
            $generator = new BarcodeGeneratorPNG();
            file_put_contents(public_path($barcodePath), $generator->getBarcode($request->input('tag_no'), $generator::TYPE_CODE_39));

            $payload = [
                'id' => $request->input('id'),
                'tag_no' => $request->input('tag_no'),
                'product_id' => $productId,
                'product_name' => $this->finishProductName($request, $productId),
                'warehouse_id' => $request->input('warehouse_id'),
                'picture' => $picturePath,
                'barcode' => $barcodePath,
                'short_description' => $request->input('short_description'),
                'long_description' => $request->input('long_description'),
                'tags' => $request->input('tags'),
                'is_parent' => $request->boolean('is_parent') ? 1 : 0,
                'parent_id' => $request->input('parent_id', 0),
                'job_purchase_id' => null,
                'job_purchase_detail_id' => null,
                'ratti_kaat_id' => null,
                'ratti_kaat_detail_id' => null,
                'gold_carat' => $this->floatInput($request->input('gold_carat')),
                'scale_weight' => $this->floatInput($request->input('scale_weight')),
                'bead_weight' => $this->floatInput($request->input('bead_weight')),
                'stones_weight' => $this->floatInput($request->input('stones_weight')),
                'diamond_weight' => $this->floatInput($request->input('diamond_weight')),
                'net_weight' => $this->floatInput($request->input('net_weight')),
                'waste_per' => $this->floatInput($request->input('waste_per')),
                'waste' => $this->floatInput($request->input('waste')),
                'gross_weight' => $this->floatInput($request->input('gross_weight')),
                'making_gram' => $this->floatInput($request->input('making_gram')),
                'making' => $this->floatInput($request->input('making')),
                'laker' => $this->floatInput($request->input('laker')),
                'total_bead_price' => $this->floatInput($request->input('total_bead_price')),
                'total_stones_price' => $this->floatInput($request->input('total_stones_price')),
                'total_diamond_price' => $this->floatInput($request->input('total_diamond_price')),
                'other_amount' => $this->floatInput($request->input('other_amount')),
                'gold_rate' => $this->floatInput($request->input('gold_rate')),
                'total_gold_price' => $this->floatInput($request->input('total_gold_price')),
                'total_amount' => $this->floatInput($request->input('total_amount')),
                'is_active' => $request->boolean('is_active', true) ? 1 : 0,
                'is_deleted' => 0,
            ];

            $saved = $this->finish_product_service->save($payload);

            if (! $saved) {
                return redirect()->back()->with('error', config('enum.error'))->withInput();
            }

            return redirect('products')->with('success', $request->filled('id') ? config('enum.updated') : config('enum.saved'));
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage() ?: config('enum.error'))->withInput();
        }
    }

    public function update(Request $request)
    {
        return $this->store($request);
    }

    public function status($id)
    {
        abort_if(Gate::denies('products_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $finish_product = $this->finish_product_service->statusById($id);

            return $this->success(config('enum.status'), $finish_product, true);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('products_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $finish_product = $this->finish_product_service->deleteById($id);

            if (! $finish_product) {
                return $this->error(config('enum.error'));
            }

            return $this->success(config('enum.delete'), $finish_product, true);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function importCsv(Request $request)
    {
        abort_if(Gate::denies('products_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'rb');

        if (! $handle) {
            return redirect()->back()->withErrors(['csv_file' => 'Unable to read the CSV file.']);
        }

        $header = fgetcsv($handle);

        if (! is_array($header)) {
            fclose($handle);
            return redirect()->back()->withErrors(['csv_file' => 'CSV file is empty.']);
        }

        $columns = array_map(function ($column) {
            return Str::of((string) $column)->lower()->trim()->replace([' ', '-'], '_')->toString();
        }, $header);

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $lineNumber = 1;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;

                if ($this->isBlankCsvRow($row)) {
                    continue;
                }

                $data = $this->csvRowToAssoc($columns, $row);

                $tagNo = trim((string) ($data['tag_no'] ?? $data['tag'] ?? ''));
                $warehouseId = trim((string) ($data['warehouse_id'] ?? ''));
                $warehouseName = trim((string) ($data['warehouse'] ?? ''));
                $existing = null;

                if (trim((string) ($data['id'] ?? '')) !== '') {
                    $existing = FinishProduct::find((int) $data['id']);
                }

                if (! $existing && $tagNo !== '') {
                    $existing = FinishProduct::where('tag_no', $tagNo)->first();
                }

                if ($tagNo === '') {
                    $skipped++;
                    $errors[] = 'Line ' . $lineNumber . ': Missing tag_no.';
                    continue;
                }

                $productId = trim((string) ($data['category_id'] ?? $data['product_id'] ?? ''));
                $finishedProductName = $this->csvFirstValue($data, [
                    'product_name',
                    'finished_product_name',
                    'item_name',
                    'title',
                    'name',
                ]);
                $categoryName = $this->csvFirstValue($data, [
                    'category_name',
                    'category',
                    'new_category_name',
                    'new_product_name',
                ]);
                $categoryPrefix = strtoupper($this->csvFirstValue($data, [
                    'category_prefix',
                    'new_category_prefix',
                    'new_product_prefix',
                    'product_prefix',
                    'prefix',
                ]));

                if ($productId === '') {
                    if ($categoryName === '' && $categoryPrefix === '') {
                        $skipped++;
                        $errors[] = 'Line ' . $lineNumber . ': Missing category_id or category_name/category_prefix.';
                        continue;
                    }

                    $product = Product::where('is_deleted', 0)
                        ->where(function ($query) use ($categoryName, $categoryPrefix) {
                            if ($categoryName !== '') {
                                $query->where('name', $categoryName);
                            }

                            if ($categoryPrefix !== '') {
                                if ($categoryName !== '') {
                                    $query->orWhere('prefix', $categoryPrefix);
                                } else {
                                    $query->where('prefix', $categoryPrefix);
                                }
                            }
                        })
                        ->first();

                    if (! $product) {
                        $product = Product::create([
                            'name' => $categoryName !== '' ? $categoryName : $categoryPrefix,
                            'prefix' => $categoryPrefix !== '' ? $categoryPrefix : Str::upper(Str::substr($categoryName, 0, 3)),
                            'is_active' => 1,
                            'is_deleted' => 0,
                            'createdby_id' => Auth::id(),
                        ]);
                    }

                    $productId = (string) $product->id;
                }

                if ($finishedProductName === '') {
                    $finishedProductName = $tagNo;
                }

                $warehouse = null;

                if ($warehouseId !== '') {
                    $warehouse = Warehouse::where('is_deleted', 0)->find((int) $warehouseId);
                } elseif ($warehouseName !== '') {
                    $warehouse = Warehouse::where('is_deleted', 0)->where('name', $warehouseName)->first();
                }

                if (! $warehouse) {
                    $warehouse = Warehouse::where('is_deleted', 0)->orderBy('id')->first();
                }

                if (! $warehouse) {
                    $skipped++;
                    $errors[] = 'Line ' . $lineNumber . ': No warehouse found for import.';
                    continue;
                }

                $payload = [
                    'id' => $existing->id ?? null,
                    'tag_no' => $tagNo,
                    'product_id' => (int) $productId,
                    'product_name' => $finishedProductName,
                    'warehouse_id' => $warehouse->id,
                    'picture' => $this->csvImagePath($data, $existing->picture ?? null),
                    'barcode' => 'barcodes/' . $tagNo . '.png',
                    'short_description' => $this->csvFirstValue($data, ['short_description', 'short_desc', 'summary']),
                    'long_description' => $this->csvFirstValue($data, ['long_description', 'description', 'details']),
                    'tags' => $this->csvFirstValue($data, ['tags', 'tag_names']),
                    'sizes' => $this->csvSizes($data['sizes'] ?? null),
                    'stock_quantity' => (int) $this->csvNumber($data['quantity'] ?? null),
                    'is_parent' => $this->csvBoolean($data['is_parent'] ?? null, false) ? 1 : 0,
                    'parent_id' => (int) ($data['parent_id'] ?? 0),
                    'gold_carat' => $this->csvNumber($data['gold_carat'] ?? null),
                    'scale_weight' => $this->csvNumber($data['scale_weight'] ?? null),
                    'bead_weight' => $this->csvNumber($data['bead_weight'] ?? null),
                    'stones_weight' => $this->csvNumber($data['stones_weight'] ?? null),
                    'diamond_weight' => $this->csvNumber($data['diamond_weight'] ?? null),
                    'net_weight' => $this->csvNumber($data['net_weight'] ?? null),
                    'waste_per' => $this->csvNumber($data['waste_per'] ?? null),
                    'waste' => $this->csvNumber($data['waste'] ?? null),
                    'gross_weight' => $this->csvNumber($data['gross_weight'] ?? null),
                    'making_gram' => $this->csvNumber($data['making_gram'] ?? null),
                    'making' => $this->csvNumber($data['making'] ?? null),
                    'laker' => $this->csvNumber($data['laker'] ?? null),
                    'total_bead_price' => $this->csvNumber($data['total_bead_price'] ?? null),
                    'total_stones_price' => $this->csvNumber($data['total_stones_price'] ?? null),
                    'total_diamond_price' => $this->csvNumber($data['total_diamond_price'] ?? null),
                    'other_amount' => $this->csvNumber($data['other_amount'] ?? null),
                    'gold_rate' => $this->csvNumber($data['gold_rate'] ?? null),
                    'total_gold_price' => $this->csvNumber($data['total_gold_price'] ?? null),
                    'total_amount' => $this->csvMoney($data['total_amount'] ?? null),
                    'is_active' => $this->csvBoolean($data['is_active'] ?? null, true) ? 1 : 0,
                    'is_deleted' => 0,
                    'createdby_id' => Auth::id(),
                    'updatedby_id' => Auth::id(),
                ];

                if ($payload['total_amount'] === 0.0) {
                    $payload['total_amount'] = round(
                        $payload['total_bead_price'] +
                        $payload['total_stones_price'] +
                        $payload['total_diamond_price'] +
                        $payload['other_amount'] +
                        $payload['making'] +
                        $payload['laker'] +
                        $payload['total_gold_price'],
                        3
                    );
                }

                File::ensureDirectoryExists(public_path('barcodes'));
                $generator = new BarcodeGeneratorPNG();
                file_put_contents(public_path($payload['barcode']), $generator->getBarcode($tagNo, $generator::TYPE_CODE_39));

                $saved = $this->finish_product_service->save($payload);

                if ($saved) {
                    $existing ? $updated++ : $created++;
                } else {
                    $skipped++;
                    $errors[] = 'Line ' . $lineNumber . ': Could not save the record.';
                }
            }

            fclose($handle);
        } catch (Exception $e) {
            fclose($handle);

            return redirect()->back()->withErrors([
                'csv_file' => 'Import failed. Please check the CSV format and try again.',
            ]);
        }

        $message = "CSV import complete. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}.";

        return redirect()->back()
            ->with('success', $message)
            ->with('import_errors', array_slice($errors, 0, 10));
    }

    public function downloadCsvSample()
    {
        abort_if(Gate::denies('products_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rows = [
            ['tag_no', 'category_name', 'category_prefix', 'product_name', 'image_filename', 'short_description', 'long_description', 'tags', 'warehouse', 'gold_carat', 'scale_weight', 'bead_weight', 'stones_weight', 'diamond_weight', 'net_weight', 'waste_per', 'waste', 'gross_weight', 'making_gram', 'making', 'laker', 'total_bead_price', 'total_stones_price', 'total_diamond_price', 'other_amount', 'gold_rate', 'total_gold_price', 'total_amount', 'is_active'],
            ['TAG-001', 'Necklace', 'NEC', 'Ruby Necklace 18K', 'ruby-necklace.png', 'Hand finished ruby necklace', 'Long description for the storefront product page.', 'hot sale,ruby,necklace', 'Main Warehouse', '18', '12.500', '0', '0', '0', '12.500', '10', '1.250', '13.750', '0.500', '6.875', '0', '0', '0', '0', '0', '0', '0', '6.875', '1'],
        ];

        $callback = function () use ($rows) {
            $output = fopen('php://output', 'wb');

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, 'finished-products-import-sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function csvRowToAssoc(array $columns, array $row): array
    {
        $assoc = [];

        foreach ($columns as $index => $column) {
            $assoc[$column] = $row[$index] ?? null;
        }

        return $assoc;
    }

    private function isBlankCsvRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function csvBoolean($value, bool $default): bool
    {
        $value = strtolower(trim((string) $value));

        if ($value === '') {
            return $default;
        }

        return in_array($value, ['1', 'true', 'yes', 'y', 'active'], true);
    }

    private function csvNumber($value): float
    {
        $value = trim((string) $value);

        return $value === '' ? 0.0 : (float) $value;
    }

    private function csvMoney($value): float
    {
        $value = str_replace(',', '', trim((string) $value));
        $value = preg_replace('/[^0-9.]/', '', $value);

        return $value === '' ? 0.0 : (float) $value;
    }

    private function csvSizes($value): ?string
    {
        $value = trim((string) $value);

        if ($value === '' || strtoupper($value) === 'N/A') {
            return null;
        }

        return $value;
    }

    private function csvFirstValue(array $data, array $keys): string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data) && trim((string) $data[$key]) !== '') {
                return trim((string) $data[$key]);
            }
        }

        return '';
    }

    private function csvImagePath(array $data, ?string $fallback): ?string
    {
        $image = $this->csvFirstValue($data, [
            'image_filename',
            'image_name',
            'image',
            'picture_name',
            'picture',
            'image_path',
        ]);

        if ($image === '') {
            return $fallback;
        }

        $image = ltrim(str_replace('\\', '/', $image), '/');

        if (Str::contains($image, '/')) {
            return $image;
        }

        return 'pictures/' . $image;
    }

    private function floatInput($value): float
    {
        $value = trim((string) $value);

        return $value === '' ? 0.0 : (float) $value;
    }

    private function resolveProductId(Request $request): int
    {
        $newProductName = trim((string) $request->input('new_product_name', ''));
        $newProductPrefix = strtoupper(trim((string) $request->input('new_product_prefix', '')));
        $productId = (int) $request->input('product_id', 0);

        if ($newProductName !== '') {
            $existing = Product::where('is_deleted', 0)
                ->where(function ($query) use ($newProductName, $newProductPrefix) {
                    $query->where('name', $newProductName);

                    if ($newProductPrefix !== '') {
                        $query->orWhere('prefix', $newProductPrefix);
                    }
                })
                ->first();

            if ($existing) {
                return (int) $existing->id;
            }

            $product = Product::create([
                'name' => $newProductName,
                'prefix' => $newProductPrefix !== '' ? $newProductPrefix : Str::upper(Str::substr($newProductName, 0, 3)),
                'is_active' => 1,
                'is_deleted' => 0,
                'createdby_id' => Auth::id(),
                'updatedby_id' => Auth::id(),
            ]);

            return (int) $product->id;
        }

        if ($productId > 0) {
            return $productId;
        }

        throw new Exception('Please select an existing category or create a new category name.');
    }

    private function finishProductName(Request $request, int $productId): string
    {
        $name = trim((string) $request->input('product_name', ''));

        if ($name !== '') {
            return $name;
        }

        $category = Product::find($productId);

        return $category->name ?? $request->input('tag_no');
    }
}
