<?php

namespace App\Services\Concrete;

use App\Models\Journal;
use App\Repository\Repository;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class JournalService
{
    protected $model_journal;
    public function __construct()
    {
        // set the model
        $this->model_journal = new Repository(new Journal);
    }
    //Journal
    public function getJournalSource()
    {
        $model = Journal::where('is_deleted', 0);
        $data = DataTables::of($model)
            ->addColumn('status', function ($item) {
                if (Auth::user()->can('journals_status')){
                if ($item->is_active == 1) {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" checked="checked" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                } else {
                    $status = '<label class="switch pr-5 switch-primary mr-3"><input type="checkbox" id="status" data-id="' . $item->id . '"><span class="slider"></span></label>';
                }
                return $status;
            }else{
                return 'N/A';
            }

            })
            ->addColumn('action', function ($item) {
                $action_column = '';
                $edit_column    = "<a class='text-success mr-2' id='editJournal' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='Edit'><i title='Add' class='nav-icon mr-2 fa fa-edit'></i>Edit</a>";
                $delete_column    = "<a class='text-danger mr-2' id='deleteJournal' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";
                if (Auth::user()->can('journals_edit'))
                    $action_column .= $edit_column;
                if (Auth::user()->can('journals_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
        return $data;
    }
    // get all journal
    public function getAllJournal()
    {
        return Journal::where('is_deleted', 0)->get();
    }
    // save Journal
    public function saveJournal($obj)
    {

        $obj['createdby_id'] = Auth::User()->id;

        $saved_obj = $this->model_journal->create($obj);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // update Journal
    public function updateJournal($obj)
    {

        $obj['updatedby_id'] = Auth::User()->id;
        $this->model_journal->update($obj, $obj['id']);
        $saved_obj = $this->model_journal->find($obj['id']);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }

    // get by id
    public function getJournalById($id)
    {
        $journal = $this->model_journal->getModel()::find($id);

        if (!$journal)
            return false;

        return $journal;
    }
    // status by id
    public function statusById($id)
    {
        $journal = $this->model_journal->getModel()::find($id);
        if ($journal->is_active == 0) {
            $journal->is_active = 1;
        } else {
            $journal->is_active = 0;
        }
        $journal->updatedby_id = Auth::user()->id;
        $journal->update();

        if ($journal)
            return true;

        return false;
    }
    // delete by id
    public function deleteJournalById($id)
    {
        $journal = $this->model_journal->getModel()::find($id);
        $journal->is_deleted = 1;
        $journal->deletedby_id = Auth::user()->id;
        $journal->update();

        if (!$journal)
            return false;

        return $journal;
    }
}
