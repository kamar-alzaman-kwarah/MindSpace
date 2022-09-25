<?php

namespace App\Http\Controllers;

use App\Models\role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        $role = role::get();
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$role],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $name = $request->input('role_name');
        $role = role::create([
            'role_name'=> $name
        ]);
        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$role],Response::HTTP_CREATED);
    }

    public function show(role $role)
    {
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$role],Response::HTTP_OK);
    }

    public function update(Request $request, role $role)
    {
        if($request->has('role_name'))
        {
            $name = $request->input('role_name');
            $role->update([
                'role_name'=> $name,
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$role],Response::HTTP_OK);
    }

    public function destroy(role $role)
    {
        $role->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$role],Response::HTTP_NO_CONTENT);
    }
}
