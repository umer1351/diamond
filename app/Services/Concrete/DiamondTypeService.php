<?php

namespace App\Services\Concrete;

use App\Models\DiamondType;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DiamondTypeService
{
      protected $model_diamond_type;
      public function __construct()
      {
            // set the model
            $this->model_diamond_type = new Repository(new DiamondType);
      }
      //Bead type
      public function getSource()
      {
            $model = $this->model_diamond_type->getModel()::where('is_deleted', 0);
            $data = DataTables::of($model)
                  ->addColumn('status', function ($item) {
                        if (Auth::user()->can('diamond_type_status')) {
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
                        $edit_column    = "<a class='text-success mr-2' id='editDiamondType' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteDiamondType' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        if (Auth::user()->can('diamond_type_edit'))
                              $action_column .= $edit_column;
                        if (Auth::user()->can('diamond_type_delete'))
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
            return $this->model_diamond_type->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
      }
      // save
      public function save($obj)
      {

            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_diamond_type->create($obj);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // update
      public function update($obj)
      {

            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_diamond_type->update($obj, $obj['id']);
            $saved_obj = $this->model_diamond_type->find($obj['id']);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // get by id
      public function getById($id)
      {
            $diamond_type = $this->model_diamond_type->getModel()::find($id);

            if (!$diamond_type)
                  return false;

            return $diamond_type;
      }
      // status by id
      public function statusById($id)
      {
            $diamond_type = $this->model_diamond_type->getModel()::find($id);
            if ($diamond_type->is_active == 0) {
                  $diamond_type->is_active = 1;
            } else {
                  $diamond_type->is_active = 0;
            }
            $diamond_type->updatedby_id = Auth::user()->id;
            $diamond_type->update();

            if ($diamond_type)
                  return true;

            return false;
      }
      // delete by id
      public function deleteById($id)
      {
            $diamond_type = $this->model_diamond_type->getModel()::find($id);
            $diamond_type->is_deleted = 1;
            $diamond_type->deletedby_id = Auth::user()->id;
            $diamond_type->update();

            if (!$diamond_type)
                  return false;

            return $diamond_type;
      }
}
