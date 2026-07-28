<?php

namespace App\Services\Concrete;

use App\Models\JobTaskActivity;
use App\Repository\Repository;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class JobTaskActivityService
{
    protected $model_job_task_activity;
    public function __construct()
    {
        // set the model
        $this->model_job_task_activity = new Repository(new JobTaskActivity);
    }
    //Bead type
    public function getSource($obj)
    {
        $model = $this->model_job_task_activity->getModel()::with('job_task')
            ->where('is_deleted', 0)
            ->where('job_task_id', $obj['job_task_id'])
            ->orderBy('id', 'DESC')
            ->orderBy('created_at', 'DESC');
        $data = DataTables::of($model)
            ->addColumn('date', function ($item) {
                return $item->created_at->format('d M Y') ?? '';
            })
            ->addColumn('job_task', function ($item) {
                return $item->job_task->job_task_no ?? '';
            })
            ->addColumn('image', function ($item) {
                $url = asset($item->picture); // Adjust the path as needed
                $image = '<img src="' . $url . '" width="50" height="50" alt="Image">';
                return $image;
            })
            ->addColumn('action', function ($item) {
                $action_column = '';
                $delete_column    = "<a class='text-danger mr-2' id='deleteJobTaskActivity' href='javascript:void(0)' data-toggle='tooltip'  data-id='" . $item->id . "' data-original-title='delete'><i title='Delete' class='nav-icon mr-2 fa fa-trash'></i>Delete</a>";

                if (Auth::user()->can('job_task_activity_delete'))
                    $action_column .= $delete_column;

                return $action_column;
            })
            ->rawColumns(['date', 'job_task', 'image', 'action'])
            ->make(true);
        return $data;
    }
    // save
    public function save($obj)
    {

        $obj['createdby_id'] = Auth::User()->id;

        $saved_obj = $this->model_job_task_activity->create($obj);

        if (!$saved_obj)
            return false;

        return $saved_obj;
    }
    // delete by id
    public function deleteById($id)
    {
        $job_task_activity = $this->model_job_task_activity->getModel()::find($id);
        $job_task_activity->is_deleted = 1;
        $job_task_activity->deletedby_id = Auth::user()->id;
        $job_task_activity->update();

        if (!$job_task_activity)
            return false;

        return $job_task_activity;
    }
}
