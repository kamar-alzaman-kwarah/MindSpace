<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessUserControl
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
        $parameter =$request->route()->parameters();

        if(array_key_exists('user',$parameter )) {
            $parameter = $parameter['user']->id;
        }
        else if(array_key_exists('item',$parameter )){
            $parameter = $parameter['item']->cart()->first()->user()->first()->id;
        }
        else if(array_key_exists('donate_cart',$parameter )){
            $parameter = $parameter['donate_cart']->cart()->first()->user_id;
        }
        else{
            $parameter = reset($parameter)->user_id;
        }

        if($parameter == $user->id ){
            return $next($request);
        }
        else{
            return response()->json(['status'=> 403,'message'=>'Access Denied'],Response::HTTP_FORBIDDEN);
        }
    }
}
