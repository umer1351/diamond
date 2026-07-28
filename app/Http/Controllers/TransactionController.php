<?php

namespace App\Http\Controllers;

use App\Services\Concrete\TransactionService;
use App\Services\Concrete\WarehouseService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    use JsonResponse;
    protected $transaction_service;
    protected $warehouse_service;

    public function __construct(
        TransactionService $transaction_service,
        WarehouseService $warehouse_service
    ) {
        $this->transaction_service = $transaction_service;
        $this->warehouse_service = $warehouse_service;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('transaction_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $warehouses = $this->warehouse_service->getAll();
        return view('transaction.index', compact('warehouses'));
    }

    public function getData(Request $request)
    {
        abort_if(Gate::denies('transaction_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            
            return $this->transaction_service->getSource($request->all());
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('transaction_log_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $transaction = $this->transaction_service->deleteById($id);
            return $this->success(
                config("enum.delete"),
                $transaction,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
}
