<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Services\Concrete\CustomerService;
use App\Services\Concrete\JournalEntryService;
use App\Services\Concrete\JournalService;
use App\Services\Concrete\SupplierService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class JournalEntryController extends Controller
{
    use JsonResponse;
    protected $journal_entry_service;
    protected $journal_service;
    protected $account_service;
    protected $supplier_service;
    protected $customer_service;
    
    public function __construct(
        JournalEntryService $journal_entry_service,
        JournalService $journal_service,
        SupplierService $supplier_service,
        AccountService $account_service,
        CustomerService $customer_service
    ) {
        $this->journal_entry_service = $journal_entry_service;
        $this->journal_service = $journal_service;
        $this->account_service = $account_service;
        $this->supplier_service = $supplier_service;
        $this->customer_service = $customer_service;
    }

    public function index()
    {
        abort_if(Gate::denies('journal_entries_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $login_user = Auth::user()->id;
        $journals = $this->journal_service->getAllJournal();
        $suppliers = $this->supplier_service->getAllActiveSupplier();
        $customers = $this->customer_service->getAllActiveCustomer();
        return view('journal_entries.index', compact('journals', 'suppliers','customers'));
    }

    public function getData(Request $request)
    {
        abort_if(Gate::denies('journal_entries_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return $this->journal_entry_service->getJournalEntrySource($request->all());
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('journal_entries_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        $data = json_decode($request->items, true);

        if ($request->id != "") {

            try {
                DB::beginTransaction();

                // Delete Journal Detail entry
                $this->journal_entry_service->deleteDetailByJournalEntry($request->id);

                for ($i = 0; $i < count($data['item']); $i++) {
                    if ($data['item'][$i]['Debit'] == 0) {
                        $amount = $data['item'][$i]['Credit'];
                    }

                    if ($data['item'][$i]['Credit'] == 0) {
                        $amount = $data['item'][$i]['Debit'];
                    }

                    $dataSet = [
                        'journal_entry_id' => $request->id,
                        'explanation' => $data['item'][$i]['Explanation'],
                        'bill_no' => $data['item'][$i]['BillNo'],
                        'check_no' => $data['item'][$i]['CheckNo'],
                        'credit' => str_replace(',', '', $data['item'][$i]['Credit']),
                        'debit' => str_replace(',', '', $data['item'][$i]['Debit']),
                        'doc_date' => $data['item'][$i]['Date'],
                        'account_id' => $data['item'][$i]['acc_head_id'],
                        'createdby_id' => Auth::user()->id,
                        'amount' => str_replace(',', '', $amount),
                        'amount_in_words' => $data['item'][$i]['amount_in_words'],
                        'account_code' => $data['item'][$i]['Code']
                    ];
                    $response = $this->journal_entry_service->saveJournalEntryDetail($dataSet);
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

            return $this->success(
                config("enum.updated"),
                $response
            );
        } else {

            try {
                DB::beginTransaction();
                $date = date("Y-m-d", strtotime(str_replace('/', '-', $data['item'][0]['Date'])));
                $journal = $this->journal_service->getJournalById($request->journal_id);
                $journalEntryNum = [
                    "date" => $date,
                    "journal_id" => $request->journal_id,
                    "prefix" => strtoupper($journal->prefix)
                ];
                $entryNumber = $this->journal_entry_service->generateJournalEntryNum($journalEntryNum);
                $obj_entry = [
                    "journal_id" => $request->journal_id,
                    "supplier_id" => ($request->supplier_id != "") ? $request->supplier_id : null,
                    "date_post" => $date,
                    "reference" => $request->reference,
                    "entryNum" => $entryNumber,
                    'createdby_id' => Auth::user()->id

                ];
                $journalEntry = $this->journal_entry_service->saveJournalEntry($obj_entry);

                for ($i = 0; $i < count($data['item']); $i++) {
                    if ($data['item'][$i]['Debit'] == 0) {
                        $amount = $data['item'][$i]['Credit'];
                    }

                    if ($data['item'][$i]['Credit'] == 0) {
                        $amount = $data['item'][$i]['Debit'];
                    }
                    $dataSet = [
                        'journal_entry_id' => $journalEntry->id,
                        'explanation' => $data['item'][$i]['Explanation'],
                        'bill_no' => $data['item'][$i]['BillNo'],
                        'check_no' => $data['item'][$i]['CheckNo'],
                        'credit' => str_replace(',', '', $data['item'][$i]['Credit']),
                        'debit' => str_replace(',', '', $data['item'][$i]['Debit']),
                        'doc_date' => $data['item'][$i]['Date'],
                        'account_id' => $data['item'][$i]['acc_head_id'],
                        'createdby_id' => Auth::user()->id,
                        'amount' => str_replace(',', '', $amount),
                        'amount_in_words' => $data['item'][$i]['amount_in_words'],
                        'account_code' => $data['item'][$i]['Code'],
                        'currency' => $request->currency??0
                    ];
                    $response = $this->journal_entry_service->saveJournalEntryDetail($dataSet);
                }
                DB::commit();
            } catch (Exception $e) {

                DB::rollback();
                throw $e;
            }
            return $this->success(
                config("global.saved"),
                $response
            );
        }
    }
    
    public function create()
    {
        abort_if(Gate::denies('journal_entries_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $journals = $this->journal_service->getAllJournal();
            $accounts = $this->account_service->getAllActiveChild();
            $suppliers = $this->supplier_service->getAllActiveSupplier();
            return view('journal_entries.create', compact('journals', 'accounts', 'suppliers'));
        } catch (Exception $e) {
            return back()->with('errors', config('enum.error'));
        }
    }

    public function edit($id)
    {
        abort_if(Gate::denies('journal_entries_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $journals = $this->journal_service->getAllJournal();
            $accounts = $this->account_service->getAllActiveChild();
            $journal_entry = $this->journal_entry_service->getJournalEntryById($id);
            $suppliers = $this->supplier_service->getAllActiveSupplier();
            return view('journal_entries.create', compact('journal_entry', 'journals', 'accounts', 'suppliers'));
        } catch (Exception $e) {
            return back()->with('errors', config('enum.error'));
        }
    }

    public function grid_journal_edit($id)
    {
        
        abort_if(Gate::denies('journal_entries_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $entry = $this->journal_entry_service->gridJournalEdit($id);

        $recordsarray = [];
        foreach ($entry as $key => $records) {
            $recordsarray[$key] = $records;
        }
        echo json_encode($recordsarray);

    }

    public function print($id)
    {

        abort_if(Gate::denies('journal_entries_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $journal_entry = $this->journal_entry_service->getJournalEntryById($id);
        $journal_entry_detail = $this->journal_entry_service->getJournalEntryDetailByJournalEntryId($id);

        $pdf = PDF::loadView('/journal_entries/partials.print', compact('journal_entry', 'journal_entry_detail'));
        return $pdf->stream();
    }

    
    public function destroy($id)
    {
        abort_if(Gate::denies('journal_entries_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $journal_entry = $this->journal_entry_service->deleteJournalEntryById($id);

            return $this->success(
                config("enum.delete"),
                [],
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function allJvs(Request $request)
    {
        $journal_entries = $this->journal_entry_service->getJournalEntryByIds($request->filter);
        $view = view('journal_entries/partials.ref', compact('journal_entries'));
        return response($view);
    }

    public function saleOrderAdvance($sale_order_id){
        
        try {
            $advance = $this->journal_entry_service->getSaleOrderAdvanceById($sale_order_id);

            return $this->success(
                config("enum.success"),
                $advance,
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
}
