<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessSuperAdminControl
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

        if($role != 'super_admin'){
            return response()->json(['status'=> 403,'message'=>'Access Denied'],Response::HTTP_FORBIDDEN);
        }
        else{
            return $next($request);
        }
    }
}
