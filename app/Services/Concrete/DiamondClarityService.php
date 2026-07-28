<?php

namespace App\Services\Concrete;

use App\Models\DiamondClarity;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DiamondClarityService
{
      protected $model_diamond_clarity;
      public function __construct()
      {
            // set the model
            $this->model_diamond_clarity = new Repository(new DiamondClarity);
      }
      //Bead type
      public function getSource()
      {
            $model = $this->model_diamond_clarity->getModel()::where('is_deleted', 0);
            $data = DataTables::of($model)
                  ->addColumn('status', function ($item) {
                        if (Auth::user()->can('diamond_clarity_status')) {
                              if ($item->is_active == 1) {
                                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                              } else {
                                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                              }
                              return $status;
                        } else {
                              return 'N/A';
                        }
                  })
                  ->addColumn('action', function ($item) {
                        $action_column = '';
                        $edit_column    = "<a class='text-success mr-2' id='editDiamondClarity' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteDiamondClarity' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        if (Auth::user()->can('diamond_clarity_edit'))
                              $action_column .= $edit_column;
                        if (Auth::user()->can('diamond_clarity_delete'))
                              $action_column .= $delete_column;

                        return $action_column;
                  })
                  ->rawColumns(['status', 'action'])
                  ->make(true);
            return $data;
      }
      // get all
      public function getAllActive()
      {
            return $this->model_diamond_clarity->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
      }
      // save
      public function save($obj)
      {

            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_diamond_clarity->create($obj);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // update
      public function update($obj)
      {

            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_diamond_clarity->update($obj, $obj['id']);
            $saved_obj = $this->model_diamond_clarity->find($obj['id']);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // get by id
      public function getById($id)
      {
            $diamond_clarity = $this->model_diamond_clarity->getModel()::find($id);

            if (!$diamond_clarity)
                  return false;

            return $diamond_clarity;
      }
      // status by id
      public function statusById($id)
      {
            $diamond_clarity = $this->model_diamond_clarity->getModel()::find($id);
            if ($diamond_clarity->is_active == 0) {
                  $diamond_clarity->is_active = 1;
            } else {
                  $diamond_clarity->is_active = 0;
            }
            $diamond_clarity->updatedby_id = Auth::user()->id;
            $diamond_clarity->update();

            if ($diamond_clarity)
                  return true;

            return false;
      }
      // delete by id
      public function deleteById($id)
      {
            $diamond_clarity = $this->model_diamond_clarity->getModel()::find($id);
            $diamond_clarity->is_deleted = 1;
            $diamond_clarity->deletedby_id = Auth::user()->id;
            $diamond_clarity->update();

            if (!$diamond_clarity)
                  return false;

            return $diamond_clarity;
      }
}
