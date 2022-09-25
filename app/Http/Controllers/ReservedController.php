<?php

namespace App\Http\Controllers;

use App\Models\reserved;
use App\Models\res;
use App\Models\book;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class ReservedController extends Controller
{
    public function index(User $user)
    {
        $reserved = reserved::where('user_id', $user->id)->get();
        $reserved2 = res::where('user_id', $user->id)->get();

        $all_books = [];
        foreach($reserved as $res)
        {
            array_push($all_books, book::where('id', $res->book_id)->select('id' , 'name' , 'cover')->first(), $res->number);
        }
        $data['not available'] = $all_books;

        $all_books = [];
        foreach($reserved2 as $res)
        {
            array_push($all_books, book::where('id', $res->book_id)->select('id' , 'name' , 'cover')->first(), $res->number);
        }
        $data['available'] = $all_books;

        return response()->json(['status'=>200,'message'=>'successful','data'=>$data], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'book_id'=>'required',
            'number'=>['required','numeric','min:0']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $previous_reservation = reserved::where('book_id', $request->book_id)
        ->where('user_id', Auth::id())
        ->get();
        $book_id = $request->input('book_id');
        $number = $request->input('number');
        $book = book::where('id', $book_id)->first();

        if(!sizeof($previous_reservation) && !$book->state && $number <= $book->copies_number)
        {
            $reserved = reserved::create([
                'user_id'=>Auth::id(),
                'book_id'=>$book_id,
                'number'=>$number
            ]);

            $copies_number = $book->copies_number - $reserved->number;
            $book->update(['copies_number'=>$copies_number]);

            return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$reserved],Response::HTTP_CREATED);
        }

        else
        {
            if(sizeof($previous_reservation))
                return response()->json(['status'=> 200 ,'message'=>'You have previous reservation on this book.' , 'data'=>null],Response::HTTP_OK);
            else if($book->state)
                return response()->json(['status'=> 200 ,'message'=>'The book is already available!' , 'data'=>null],Response::HTTP_OK);
            else
                return response()->json(['status'=> 200 ,'message'=>"You can't reserve more than $book->copies_number of this book." , 'data'=>null],Response::HTTP_OK);
        }
    }

    public function update(Request $request, reserved $reserved)
    {
        $Validator = Validator::make($request->all(),[
            'number'=>['nullable','numeric','min:0']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $book = book::where('id', $reserved->book_id)->first();
        $total = $book->copies_number + $reserved->number;
        $number = $request->input('number');

        if($number && $number <= $total)
        {
            $copies_number = $total - $number;
            $book->update(['copies_number'=>$copies_number]);
            $reserved->update(['number'=> $number]);

            return response()->json(['status'=>200, 'message'=>'successful', 'data'=>$reserved], Response::HTTP_OK);
        }

        return response()->json(['status'=> 200 ,'message'=>"You can't reserve more than $total of this book.", 'data'=>null],Response::HTTP_OK);
    }

    public function destroy(reserved $reserved)
    {
        $book = book::where('id',$reserved->book_id)->first();
        $copies_number = $book->copies_number + $reserved->number;
        $book->update(['copies_number'=>$copies_number]);
        $reserved->delete();

        return response()->json(['status'=> 204 ,'message'=>'deleted successful' , 'data'=>$reserved], Response::HTTP_NO_CONTENT);
    }
}
