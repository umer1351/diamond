<?php

namespace App\Http\Controllers;

use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class WarehouseController extends Controller
{
    use JsonResponse;
    protected $warehouse_service;


    public function __construct(
        WarehouseService  $warehouse_service
    ) {
        $this->warehouse_service = $warehouse_service;
    }

    public function index()
    {
        abort_if(Gate::denies('warehouses_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('warehouses.index');
    }


    public function getData(Request $request)
    {
        abort_if(Gate::denies('warehouses_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->warehouse_service->getWarehouseSource();
    }

    public function create()
    {
        abort_if(Gate::denies('warehouses_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('warehouses_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:50']
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $obj = [
                "id"        => $request->id,
                "name"      => $request->name,
                "email"     => $request->email ?? '',
                "phone"     => $request->phone ?? '',
                "address"     => $request->address ?? '',
            ];

            $warehouse = $this->warehouse_service->save($obj);

            if (!$warehouse)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect('warehouses')->with('success', config('enum.saved'));
        } catch (Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('warehouses_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $warehouse = $this->warehouse_service->getById($id);
        return view('warehouses.create', compact('warehouse'));
    }


    public function destroy($id)
    {
        abort_if(Gate::denies('warehouses_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $warehouse = $this->warehouse_service->deleteById($id);

            if ($warehouse)
                return $this->success(
                    config('enum.delete'),
                    []

                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}
