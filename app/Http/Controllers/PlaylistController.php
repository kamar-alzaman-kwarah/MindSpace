<?php

namespace App\Http\Controllers;

use App\Models\playlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends Controller
{
    public function index(User $user)
    {
        if($user->id == Auth::id()){
            $playlist = playlist::where('user_id',$user->id)->get();
        }
        else{
            $playlist = playlist::where('user_id',$user->id)->where('state',0)->get();
        }

        $playlists =[];
        foreach($playlist as $list){
            $cover = $list->playlist_books()->orderBy('id','desc')->take(3)->get();

            $data['list'] = $list;
            $covers_list = [];
            if($cover){
                foreach($cover as $cov){
                    array_push($covers_list,['book_cover' => $cov->book()->select('id','cover')->first()]);
                }
            }
            $data['covers'] = $covers_list;
            array_push($playlists , $data);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$playlists],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>['required','string' , 'min:1' , 'unique:playlists,name,'.Auth::id()],
            'state'=>['nullable']
        ]);
        if($validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $state = 0;
        if($request->has('state')){
            $state = $request->state;
        }

        $playlist = playlist :: create([
            'name'=>$request->name,
            'state'=>$state,
            'user_id'=> Auth::id(),
        ]);

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$playlist],Response::HTTP_CREATED);
    }

    public function show(playlist $playlist)
    {
        $list = null;
        if($playlist->user_id == Auth::id()){
            $list = $playlist->playlist_books()->get();
        }
        else{
            if($playlist->state == 0){
                $list = $playlist->playlist_books()->get();
            }
        }

        $book =[];
        foreach($list as $li){
           array_push($book ,$li->book()->select('id' ,'name' , 'cover')->first());
        }

        $data['playlist'] = $playlist;
        $data['books'] = $book;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request, playlist $playlist)
    {
        $validator = Validator::make($request->all(), [
            'name'=>['required','string' , 'min:1'],
        ]);
        if($validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if(($playlist->name != "private") && ($playlist->name != "favorite")){
            if($request->has('name')){
                $playlist->update([
                    'name'=> $request->name,
                ]);
            }

            if($request->has('state')){
                $playlist->update([
                    'state'=> $request->state,
                ]);
            }
            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$playlist],Response::HTTP_OK);
        }
        else{
            return response()->json(['status'=> 200 ,'message'=>'you can not update it' , 'data'=>$playlist],Response::HTTP_OK);
        }
    }

    public function destroy(playlist $playlist)
    {
        if($playlist->name != 'private' && $playlist->name != 'favorite'){
            $playlist->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$playlist],Response::HTTP_NO_CONTENT);
        }

        return response()->json(['status'=> 204 ,'message'=>'you can not delete it' , 'data'=>$playlist],Response::HTTP_OK);
    }
}
