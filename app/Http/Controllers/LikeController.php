<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Models\review;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\MindSpace;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'reviewId'=>['required', 'integer'],
            'like'=>['required', 'boolean']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $userId = Auth::id();
        $reviewId = $request->reviewId;
        $currentLike = $request->like;
        $like = Like::where('user_id', $userId)-> where('review_id' , $reviewId)->first();
        if($like)
        {
            if($like->like == $currentLike)
            {
                $like->delete();
                return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$like],Response::HTTP_NO_CONTENT);
            }
            else {
                $like->update([
                    'like'=>$currentLike
                ]);

                LikeController::notify_user($currentLike, $reviewId, $userId);
                return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$like],Response::HTTP_OK);
            }
        }
        else {
             $like = Like::create([
                'like'=>$currentLike,
                'user_id'=>$userId,
                'review_id'=>$reviewId
            ]);
            LikeController::notify_user($currentLike, $reviewId, $userId);

            return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$like],Response::HTTP_CREATED);
        }
    }

    public function notify_user($currentLike, $reviewId, $userId)
    {
        if($currentLike == 1) $value = 'liked'; else $value = 'disliked';
        $user = review::where('id', $reviewId)->first();
        $user_to_notify = User::where('id', $user->user_id)->first();
        $user2 = User::where('id', $userId)->first();

        $data = [
            'body'=>' New notification',
            'dataText'=>"$user2->first_name $user2->last_name $value your comment: $user->comment",
            'url'=>url('/'),
            'thankyou'=>':)'
        ];

        try{
            $user_to_notify->notify(new MindSpace($data));
        }catch(\Throwable $e){

        }
    }
}
