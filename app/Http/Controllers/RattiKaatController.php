<?php

namespace App\Http\Controllers;

use App\Models\RattiKaat;
use App\Models\User;
use App\Services\Concrete\AccountService;
use App\Services\Concrete\CommonService;
use App\Services\Concrete\ProductService;
use App\Services\Concrete\RattiKaatService;
use App\Services\Concrete\SupplierService;
use App\Services\Concrete\BeadTypeService;
use App\Services\Concrete\CompanySettingService;
use App\Services\Concrete\DiamondClarityService;
use App\Services\Concrete\DiamondColorService;
use App\Services\Concrete\DiamondCutService;
use App\Services\Concrete\DiamondTypeService;
use App\Services\Concrete\JobPurchaseService;
use App\Services\Concrete\StoneCategoryService;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RattiKaatController extends Controller
{

    use JsonResponse;
    protected $account_service;
    protected $supplier_service;
    protected $product_service;
    protected $ratti_kaat_service;
    protected $common_service;
    protected $bead_type_service;
    protected $stone_category_service;
    protected $diamond_type_service;
    protected $diamond_color_service;
    protected $diamond_cut_service;
    protected $diamond_clarity_service;
    protected $job_purchase_service;
    protected $company_setting_service;

    public function __construct(
        SupplierService $supplier_service,
        AccountService $account_service,
        ProductService $product_service,
        RattiKaatService $ratti_kaat_service,
        CommonService $common_service,
        BeadTypeService $bead_type_service,
        StoneCategoryService $stone_category_service,
        DiamondTypeService $diamond_type_service,
        DiamondColorService $diamond_color_service,
        DiamondCutService $diamond_cut_service,
        DiamondClarityService $diamond_clarity_service,
        JobPurchaseService $job_purchase_service,
        CompanySettingService $company_setting_service
    ) {
        $this->account_service = $account_service;
        $this->supplier_service = $supplier_service;
        $this->product_service = $product_service;
        $this->ratti_kaat_service = $ratti_kaat_service;
        $this->common_service = $common_service;
        $this->bead_type_service = $bead_type_service;
        $this->stone_category_service = $stone_category_service;
        $this->diamond_type_service = $diamond_type_service;
        $this->diamond_color_service = $diamond_color_service;
        $this->diamond_cut_service = $diamond_cut_service;
        $this->diamond_clarity_service = $diamond_clarity_service;
        $this->job_purchase_service = $job_purchase_service;
        $this->company_setting_service = $company_setting_service;
    }

    public function index()
    {
        abort_if(Gate::denies('ratti_kaat_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $suppliers = $this->supplier_service->getAllActiveSupplier();
        $setting = $this->company_setting_service->getSetting();
        $accounts = $this->account_service->getAllActiveChild();
        return view('purchases.ratti_kaat.index', compact('suppliers','setting','accounts'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('ratti_kaat_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $end = $request['end_date'] ?? Carbon::now()->addDay(1);
            $start = $request['start_date'] ?? Carbon::now()->subDay(1);
            $supplier_id = $request['supplier_id'] ?? '';
            $posted = $request['posted'] ?? '';
            $obj = [
                "supplier_id" => $supplier_id,
                "end" => $end,
                "start" => $start,
                "posted" => $posted,
            ];
            return $this->ratti_kaat_service->getRattiKaatSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function create()
    {
        abort_if(Gate::denies('ratti_kaat_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $suppliers = $this->supplier_service->getAllActiveSupplier();
        $products = $this->product_service->getAllActiveProduct();
        $ratti_kaat = $this->ratti_kaat_service->saveRattiKaat();
        $bead_types = $this->bead_type_service->getAllActive();
        $stone_categories = $this->stone_category_service->getAllActive();
        $diamond_types = $this->diamond_type_service->getAllActive();
        $diamond_colors = $this->diamond_color_service->getAllActive();
        $diamond_cuts = $this->diamond_cut_service->getAllActive();
        $diamond_clarities = $this->diamond_clarity_service->getAllActive();
        return view('purchases.ratti_kaat.create', compact(
            'suppliers',
            'products',
            'ratti_kaat',
            'bead_types',
            'stone_categories',
            'diamond_types',
            'diamond_colors',
            'diamond_cuts',
            'diamond_clarities'
        ));
    }

    // Bead Weight
    public function getBeadWeight($ratti_kaat_detail_id)
    {
        try {
            $ratti_kaat_beads = $this->ratti_kaat_service->getBeadWeight($ratti_kaat_detail_id);
            return $this->success(
                config('enum.success'),
                $ratti_kaat_beads,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    // public function storeBeadWeight(Request $request)
    // {
    //     $validation = Validator::make(
    //         $request->all(),
    //         [
    //             'type'                      => 'required',
    //             'bead_weight_product_id'    => 'required',
    //             'bead_weight_ratti_kaat_id' => 'required',
    //             'beads'                     => 'required',
    //             'bead_gram'                 => 'required',
    //             'bead_carat'                => 'required',
    //             'bead_gram_rate'            => 'required',
    //             'bead_carat_rate'           => 'required',
    //             'bead_total'                => 'required'
    //         ],
    //         $this->validationMessage()
    //     );

    //     if ($validation->fails()) {
    //         $validation_error = "";
    //         foreach ($validation->errors()->all() as $message) {
    //             $validation_error .= $message;
    //         }
    //         return $this->validationResponse(
    //             $validation_error
    //         );
    //     }
    //     try {
    //         $obj = [
    //             'product_id'        => $request->bead_weight_product_id,
    //             'ratti_kaat_id'     => $request->bead_weight_ratti_kaat_id,
    //             'type'              => $request->type,
    //             'beads'             => $request->beads,
    //             'gram'              => $request->bead_gram,
    //             'carat'             => $request->bead_carat,
    //             'gram_rate'         => $request->bead_gram_rate,
    //             'carat_rate'        => $request->bead_carat_rate,
    //             'total_amount'      => $request->bead_total
    //         ];
    //         $response = $this->ratti_kaat_service->saveBeadWeight($obj);
    //         return  $this->success(
    //             config("enum.saved"),
    //             $response
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }
    // public function destroyBeadWeight($id)
    // {
    //     try {
    //         $beads = $this->ratti_kaat_service->deleteBeadWeightById($id);
    //         return $this->success(
    //             config("enum.delete"),
    //             $beads,
    //             true
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }

    // Stone Weight
    public function getStoneWeight($ratti_kaat_detail_id)
    {
        try {
            $ratti_kaat_stones = $this->ratti_kaat_service->getStoneWeight($ratti_kaat_detail_id);
            return $this->success(
                config('enum.success'),
                $ratti_kaat_stones,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    // public function storeStoneWeight(Request $request)
    // {
    //     $validation = Validator::make(
    //         $request->all(),
    //         [
    //             'stone_weight_product_id'    => 'required',
    //             'stone_weight_ratti_kaat_id' => 'required',
    //             'category'                   => 'required',
    //             'type'                       => 'required',
    //             'stones'                     => 'required',
    //             'stone_gram'                 => 'required',
    //             'stone_carat'                => 'required',
    //             'stone_gram_rate'            => 'required',
    //             'stone_carat_rate'           => 'required',
    //             'stone_total'                => 'required'
    //         ],
    //         $this->validationMessage()
    //     );

    //     if ($validation->fails()) {
    //         $validation_error = "";
    //         foreach ($validation->errors()->all() as $message) {
    //             $validation_error .= $message;
    //         }
    //         return $this->validationResponse(
    //             $validation_error
    //         );
    //     }
    //     try {
    //         $obj = [
    //             'product_id'        => $request->stone_weight_product_id,
    //             'ratti_kaat_id'     => $request->stone_weight_ratti_kaat_id,
    //             'category'          => $request->category,
    //             'type'              => $request->type,
    //             'stones'            => $request->stones,
    //             'gram'              => $request->stone_gram,
    //             'carat'             => $request->stone_carat,
    //             'gram_rate'         => $request->stone_gram_rate,
    //             'carat_rate'        => $request->stone_carat_rate,
    //             'total_amount'      => $request->stone_total
    //         ];
    //         $response = $this->ratti_kaat_service->saveStoneWeight($obj);
    //         return  $this->success(
    //             config("enum.saved"),
    //             $response
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }
    // public function destroyStoneWeight($id)
    // {
    //     try {
    //         $stones = $this->ratti_kaat_service->deleteStoneWeightById($id);
    //         return $this->success(
    //             config("enum.delete"),
    //             $stones,
    //             true
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }

    // Diamond Carat
    public function getDiamondCarat($ratti_kaat_detail_id)
    {
        try {
            $ratti_kaat_diamonds = $this->ratti_kaat_service->getDiamondCarat($ratti_kaat_detail_id);
            return $this->success(
                config('enum.success'),
                $ratti_kaat_diamonds,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    // public function storeDiamondCarat(Request $request)
    // {
    //     $validation = Validator::make(
    //         $request->all(),
    //         [
    //             'diamond_carat_product_id'      => 'required',
    //             'diamond_carat_ratti_kaat_id'   => 'required',
    //             'diamonds'                      => 'required',
    //             'type'                          => 'required',
    //             'color'                         => 'required',
    //             'clarity'                       => 'required',
    //             'cut'                           => 'required',
    //             'carat'                         => 'required',
    //             'carat_rate'                    => 'required',
    //             'diamond_total'                 => 'required',
    //             'diamond_total_dollar'          => 'required'
    //         ],
    //         $this->validationMessage()
    //     );

    //     if ($validation->fails()) {
    //         $validation_error = "";
    //         foreach ($validation->errors()->all() as $message) {
    //             $validation_error .= $message;
    //         }
    //         return $this->validationResponse(
    //             $validation_error
    //         );
    //     }
    //     try {
    //         $obj = [
    //             'product_id'        => $request->diamond_carat_product_id,
    //             'ratti_kaat_id'     => $request->diamond_carat_ratti_kaat_id,
    //             'diamonds'          => $request->diamonds,
    //             'type'              => $request->type,
    //             'cut'               => $request->cut,
    //             'color'             => $request->color,
    //             'clarity'           => $request->clarity,
    //             'carat'             => $request->carat,
    //             'carat_rate'        => $request->carat_rate,
    //             'total_amount'      => $request->diamond_total,
    //             'total_dollar'      => $request->diamond_total_dollar,
    //         ];
    //         $response = $this->ratti_kaat_service->saveDiamondCarat($obj);
    //         return  $this->success(
    //             config("enum.saved"),
    //             $response
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }
    // public function destroyDiamondCarat($id)
    // {
    //     try {
    //         $diamond = $this->ratti_kaat_service->deleteDiamondCaratById($id);
    //         return $this->success(
    //             config("enum.delete"),
    //             $diamond,
    //             true
    //         );
    //     } catch (Exception $e) {
    //         return $this->error(config('enum.error'));
    //     }
    // }

    // Ratti Kaat Purchase Store
    public function store(Request $request)
    {
        abort_if(Gate::denies('ratti_kaat_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'id'                => 'required',
                'purchase_date'     => 'required',
                'supplier_id'       => 'required',
                'reference'         => 'required',
                'pictures.*'        => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'purchaseDetail'    => 'required',
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            $validation_error = "";
            foreach ($validation->errors()->all() as $message) {
                $validation_error .= $message;
            }
            return $this->validationResponse(
                $validation_error
            );
        }

        // try {
            $filenames = [];
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('pictures'), $filename);
                    $filenames[] = 'pictures/' . $filename;
                }
            }
            $obj = [
                "id" => $request->id,
                "purchase_date" => $request->purchase_date ?? Null,
                "supplier_id" => $request->supplier_id ?? Null,
                // "purchase_account" => $request->purchase_account ?? Null,
                "paid" => ($request->paid != '') ? $request->paid : 0,
                // "paid_account" => $request->paid_account ?? Null,
                "paid_au" => ($request->paid_au != '') ? $request->paid_au : 0,
                // "paid_account_au" => $request->paid_account_au ?? Null,
                "paid_dollar" => ($request->paid_dollar != '') ? $request->paid_dollar : 0,
                // "paid_account_dollar" => $request->paid_account_dollar ?? Null,
                "reference" => $request->reference ?? Null,
                "pictures" => $filenames ?? Null,
                "purchaseDetail" => $request->purchaseDetail
            ];

            $ratti_kaat = $this->ratti_kaat_service->updateRattiKaat($obj, $request->id);

            return $this->success(
                config('enum.saved'),
                []
            );
        // } catch (Exception $e) {
        //     return $this->error(config('enum.error'));
        // }
    }


    public function edit($id)
    {
        abort_if(Gate::denies('ratti_kaat_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $suppliers = $this->supplier_service->getAllActiveSupplier();
        $products = $this->product_service->getAllActiveProduct();
        $bead_types = $this->bead_type_service->getAllActive();
        $stone_categories = $this->stone_category_service->getAllActive();
        $diamond_types = $this->diamond_type_service->getAllActive();
        $diamond_colors = $this->diamond_color_service->getAllActive();
        $diamond_cuts = $this->diamond_cut_service->getAllActive();
        $diamond_clarities = $this->diamond_clarity_service->getAllActive();
        $ratti_kaat = $this->ratti_kaat_service->getRattiKaatById($id);
        return view('purchases.ratti_kaat.create', compact(
            'suppliers',
            'products',
            'ratti_kaat',
            'bead_types',
            'stone_categories',
            'diamond_types',
            'diamond_colors',
            'diamond_cuts',
            'diamond_clarities'
        ));
    }


    public function rattiKaatDetail($raat_kaat_id)
    {
        try {
            $ratti_kaat_detail = $this->ratti_kaat_service->getRattiKaatDetail($raat_kaat_id);
            return $this->success(
                config('enum.success'),
                $ratti_kaat_detail,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function ChangeKaat(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'email'       => 'required',
                'password'    => 'required',
                'kaat'        => 'required',
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            $validation_error = "";
            foreach ($validation->errors()->all() as $message) {
                $validation_error .= $message;
            }
            return $this->validationResponse(
                $validation_error
            );
        }
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            return $this->success(
                config('enum.success'),
                [
                    "approved_by" => $user->id,
                    "kaat" => number_format($request->kaat, 3)
                ]
            );
        } else {
            return $this->error(
                'Invalid credentials'
            );
        }
    }

    // Ratti Kaat Post
    public function postRattiKaat(Request $request)
    {
        abort_if(Gate::denies('ratti_kaat_post'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $ratti_kaat_post = $this->ratti_kaat_service->postRattiKaat($request->all());
            if ($ratti_kaat_post != 'true') {
                return $this->validationResponse(
                    $ratti_kaat_post
                );
            }
            return $this->success(
                config('anum.posted'),
                [],
                true
            );
        } catch (Exception $e) {
            return $this->error(config('anum.error'));
        }
    }
    public function unpostRattiKaat($id)
    {
        abort_if(Gate::denies('ratti_kaat_post'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->ratti_kaat_service->unpostRattiKaatById($id);
            return $this->success(
                config('enum.unposted'),
                $response,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'),);
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('ratti_kaat_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->ratti_kaat_service->deleteRattiKaatById($id);
            return $this->success(
                config('enum.delete'),
                $response,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.noDelete'),);
        }
    }

    public function getRattiKaatByProductId($product_id)
    {

        try {
            $product = $this->product_service->getProductById($product_id);
            $ratti_kaats = $this->ratti_kaat_service->getRattiKaatByProductId($product_id);
            $job_purchase = $this->job_purchase_service->getJobPurchaseByProductId($product_id);
            $tag_no = $this->common_service->generateFinishProductTagNo($product->prefix);
            $data = [
                "ratti_kaat" => $ratti_kaats,
                "job_purchase" => $job_purchase,
                "tag_no" => $tag_no
            ];
            return $this->success(
                config('enum.success'),
                $data
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getRattiKaatDetailById($detail_id)
    {
        try {
            $response = $this->ratti_kaat_service->getRattiKaatDetailById($detail_id);
            return $this->success(
                config('enum.success'),
                $response,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'),);
        }
    }
}
