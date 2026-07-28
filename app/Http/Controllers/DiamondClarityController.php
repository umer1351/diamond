<?php

namespace App\Http\Controllers;

use App\Services\Concrete\DiamondClarityService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DiamondClarityController extends Controller
{
    use JsonResponse;
    protected $diamond_clarity_service;

    public function __construct(DiamondClarityService $diamond_clarity_service)
    {
        $this->diamond_clarity_service = $diamond_clarity_service;
    }

    public function index()
    {
        
        abort_if(Gate::denies('diamond_clarity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('diamond_clarity.index');
    }

    public function getData()
    {
        abort_if(Gate::denies('diamond_clarity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return $this->diamond_clarity_service->getSource();
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('diamond_clarity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required'
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
                    'name' => $request->name
                ];
                $response = $this->diamond_clarity_service->update($obj);
                return  $this->success(
                    config("enum.updated"),
                    $response
                );
            } else {
                $obj = [
                    'name' => $request->name
                ];
                $response = $this->diamond_clarity_service->save($obj);
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
        abort_if(Gate::denies('diamond_clarity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return  $this->success(
                config('enum.success'),
                $this->diamond_clarity_service->getById($id),
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function status($id)
    {
        abort_if(Gate::denies('diamond_clarity_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $diamond_clarity = $this->diamond_clarity_service->statusById($id);
            return $this->success(
                config("enum.status"),
                $diamond_clarity,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {abort_if(Gate::denies('diamond_clarity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $diamond_clarity = $this->diamond_clarity_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                $diamond_clarity,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
}
