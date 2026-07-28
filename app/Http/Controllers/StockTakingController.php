<?php

namespace App\Http\Controllers;

use App\ExcelExports\ReportExport;
use App\Services\Concrete\StockService;
use App\Services\Concrete\StockTakingService;
use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StockTakingController extends Controller
{
    use JsonResponse;
    protected $stock_taking_service;
    protected $stock_service;
    protected $warehouse_service;
    public function __construct(
        StockTakingService $stock_taking_service,
        StockService $stock_service,
        WarehouseService $warehouse_service
    ) {
        $this->stock_taking_service = $stock_taking_service;
        $this->stock_service = $stock_service;
        $this->warehouse_service = $warehouse_service;
    }
    public function index()
    {
        abort_if(Gate::denies('stock_taking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $warehouses = $this->warehouse_service->getAll();
        return view('stock_taking.index', compact('warehouses'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('stock_taking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $obj = $request->all();
        return $this->stock_taking_service->getSource($obj);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('stock_taking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $warehouses =  $this->warehouse_service->getAll();
        return view('stock_taking.create', compact('warehouses'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('stock_taking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $stock_detail = $this->stock_taking_service->store($request->all());

        return $this->success(
            config('enum.saved'),
            []
        );
    }


    public function view($id)
    {
        abort_if(Gate::denies('stock_taking_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $stock_taking = $this->stock_taking_service->getById($id);
            $stock_taking_detail = $this->stock_taking_service->stockTakingDetailByStockId($id);
            return view('stock_taking.view', compact('stock_taking', 'stock_taking_detail'));
            // return $pdf->stream('Stock-Taking' . $stock_taking->stock_date . '.pdf');
        } catch (Exception $e) {
            return $this->error(config('enum.error'),);
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('stock_taking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->stock_taking_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                $response,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.noDelete'),);
        }
    }

    public function print(Request $request)
    {
        abort_if(Gate::denies('stock_taking_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $obj = $request->all();
        $stock_detail['stock_detail'] = $this->stock_service->getStockDetail($obj);
        $warehouse = $this->warehouse_service->getById($request->warehouse_id);
        $date = $request->date;
        $parms = (object)$stock_detail;
        $parms->date = $date;
        $parms->warehouse = $warehouse;
        $parms->report_name = "stock_taking_report";
        if ($request->has('exportExcel')) {
            return Excel::download(new ReportExport($parms), 'Stock-Taking.xls');
        }
        $pdf = PDF::loadView('/stock_taking.print', compact('parms'));
        return $pdf->stream('Stock-Taking' . $request->date . '.pdf');
    }
}
