<?php

namespace App\Http\Controllers;

use App\Services\Concrete\JobTaskActivityService;
use App\Services\Concrete\JobTaskService;
use App\Traits\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class JobTaskActivityController extends Controller
{
    use JsonResponse;
    protected $job_task_activity_service;
    protected $job_task_service;

    public function __construct(
        JobTaskActivityService $job_task_activity_service,
        JobTaskService $job_task_service
    ) {
        $this->job_task_activity_service = $job_task_activity_service;
        $this->job_task_service = $job_task_service;
    }
    public function index($job_task_id)
    {
        
        abort_if(Gate::denies('job_task_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $job_task = $this->job_task_service->getById($job_task_id);
        return view('job_task_activity.index',compact('job_task'));
    }
    public function getData(Request $request)
    {
        abort_if(Gate::denies('job_task_activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $obj=$request->all();
        return $this->job_task_activity_service->getSource($obj);
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('job_task_activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validation = Validator::make(
            $request->all(),
            [
                'job_task_id'   => 'required',
                'category'      => 'required',
                'weight'        => 'required',
                'description'   => 'required'
                
            ],
            $this->validationMessage()
        );

        if ($validation->fails()) {
            $validation_error = "";
            foreach ($validation->errors()->all() as $message) {
                $validation_error .= $message;
            }
            return $this->validationResponse(
                $validation_error
            );
        }


        try {
            DB::beginTransaction();
            $obj = $request->all();
            $filenames = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('activity/pictures'), $filename);
                $filenames = 'activity/pictures/' . $filename;
                $obj['picture'] = $filenames;
            }
            $job_task_activity = $this->job_task_activity_service->save($obj);

            DB::commit();
            if ($job_task_activity)
                return  $this->success(
                    config("enum.saved"),
                    $job_task_activity
                );
        } catch (Exception $e) {
            return $this->error(config('enum.error'));
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('job_task_activity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $job_task_activity = $this->job_task_activity_service->deleteById($id);

            if ($job_task_activity)
                return $this->success(
                    config('enum.delete'),
                    $job_task_activity
                );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
