<?php

namespace App\Http\Controllers;

use App\Services\Concrete\StockService;
use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StockController extends Controller
{
    use JsonResponse;
    protected $stock_service;
    protected $warehouse_service;

    public function __construct(
        StockService $stock_service,
        WarehouseService $warehouse_service
    ) {
        $this->stock_service = $stock_service;
        $this->warehouse_service = $warehouse_service;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $warehouses = $this->warehouse_service->getAll();
        return view('stock.index', compact('warehouses'));
    }

    public function getData(Request $request)
    {
        abort_if(Gate::denies('stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $warehouse_id = $request['warehouse_id'] ?? '';
            $obj = [
                "warehouse_id" => $warehouse_id
            ];
            return $this->stock_service->getSource($obj);
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function getDetail(Request $request)
    {
        $obj = $request->all();
        $stock_detail = $this->stock_service->getStockDetail($obj);
        return $stock_detail;
    }
}
