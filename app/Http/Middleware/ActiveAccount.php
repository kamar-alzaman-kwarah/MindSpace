<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\review;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveAccount
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

        if($user->activated == 1){
            return response()->json(['status'=> 403,'message'=>'this account not available'],Response::HTTP_FORBIDDEN);
        }
        else if(array_key_exists('user',$parameter )){
            if($parameter['user']->activated == 1){
                return response()->json(['status'=> 403,'message'=>'this account not available'],Response::HTTP_FORBIDDEN);
            }
            else{
                return $next($request);
            }
        }
        else if($request->has('review') || $request->has('reviewId')){
            if($request->review != null || $request->reviewId != null){
                if($request->review){
                    $review = $request->review;
                }
                else{
                    $review = $request->reviewId;
                }
                $reviews = review::where('id' , $review)->first()->user_id;
                $user = User::where('id' , $reviews)->first();
                if($user->activated == 1){
                    return response()->json(['status'=> 403,'message'=>'this account not available'],Response::HTTP_FORBIDDEN);
                }
                else{
                    return $next($request);
                }
            }
            else{
                return $next($request);
            }
        }
        else{
            return $next($request);
        }
    }
}
