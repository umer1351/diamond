<?php

namespace App\Http\Controllers;

use App\Services\Concrete\PermissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    protected $permission_service;
    public function __construct(
        PermissionService  $permission_service
    ) {
        $this->permission_service = $permission_service;
    }

    public function index()
    {
        abort_if(Gate::denies('permissions_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('permissions.index');
    }


    public function getData(Request $request)
    {
        abort_if(Gate::denies('permissions_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $this->permission_service->getPermissionSource();
    }

    public function create()
    {
        abort_if(Gate::denies('permissions_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('permissions_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'max:50', 'string', 'unique:permissions,name,'.$request->id]
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $obj = [
                "id"    => $request->id,
                "name"  => $request->name
            ];

            $permission = $this->permission_service->save($obj);

            if (!$permission)
                return redirect()->back()->with('error', config('enum.error'));


            return redirect('permissions')->with('success', config('enum.saved'));
        } catch (Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage());
        }
    }

    public function edit($id) {
        abort_if(Gate::denies('permissions_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $permission = $this->permission_service->getById($id);
        return view('permissions.create',compact('permission'));
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}

    public function status($id) {}
}
