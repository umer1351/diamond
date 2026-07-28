<?php

namespace App\Services\Concrete;

use App\Models\DiamondColor;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DiamondColorService
{
      protected $model_diamond_color;
      public function __construct()
      {
            // set the model
            $this->model_diamond_color = new Repository(new DiamondColor);
      }
      //Bead type
      public function getSource()
      {
            $model = $this->model_diamond_color->getModel()::where('is_deleted', 0);
            $data = DataTables::of($model)
                  ->addColumn('status', function ($item) {
                        if (Auth::user()->can('diamond_color_status')) {
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
                        $edit_column    = "<a class='text-success mr-2' id='editDiamondColor' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                        $delete_column    = "<a class='text-danger mr-2' id='deleteDiamondColor' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                        if (Auth::user()->can('diamond_color_edit'))
                              $action_column .= $edit_column;
                        if (Auth::user()->can('diamond_color_delete'))
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
            return $this->model_diamond_color->getModel()::where('is_deleted', 0)->where('is_active', 1)->get();
      }
      // save
      public function save($obj)
      {

            $obj['createdby_id'] = Auth::User()->id;

            $saved_obj = $this->model_diamond_color->create($obj);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // update
      public function update($obj)
      {

            $obj['updatedby_id'] = Auth::User()->id;
            $this->model_diamond_color->update($obj, $obj['id']);
            $saved_obj = $this->model_diamond_color->find($obj['id']);

            if (!$saved_obj)
                  return false;

            return $saved_obj;
      }

      // get by id
      public function getById($id)
      {
            $diamond_color = $this->model_diamond_color->getModel()::find($id);

            if (!$diamond_color)
                  return false;

            return $diamond_color;
      }
      // status by id
      public function statusById($id)
      {
            $diamond_color = $this->model_diamond_color->getModel()::find($id);
            if ($diamond_color->is_active == 0) {
                  $diamond_color->is_active = 1;
            } else {
                  $diamond_color->is_active = 0;
            }
            $diamond_color->updatedby_id = Auth::user()->id;
            $diamond_color->update();

            if ($diamond_color)
                  return true;

            return false;
      }
      // delete by id
      public function deleteById($id)
      {
            $diamond_color = $this->model_diamond_color->getModel()::find($id);
            $diamond_color->is_deleted = 1;
            $diamond_color->deletedby_id = Auth::user()->id;
            $diamond_color->update();

            if (!$diamond_color)
                  return false;

            return $diamond_color;
      }
}
