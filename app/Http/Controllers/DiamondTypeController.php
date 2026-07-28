<?php

namespace App\Http\Controllers;

use App\Services\Concrete\DiamondTypeService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DiamondTypeController extends Controller
{
    use JsonResponse;
    protected $diamond_type_service;

    public function __construct(DiamondTypeService $diamond_type_service)
    {
        $this->diamond_type_service = $diamond_type_service;
    }

    public function index()
    {
        abort_if(Gate::denies('diamond_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('diamond_type.index');
    }

    public function getData()
    {
        abort_if(Gate::denies('diamond_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return $this->diamond_type_service->getSource();
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('diamond_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
                $response = $this->diamond_type_service->update($obj);
                return  $this->success(
                    config("enum.updated"),
                    $response
                );
            } else {
                $obj = [
                    'name' => $request->name
                ];
                $response = $this->diamond_type_service->save($obj);
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
        abort_if(Gate::denies('diamond_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return  $this->success(
                config('enum.success'),
                $this->diamond_type_service->getById($id),
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function status($id)
    {
        abort_if(Gate::denies('diamond_type_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $diamond_type = $this->diamond_type_service->statusById($id);
            return $this->success(
                config("enum.status"),
                $diamond_type,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('diamond_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $diamond_type = $this->diamond_type_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                $diamond_type,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
}
