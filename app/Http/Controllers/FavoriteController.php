<?php

namespace App\Http\Controllers;

use App\Models\favorite;
use App\Models\author;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    public function index(User $user)
    {
        $favorite = favorite::where('user_id' , $user->id )->get();
        $author =[];

        foreach($favorite as $fav){
            array_push($author ,$fav->author()->select('id' , user::raw("CONCAT(first_name,' ',last_name) AS name") , 'photo')->get()->first());
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$author],Response::HTTP_OK);
    }

    public function store(Request $request , author $author)
    {
        $favorite = favorite :: where('user_id' , Auth::id())
        ->where('author_id' , $author->id)
        ->get()
        ->first();

        if($favorite == null){
            $author->favorites()->create([
                'user_id' => Auth::id()
            ]);

            return response()->json(['status'=> 201 ,'message'=>'successful'],Response::HTTP_CREATED);
        }

        return FavoriteController::destroy($favorite);
    }

    public function destroy(favorite $favorite)
    {
        $favorite->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$favorite],Response::HTTP_NO_CONTENT);
    }
}
