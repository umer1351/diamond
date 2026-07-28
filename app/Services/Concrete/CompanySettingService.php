<?php

namespace App\Services\Concrete;

use App\Models\BeadType;
use App\Models\CompanySetting;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CompanySettingService
{
    protected $model_company_setting;
    public function __construct()
    {
        // set the model
        $this->model_company_setting = new Repository(new CompanySetting());
    }
    // get all
    public function getSetting()
    {
        return $this->model_company_setting->getModel()::first();
    }
    // save
    public function save($obj)
    {
        $company_seeting = $this->model_company_setting->getModel()::first();
        if ($company_seeting) {
            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_company_setting->update($obj, $obj['id']);
            $saved_obj = $this->model_company_setting->find($obj['id']);
        } else {

            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_company_setting->create($obj);
        }
        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

}
