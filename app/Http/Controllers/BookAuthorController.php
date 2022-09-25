<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\book_author;
use App\Models\favorite;
use App\Models\User;
use App\Notifications\MindSpace;
use Illuminate\Http\Request;

class BookAuthorController extends Controller
{
    public function store(Request $request)
    {
        $Validator=Validator::make($request->all(),[
            'book_id'=>['required'],
            'author_id'=>['required']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $au = $request->input('author_id');
        $users = favorite::where('author_id' , $au)->select('user_id')->get();
        $data = [
            'body' => 'New Notification',
            'dataText' => 'we have a new book for your favorite author ,you will like to check it ^-^',
            'url' => url('/'),
            'thankyou' => "all love."
        ];
        foreach($users as $user){
            $user = User::where('id',$user->user_id)->first();

            try{
                $mail = $user->notify(new MindSpace($data));
            }catch(\Throwable $e){
                return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
            }
        }

        $ba=book_author::create([
            'book_id'=>$request->input('book_id'),
            'author_id'=>$request->input('author_id')
        ] );

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$ba],Response::HTTP_OK);
    }

    public function update(Request $request, book_author $book_author)
    {
        if($request->has('book_id'))
        {
            $book_id = $request->input('book_id');
            $book_author->update([
                'book_id'=> $book_id,
            ]);
        }
        if($request->has('author_id'))
        {
            $author_id = $request->input('author_id');
            $book_author->update([
                'author_id'=> $author_id,
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$book_author],Response::HTTP_OK);
    }

    public function destroy(book_author $book_author)
    {
        $book_author->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$book_author],Response::HTTP_NO_CONTENT);
    }
}
