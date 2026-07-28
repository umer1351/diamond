<?php

namespace App\Http\Controllers;

use App\Services\Concrete\AccountService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class AccountController extends Controller
{
    use JsonResponse;

    protected $account_service;

    public function __construct(
        AccountService  $account_service
    ) {
        $this->account_service = $account_service;
    }

    public function addEditAccount(Request $request)
    {
        abort_if(Gate::denies('accounts_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $id=$request->id;
            $validator = Validator::make($request->all(), [
                'code'          => ['required',Rule::unique('accounts')->where(function ($query) use ($id) {
                    if (isset($id)) {
                        $query->where('id', '!=', $id);
                    }
                    $query->where('is_deleted', 0);
                })
    ],
                'account_type_id' => 'required',
            ]);

            if ($validator->fails()) {
                return  $this->error(
                    $validator->errors()->first()
                );
            }
            $level = 1;
            if (!isset($request->id) || empty($request->id)) {
                if (isset($request->parent_id) && $request->parent_id > 0) {
                    $parent = $this->account_service->getById($request->parent_id);
                    $level = $parent->level + 1;
                }
                $obj = $request->all();
                $obj['level'] = $level;
                $obj['is_cash_account'] = isset($request->is_cash_account) ? 1 : 0;
                $response = $this->account_service->create($obj);
            } else {
                $obj = $request->all();
                $obj['is_cash_account'] = isset($request->is_cash_account) ? 1 : 0;
                $response = $this->account_service->update($obj);
            }
            return  $this->success(
                config('enum.saved'),
                $response
            );
        } catch (Exception $e) {
            $response["Success"]   = false;
            $response["Status"] = 200;
            $response["ErrorMessage"] = config('enum.error');

            return response(
                $response,
                200
            );
        }
    }


    public function index(Request $request)
    {
        abort_if(Gate::denies('accounts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $account_types = $this->account_service->getAllType();
        return view('accounts.index', compact('account_types'))->render();
    }

    public function getMainAccounts(Request $request)
    {
        
        abort_if(Gate::denies('accounts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $arr = $this->account_service->getAllParent(); //Get Main/Parent Accounts

            $response = [
                "Data" => view('accounts/partials/accounts-accordion', compact('arr'))->render(),
                "Success" => true,
                "Display" => false
            ];
            return $response;
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function getChildAccountsByParentId($parent_id)
    {
        abort_if(Gate::denies('accounts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $accounts = $this->account_service->getAllByParentId($parent_id);
            $response = [
                "Data" => view('accounts.partials.child-accounts-accordion', compact("accounts"))->render(),
                "Success" => true,
                "Display" => false
            ];

            return $response;
        } catch (Exception $e) {
            return back()->with('errors', config('enum.error'));
        }
    }

    public function getAccountById(Request $request)
    {
        abort_if(Gate::denies('accounts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            return  $this->success(
                "Success",
                $this->account_service->getById($request->id),
                false
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function status($id)
    {
        abort_if(Gate::denies('accounts_status'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $response = $this->account_service->accountStatus($id);
            return $this->success(
                config('enum.status'),
                []
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }
    public function destroy($id)
    {
        abort_if(Gate::denies('accounts_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $account = $this->account_service->deleteAccountById($id);
            return $this->success(
                config("global.delete"),
                $account,
                true
            );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

}
