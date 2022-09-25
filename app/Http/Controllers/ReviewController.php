<?php

namespace App\Http\Controllers;

use App\Models\review;
use App\Models\book;
use App\Models\like;
use App\Models\user;
use App\Models\spoiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Notifications\MindSpace;

class ReviewController extends Controller
{
    public function index(book $book)
    {
        $data = [];
        $review = review::where('book_id',$book->id)
        ->where('parent_id' , null)
        ->get();

        foreach($review as $rev)
        {
            $user_like = 0;
            $user_dislike = 0;

            $like = like::where('user_id', Auth::id())
            ->where('review_id' , $rev->id)
            ->where('like',1)
            ->exists();
            if($like)
                $user_like = 1;

            $dislike =like::where('user_id',Auth::id())
            ->where('review_id' , $rev->id)
            ->where('like',0)
            ->exists();
            if($dislike)
                $user_dislike = 1;

            $can_see = spoiler::where('user_id', Auth::id())->where('review_id', $rev->id)->first();
            if($can_see)
                $can_see = true;
            else
                $can_see = !$rev->spoiler;

            array_push($data ,['review' => $rev,
                               'count_of_reply' => review::where('parent_id' , $rev->id)->count('id'),
                               'like_dislike_count' => ReviewController::count($rev->id),
                               'user' => $rev->user()->select('id' , user::raw("CONCAT(first_name,' ',last_name) AS name"),'photo' )->get()->first(),
                               'user_like' => $user_like,
                               'user_dislike' => $user_dislike,
                               'user_can_see' => $can_see
                            ]);

        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function show(review $review)
    {
        $user_like = 0;
        $user_dislike = 0;
        $like = like::where('user_id', Auth::id())
        ->where('review_id' , $review->id)
        ->where('like',1)
        ->exists();
        if($like)
            $user_like = 1;

        $dislike =like::where('user_id',Auth::id())
        ->where('review_id' , $review->id)
        ->where('like',0)
        ->exists();
        if($dislike)
            $user_dislike = 1;

        $can_see = spoiler::where('user_id', Auth::id())->where('review_id', $review->id)->first();
        if($can_see)
            $can_see = true;
        else
            $can_see = !$review->spoiler;

        $data['review'] = $review;
        $data['count_of_reply'] = review::where('parent_id' , $review->id)->count('id');
        $data['like_dislike_count'] = ReviewController::count($review->id);
        $data['user'] = $review->user()->select('id' , user::raw("CONCAT(first_name,' ',last_name) AS name"),'photo' )->first();
        $data['use_like'] = $user_like;
        $data['user_dislike'] = $user_dislike;
        $data['user_can_see'] = $can_see;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function showReplies(review $review)
    {
        $reply = $review->reviews()->where('parent_id' , $review->id)->get();
        $data = [];
        foreach($reply as $rep){
            $user_like = 0;
            $user_dislike = 0;

            $like = like::where('user_id', Auth::id())
            ->where('review_id' , $rep->id)
            ->where('like',1)
            ->exists();
            if($like)
                $user_like = 1;

            $dislike =like::where('user_id',Auth::id())
            ->where('review_id' , $rep->id)
            ->where('like',0)
            ->exists();
            if($dislike)
                $user_dislike = 1;

            $can_see = spoiler::where('user_id', Auth::id())->where('review_id', $rep->id)->first();
            if($can_see)
                $can_see = true;
            else
                $can_see = !$rep->spoiler;

            array_push($data ,['reply'=>$rep ,
                               'count_of_reply'=> review::where('parent_id' , $rep->id)->count('id'),
                                'like_dislike_count' => ReviewController::count($rep->id),
                                'user' => $rep->user()->select('id' , User::raw("CONCAT(first_name,' ',last_name) AS name"),'photo' )->get()->first(),
                                'user_like' => $user_like,
                                'user_dislike' => $user_dislike,
                                'user_can_see' => $can_see
                            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful', 'data'=>$data],Response::HTTP_OK);
    }

    public function switch(review $review)
    {
        $spoiler = spoiler::where('user_id', Auth::id())->where('review_id', $review->id)->first();
        if($spoiler)
        {
            $spoiler->delete();
            return response()->json(['status' => 200, 'message' => 'successful', 'data' => ["user_can_see" => false]]);
        }
        else
        {
            spoiler::create(['user_id' => Auth::id(), 'review_id' => $review->id]);
            return response()->json(['status' => 200, 'message' => 'successful', 'data' => ["user_can_see" => true]], Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'book'=>['required', 'integer'],
            'review'=>['nullable', 'integer'],
            'comment'=> ['required','min:1'],
            'spoiler'=>['nullable', 'boolean']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('spoiler'))
            $spoiler = $request->input('spoiler');
        else
            $spoiler = 0;

        $data = review::create([
            'user_id'=>Auth::id(),
            'book_id'=>$request->book,
            'comment'=>$request->input('comment'),
            'parent_id'=>$request->review,
            'spoiler'=>$spoiler
        ]);

        if($request->review)
        {
            $user = review::where('id', $request->review)->first();
            $user_to_notify = User::where('id', $user->user_id)->first();
            $user2 = User::where('id', Auth::id())->first();

            $notification = [
                'body'=>' New notification',
                'dataText'=>"$user2->first_name $user2->last_name replied to your comment: $data->comment",
                'url'=>url('/'),
                'thankyou'=>':)'
            ];
            try{
                $user_to_notify->notify(new MindSpace($notification));
            }catch(\Throwable $e){

            }
        }

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_CREATED);
    }

    public function count($reviewId)
    {
        $like = like::where('review_id', $reviewId)->where('like',1)->get();
        $dislike = like::where('review_id', $reviewId)->where('like',0)->get();
        $data['like_number'] = count($like);
        $data['dislike_number'] = count($dislike);

        return $data;
    }

    public function update(Request $request,review $review)
    {
        $Validator = Validator::make($request->all(),[
            'comment'=> ['nullable', 'min:1'],
            'spoiler'=>['nullable', 'boolean']
        ]);

        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('comment')) {
            $review->update([
                'comment'=>$request->input('comment'),
            ]);
        }

        if($request->has('spoiler')) {
            $review->update([
                'spoiler'=>$request->input('spoiler'),
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$review],Response::HTTP_OK);
    }

    public function destroy(review $review)
    {
        $review->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$review],Response::HTTP_NO_CONTENT);
    }
}
