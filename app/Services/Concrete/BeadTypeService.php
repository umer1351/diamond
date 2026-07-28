<?php

namespace App\Services\Concrete;

use App\Models\BeadType;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BeadTypeService
{
      protected $model_bead_type;
      public function __construct()
      {
            // set the model
            $this->model_bead_type = new Repository(new BeadType);
      }
      //Bead type
      public function getSource()
      {
            $model = $this->model_bead_type->getModel()::where('is_deleted', 0);
            $data = DataTables::of($model)
                  ->addColumn('status', function ($item) {
                        if (Auth::user()->can('bead_type_status')) {
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
                        $edit_column    = "<a class='text-success mr-2' id='editBeadType' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteBeadType' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        if (Auth::user()->can('bead_type_edit'))
                              $action_column .= $edit_column;
                        if (Auth::user()->can('bead_type_delete'))
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
            return $this->model_bead_type->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
      }
      // save
      public function save($obj)
      {

            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_bead_type->create($obj);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // update
      public function update($obj)
      {

            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_bead_type->update($obj, $obj['id']);
            $saved_obj = $this->model_bead_type->find($obj['id']);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // get by id
      public function getById($id)
      {
            $bead_type = $this->model_bead_type->getModel()::find($id);

            if (!$bead_type)
                  return false;

            return $bead_type;
      }
      // status by id
      public function statusById($id)
      {
            $bead_type = $this->model_bead_type->getModel()::find($id);
            if ($bead_type->is_active == 0) {
                  $bead_type->is_active = 1;
            } else {
                  $bead_type->is_active = 0;
            }
            $bead_type->updatedby_id = Auth::user()->id;
            $bead_type->update();

            if ($bead_type)
                  return true;

            return false;
      }
      // delete by id
      public function deleteById($id)
      {
            $bead_type = $this->model_bead_type->getModel()::find($id);
            $bead_type->is_deleted = 1;
            $bead_type->deletedby_id = Auth::user()->id;
            $bead_type->update();

            if (!$bead_type)
                  return false;

            return $bead_type;
      }
}
