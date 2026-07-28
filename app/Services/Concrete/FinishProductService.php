<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\FinishProduct;
use App\Models\FinishProductBead;
use App\Models\FinishProductDiamond;
use App\Models\FinishProductStone;
use App\Models\JobPurchaseDetail;
use App\Models\Product;
use App\Models\RattiKaatDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class FinishProductService
{
    // initialize protected model variables
    protected $model_finish_product;
    protected $model_finish_product_bead;
    protected $model_finish_product_stone;
    protected $model_finish_product_diamond;

    public function __construct()
    {
        // set the model
        $this->model_finish_product = new Repository(new FinishProduct);
        $this->model_finish_product_bead = new Repository(new FinishProductBead);
        $this->model_finish_product_stone = new Repository(new FinishProductStone);
        $this->model_finish_product_diamond = new Repository(new FinishProductDiamond);
    }

    public function getFinishProductSource()
    {
        $model = $this->model_finish_product->getModel()::with(['product', 'warehouse', 'parent_name'])->where('is_deleted', 0);

        $data = DataTables::of($model)
            ->addColumn('product', function ($item) {
                return $item->product->name ?? '';
            })
            ->addColumn('warehouse', function ($item) {
                return $item->warehouse->name ?? '';
            })
            ->addColumn('parent', function ($item) {

                return $item->parent_name->tag_no ?? '';
            })
            ->addColumn('is_parent', function ($item) {
                if ($item->is_parent == 1) {
                    $parent = '<span class=" badge badge-success mr-3">Yes</span>';
                } else {
                    $parent = '<span class=" badge badge-danger mr-3">No</span>';
                }
                return $parent;
            })
            ->addColumn('saled', function ($item) {
                if ($item->is_saled == 1) {
                    $saled = '<span class=" badge badge-success mr-3">Yes</span>';
                } else {
                    $saled = '<span class=" badge badge-danger mr-3">No</span>';
                }
                return $saled;
            })
            ->addColumn('status', function ($item) {
                if (Auth::user()->can('tagging_product_create')) {
                    if ($item->is_active == 1) {
                        $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                    } else {
                        $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                    }
                    return $status;
                } else {
                    return 'N/A';
                }
            })

            ->addColumn('action', function ($item) {
                $action_column = '';
                $view_column    = "<a class='text-warning mr-2' href='finish-product/view/" . $item->id . "'><i title='Add' class='nav-icon mr-2 fa fa-eye'></i>View</a>";
                $print_column    = "<a class='text-info mr-2' href='finish-product/print/" . $item->id . "'><i title='Print' class='nav-icon mr-2 fa fa-print'></i>Tag Print</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteFinishProduct' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('tagging_product_view'))
                    $action_column .= $view_column;


                if (Auth::user()->can('tagging_product_delete'))
                    $action_column .= $delete_column;

                // if (Auth::user()->can('tagging_product_view'))
                //     $action_column .= $print_column;

                return $action_column;
            })
            ->rawColumns(['product', 'warehouse', 'parent', 'is_parent', 'saled', 'status', 'action'])
            ->make(true);
        return $data;
    }

    public function getAllActiveFinishProduct()
    {
        return $this->model_finish_product->getModel()::with(['product', 'warehouse'])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('is_saled', 0)
            ->get();
    }

    public function getAllActiveParentFinishProduct()
    {
        return $this->model_finish_product->getModel()::with(['product', 'warehouse'])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('is_saled', 0)
            ->where('is_parent', 1)
            ->get();
    }

    public function getAllSaledFinishProduct()
    {
        return $this->model_finish_product->getModel()::with(['product', 'warehouse'])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('is_saled', 1)
            ->where('is_parent', 0)
            ->get();
    }

    public function save($obj)
    {
        try {
            DB::beginTransaction();
            $oldProductId = null;
            $finishProduct = [
                "tag_no" => $obj['tag_no'],
                "parent_id" => $obj['parent_id'] ?? 0,
                "is_parent" => $obj['is_parent'],
                "job_purchase_id" => $obj['job_purchase_id'] ?? null,
                "job_purchase_detail_id" => $obj['job_purchase_detail_id'] ?? null,
                "ratti_kaat_id" => $obj['ratti_kaat_id'] ?? null,
                "ratti_kaat_detail_id" => $obj['ratti_kaat_detail_id'] ?? null,
                "product_id" => $obj['product_id'] ?? null,
                "product_name" => $obj['product_name'] ?? null,
                "warehouse_id" => $obj['warehouse_id'] ?? null,
                "short_description" => $obj['short_description'] ?? null,
                "long_description" => $obj['long_description'] ?? null,
                "tags" => $obj['tags'] ?? null,
                "gold_carat" => $obj['gold_carat'] ?? 0,
                "scale_weight" => $obj['scale_weight'] ?? 0,
                "net_weight" => $obj['net_weight'] ?? 0,
                "bead_weight" => $obj['bead_weight'] ?? 0,
                "stones_weight" => $obj['stones_weight'] ?? 0,
                "diamond_weight" => $obj['diamond_weight'] ?? 0,
                "waste_per" => $obj['waste_per'] ?? 0,
                "waste" => $obj['waste'] ?? 0,
                "gross_weight" => $obj['gross_weight'] ?? 0,
                "laker" => $obj['laker'] ?? 0,
                "making_gram" => $obj['making_gram'] ?? 0,
                "making" => $obj['making'] ?? 0,
                "total_bead_price" => $obj['total_bead_price'] ?? 0,
                "total_stones_price" => $obj['total_stones_price'] ?? 0,
                "total_diamond_price" => $obj['total_diamond_price'] ?? 0,
                "other_amount" => $obj['other_amount'] ?? 0,
                "gold_rate" => $obj['gold_rate'] ?? 0,
                "total_gold_price" => $obj['total_gold_price'] ?? 0,
                "total_amount" => $obj['total_amount'] ?? 0,
                "picture" => $obj['picture'] ?? null,
                "barcode" => $obj['barcode'] ?? null,
                "createdby_id" => Auth::user()->id,
                "updatedby_id" => Auth::user()->id
            ];
            if (! empty($obj['id'])) {
                $saved_obj = $this->model_finish_product->getModel()::find($obj['id']);

                if (! $saved_obj) {
                    DB::rollBack();
                    return false;
                }

                $oldProductId = $saved_obj->product_id;

                if (! filled($finishProduct['picture'])) {
                    unset($finishProduct['picture']);
                }

                if (! filled($finishProduct['barcode'])) {
                    unset($finishProduct['barcode']);
                }

                unset($finishProduct['createdby_id']);
                $saved_obj->update($finishProduct);
            } else {
                $saved_obj = $this->model_finish_product->create($finishProduct);
            }

            $this->syncProductStock($saved_obj->product_id);

            if ($oldProductId && (int) $oldProductId !== (int) $saved_obj->product_id) {
                $this->syncProductStock($oldProductId);
            }

            if (filled($obj['beadDetail'] ?? null)) {
                $beadDetail = json_decode($obj['beadDetail']);

                if (is_array($beadDetail)) {
                    foreach ($beadDetail as $item) {
                        $finishProductBead = [
                            "finish_product_id" => $saved_obj->id,
                            "type" => $item->type,
                            "beads" => $item->beads,
                            "gram" => $item->gram,
                            "carat" => $item->carat,
                            "gram_rate" => $item->gram_rate,
                            "carat_rate" => $item->carat_rate,
                            "total_amount" => $item->total_amount,
                        ];
                        $this->model_finish_product_bead->create($finishProductBead);
                    }
                }
            }

            if (filled($obj['stonesDetail'] ?? null)) {
                $stoneDetail = json_decode($obj['stonesDetail']);

                if (is_array($stoneDetail)) {
                    foreach ($stoneDetail as $item) {
                        $finishProductStone = [
                            "finish_product_id" => $saved_obj->id,
                            "category" => $item->category,
                            "type" => $item->type,
                            "stones" => $item->stones,
                            "gram" => $item->gram,
                            "carat" => $item->carat,
                            "gram_rate" => $item->gram_rate,
                            "carat_rate" => $item->carat_rate,
                            "total_amount" => $item->total_amount,
                        ];
                        $this->model_finish_product_stone->create($finishProductStone);
                    }
                }
            }

            if (filled($obj['diamondDetail'] ?? null)) {
                $diamondDetail = json_decode($obj['diamondDetail']);

                if (is_array($diamondDetail)) {
                    foreach ($diamondDetail as $item) {
                        $finishProductDiamond = [
                            "finish_product_id" => $saved_obj->id,
                            "type" => $item->type,
                            "diamonds" => $item->diamonds,
                            "color" => $item->color,
                            "cut" => $item->cut,
                            "clarity" => $item->clarity,
                            "carat" => $item->carat,
                            "carat_rate" => $item->carat_rate,
                            "total_amount" => $item->total_amount,
                        ];
                        $this->model_finish_product_diamond->create($finishProductDiamond);
                    }
                }
            }

            if (! empty($obj['ratti_kaat_detail_id'])) {
                $ratti_kaat_detail = RattiKaatDetail::find($obj['ratti_kaat_detail_id']);
                if ($ratti_kaat_detail) {
                    $ratti_kaat_detail->is_finish_product = 1;
                    $ratti_kaat_detail->updatedby_id = Auth::user()->id;
                    $ratti_kaat_detail->update();
                }
            } elseif (! empty($obj['job_purchase_detail_id'])) {
                $job_purchase_detail = JobPurchaseDetail::find($obj['job_purchase_detail_id']);
                if ($job_purchase_detail) {
                    $job_purchase_detail->is_finish_product = 1;
                    $job_purchase_detail->updatedby_id = Auth::user()->id;
                    $job_purchase_detail->update();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }

        return $saved_obj;
    }

    public function getById($id)
    {
        return $this->model_finish_product->getModel()::with([
            'ratti_kaat',
            'ratti_kaat_detail',
            'product',
            'warehouse'
        ])->find($id);
    }

    public function getByTagNo($tag_no)
    {
        $finish_product = $this->model_finish_product->getModel()::with([
            'ratti_kaat',
            'ratti_kaat_detail',
            'product',
            'warehouse'
        ])->where('tag_no', $tag_no)
            ->first();
        if ($finish_product->is_parent == 1) {
            $finish_products = $this->model_finish_product->getModel()::with([
                'ratti_kaat',
                'ratti_kaat_detail',
                'product',
                'warehouse'
            ])->where('parent_id', $finish_product->id)->get();
            $data = [];
            foreach ($finish_products as $item) {
                $beadDetail = $this->model_finish_product_bead->getModel()::select('type', 'finish_product_id', 'beads', 'gram', 'carat', 'gram_rate', 'carat_rate', 'total_amount')
                    ->where('finish_product_id', $item->id)
                    ->where('is_deleted', 0)->get();
                $stonesDetail = $this->model_finish_product_stone->getModel()::select('category', 'type', 'finish_product_id', 'stones', 'gram', 'carat', 'gram_rate', 'carat_rate', 'total_amount')
                    ->where('finish_product_id', $item->id)
                    ->where('is_deleted', 0)->get();
                $diamondDetail = $this->model_finish_product_diamond->getModel()::select('diamonds', 'type', 'finish_product_id', 'color', 'cut', 'clarity', 'carat', 'carat_rate', 'total_amount', 'total_dollar')
                    ->where('finish_product_id', $item->id)
                    ->where('is_deleted', 0)->get();
                $data[] = [
                    "tag_no" => $item->tag_no ?? '',
                    "finish_product_id" => $item->id,
                    "ratti_kaat_id" => $item->ratti_kaat_id ?? '',
                    "ratti_kaat_detail_id" => $item->ratti_kaat_detail_id ?? '',
                    "job_purchase_detail_id" => $item->job_purchase_detail_id ?? '',
                    "product" => $item->product->name ?? '',
                    "product_id" => $item->product_id ?? '',
                    "gold_carat" => $item->gold_carat ?? 0,
                    "scale_weight" => $item->scale_weight ?? 0,
                    "bead_weight" => $item->bead_weight ?? 0,
                    "stones_weight" => $item->stones_weight ?? 0,
                    "diamond_weight" => $item->diamond_weight ?? 0,
                    "net_weight" => $item->net_weight ?? 0,
                    "gross_weight" => $item->gross_weight ?? 0,
                    "waste" => $item->waste ?? 0,
                    "making" => $item->making ?? 0,
                    "gold_rate" => $item->gold_rate ?? 0,
                    "total_gold_price" => $item->total_gold_price ?? 0,
                    "other_amount" => $item->other_amount ?? 0,
                    "total_bead_price" => $item->total_bead_price ?? 0,
                    "total_stones_price" => $item->total_stones_price ?? 0,
                    "total_diamond_price" => $item->total_diamond_price ?? 0,
                    "total_amount" => $item->total_amount ?? 0,
                    "beadDetail" => $beadDetail,
                    "stonesDetail" => $stonesDetail,
                    "diamondDetail" => $diamondDetail
                ];
            }
            return $data;
        }
        return $finish_product;
    }

    public function getBeadByFinishProductId($finish_product_id)
    {
        return $this->model_finish_product_bead->getModel()::with('finish_product')
            ->where('finish_product_id', $finish_product_id)
            ->where('is_deleted', 0)
            ->get();
    }
    public function getStoneByFinishProductId($finish_product_id)
    {
        return $this->model_finish_product_stone->getModel()::with('finish_product')
            ->where('finish_product_id', $finish_product_id)
            ->where('is_deleted', 0)
            ->get();
    }
    public function getDiamondByFinishProductId($finish_product_id)
    {
        return $this->model_finish_product_diamond->getModel()::with('finish_product')
            ->where('finish_product_id', $finish_product_id)
            ->where('is_deleted', 0)
            ->get();
    }

    public function statusById($id)
    {
        $finish_product = $this->model_finish_product->getModel()::find($id);
        if ($finish_product->is_active == 0) {
            $finish_product->is_active = 1;
        } else {
            $finish_product->is_active = 0;
        }
        $finish_product->updatedby_id = Auth::user()->id;
        $finish_product->update();
        $this->syncProductStock($finish_product->product_id);

        if ($finish_product)
            return true;

        return false;
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            $finish_product = $this->model_finish_product->getModel()::find($id);

            if (! $finish_product) {
                DB::rollBack();
                return false;
            }

            if ($finish_product->is_parent != 1 && filled($finish_product->ratti_kaat_detail_id)) {
                $ratti_kaat_detail = RattiKaatDetail::find($finish_product->ratti_kaat_detail_id);
                if ($ratti_kaat_detail) {
                    $ratti_kaat_detail->is_finish_product = 0;
                    $ratti_kaat_detail->update();
                }
            }
            $finish_product->is_deleted = 1;
            $finish_product->deletedby_id = Auth::user()->id;
            $finish_product->update();
            $this->syncProductStock($finish_product->product_id);

            $finish_product_beads = $this->model_finish_product_bead->getModel()::where('finish_product_id', $id)->get();
            foreach ($finish_product_beads as $item) {
                $finish_product_bead = $this->model_finish_product_bead->getModel()::find($item->id);
                $finish_product_bead->is_deleted = 1;
                $finish_product_bead->deletedby_id = Auth::user()->id;
                $finish_product_bead->update();
            }

            $finish_product_stones = $this->model_finish_product_stone->getModel()::where('finish_product_id', $id)->get();
            foreach ($finish_product_stones as $item) {
                $finish_product_stone = $this->model_finish_product_stone->getModel()::find($item->id);
                $finish_product_stone->is_deleted = 1;
                $finish_product_stone->deletedby_id = Auth::user()->id;
                $finish_product_stone->update();
            }

            $finish_product_diamonds = $this->model_finish_product_diamond->getModel()::where('finish_product_id', $id)->get();
            foreach ($finish_product_diamonds as $item) {
                $finish_product_diamond = $this->model_finish_product_diamond->getModel()::find($item->id);
                $finish_product_diamond->is_deleted = 1;
                $finish_product_diamond->deletedby_id = Auth::user()->id;
                $finish_product_diamond->update();
            }

            DB::commit();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    private function syncProductStock($productId): void
    {
        if (empty($productId)) {
            return;
        }

        $stock = FinishProduct::where('product_id', $productId)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->count();

        Product::where('id', $productId)->update(['stock' => $stock]);
    }
}
