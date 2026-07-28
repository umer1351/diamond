<?php

namespace App\Services\Concrete;

use App\Repository\Repository;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    protected $model_account;
    protected $model_account_type;

    public function __construct()
    {
        // set the model
        $this->model_account = new Repository(new Account);
        $this->model_account_type = new Repository(new AccountType);
    }

    public function create($obj)
    {
        $obj['createdby_id'] = Auth::user()->id;
        $saved_obj = $this->model_account->create($obj);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }


    public function update($obj)
    {

        $obj['updatedby_id'] = Auth::user()->id;

        $this->model_account->update($obj, $obj['id']);
        $saved_obj = $this->model_account->find($obj['id']);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }


    public function getById($id)
    {
        $account = $this->model_account->find($id);
        return $account;
    }

    public function getAllParent()
    {
        $accounts = $this->model_account->getModel()::where('parent_id', '=', 0)
            ->where('is_deleted', 0)
            ->orWhere('parent_id', '=', null)
            ->orderBy('code', 'ASC')
            ->get();

        return $accounts;
    }
    public function getAllChild()
    {
        $accounts = $this->model_account->getModel()::orderBy('code', 'ASC')
            ->where('parent_id', '!=', 0)
            ->where('is_deleted', 0)
            ->orderBy('code', 'ASC')
            ->get();

        return $accounts;
    }
    public function getAll()
    {
        $accounts = $this->model_account->getModel()::where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('code', 'ASC')
            ->get();

        return $accounts;
    }
    public function getAllActiveChild()
    {
        $accounts = $this->model_account->getModel()::orderBy('code', 'ASC')
            ->where('parent_id', '!=', 0)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('code', 'ASC')
            ->get();

        return $accounts;
    }

    public function getAllType()
    {
        $account_types = $this->model_account_type->getModel()::get();

        return $account_types;
    }

    public function getAllByParentId($parent_id)
    {
        $accounts = $this->model_account->getModel()::where('parent_id', $parent_id)
            ->where('is_deleted', 0)->orderBy('code', 'ASC')->get();

        $response = [];

        if (count($accounts) != 0) {

            foreach ($accounts as $item) {


                $temp_response = [
                    "id"                    => $item->id,
                    "code"                  => $item->code,
                    "name"                  => $item->name,
                    "is_active"             => $item->is_active,
                    "level"                 => $item->level,
                    "account_type_id"       => $item->account_type_id,
                    "is_cash_account"       => $item->is_cash_account,
                    "addurl"                => '/addAccount' . $item->id,
                    "hasChild"              => true,
                    "active"                => true,
                    "editurl"               => '/editAccount' . $item->id

                ];

                $temp_response["childs"] = $this->GetChildAccountsByAccount($item);
                $response[] = $temp_response;
            }
        }

        return $response;
    }

    public function GetChildAccountsByAccount($parent)
    {

        $arr = null;
        $response = [];
        $accounts = $this->model_account->getModel()::where('parent_id', $parent->id)
            ->where('is_deleted', 0)
            ->orderBy('code', 'ASC')
            ->get();


        if (count($accounts) != 0) {

            foreach ($accounts as $item) {

                $temp_response = [
                    "id"                    => $item->id,
                    "code"                  => $item->code,
                    "name"                  => $item->name,
                    "is_active"             => $item->is_active,
                    "level"                 => $item->level,
                    "account_type_id"       => $item->account_type_id,
                    "is_cash_account"       => $item->is_cash_account,
                    "addurl"                => '/addAccount' . $item->id,
                    "hasChild"              => true,
                    "active"                => true,
                    "editurl"               => '/editAccount' . $item->id

                ];


                $temp_response["childs"] = $this->GetChildAccountsByAccount($item);
                $response[] = $temp_response;
            }

            return $response;
        } else {

            return;
        }
    }
    public function accountStatus($id)
    {
        $account = $this->model_account->getModel()::find($id);
        if ($account->is_active == 1) {
            $account->is_active = 0;
        } else {
            $account->is_active = 1;
        }
        $account->update();
        if (!$account)
            return false;

        return $account;
    }
    // delete by id
    public function deleteAccountById($id)
    {
        $account = $this->model_account->getModel()::find($id);
        $account->is_deleted = 1;
        $account->deletedby_id = Auth::user()->id;
        $account->update();

        if (!$account)
            return false;

        return $account;
    }
}
