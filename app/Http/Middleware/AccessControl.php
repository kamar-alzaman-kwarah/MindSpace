<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $role =role::where('id' ,$user->role_id)->select('role_name')->get()->first()->role_name;

        $parameter =$request->route()->parameters();

        if(array_key_exists('user',$parameter )) {
            $parameter = $parameter['user']->id;
        }
        else{
            $parameter = reset($parameter)->user_id;
        }

        if($role == 'super_admin' || $role == 'admin' || $parameter == $user->id ){
            return $next($request);
        }
        else{
            return response()->json(['status'=> 403,'message'=>'Access Denied'],Response::HTTP_FORBIDDEN);
        }
    }
}
