<?php

namespace App\Http\Controllers;

use App\Models\OtherProduct;
use App\Services\Concrete\OtherProductService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OtherProductController extends Controller
{
    use JsonResponse;
    protected $other_product_service;

    public function __construct(OtherProductService $other_product_service)
    {
        $this->other_product_service = $other_product_service;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('other_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $other_product_units = $this->other_product_service->getAllActiveOtherProductUnit();
            return view('other_product.index',compact('other_product_units'));
    }

    public function getData()
    {
        abort_if(Gate::denies('other_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return $this->other_product_service->getSource();
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    
    public function store(Request $request)
    {

        abort_if(Gate::denies('other_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'other_product_unit_id' => 'required'
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
        try {
            if (isset($request->id)) {
                $obj = [
                    'id' => $request->id,
                    'name' => $request->name,
                    'other_product_unit_id' => $request->other_product_unit_id
                ];
                $response = $this->other_product_service->update($obj);
                return  $this->success(
                    config("enum.updated"),
                    $response
                );
            } else {
                $obj = [
                    'code'=>$this->generateProductCode($request->name),
                    'name' => $request->name,
                    'other_product_unit_id' => $request->other_product_unit_id
                ];
                $response = $this->other_product_service->save($obj);
                return  $this->success(
                    config("enum.saved"),
                    $response
                );
            }
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('other_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return  $this->success(
                config('enum.success'),
                $this->other_product_service->getById($id),
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function status($id)
    {
        abort_if(Gate::denies('other_product_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $other_product = $this->other_product_service->statusById($id);
            return $this->success(
                config("enum.status"),
                $other_product,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('other_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $other_product = $this->other_product_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                $other_product,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public static function generateProductCode($productName)
    {
        // Get the first two letters of the product name and convert to uppercase
        $prefix = strtoupper(substr($productName, 0, 2));
        
        // Generate a random 4-digit number
        $number = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Combine the prefix and the 4-digit number
        $code = $prefix . $number;

        // Check if the code is unique; regenerate if necessary
        while (OtherProduct::where('code', $code)->exists()) {
            $number = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $code = $prefix . $number;
        }

        return $code;
    }
}
