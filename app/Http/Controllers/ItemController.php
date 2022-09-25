<?php

namespace App\Http\Controllers;

use App\Models\item;
use App\Models\book;
use App\Models\cart;
use App\Models\res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function addToCart(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'book_id'=>'required',
            'quantity'=>['required','numeric','min:1']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $cart = cart::where('user_id', Auth::id())->get()->last();

        $reservation = res::where('user_id', Auth::id())
        ->where('book_id', $request->book_id)
        ->first();

        if($reservation)
            $number = $reservation->number;
        else
            $number = 0;

        $book_id = $request->input('book_id');
        $quantity = $request->input('quantity');
        $bought_before = item::where('cart_id', $cart->id)->where('book_id',$book_id)->exists();

        $book = book::where('id', $book_id)->first();

        if($book->state && $quantity <= $book->copies_number + $number && !$bought_before)
        {
            $item = item::create([
                'cart_id'=>$cart->id,
                'book_id'=>$book_id,
                'quantity'=>$quantity,
            ]);

            if($quantity >= $number)
            {
                $copies_number = $book->copies_number - ($quantity - $number);
                $book->update([
                    'copies_number'=>$copies_number
                ]);
                if($reservation)
                    $reservation->delete();
            }

            else
            {
                $new_number = $number - $quantity;
                $reservation->update(['number'=>$new_number]);
            }
            return response()->json(['status'=> 201, 'message'=>'successful', 'data'=>$item], Response::HTTP_CREATED);
        }

        else
        {
            if($bought_before)
                return response()->json(['status'=> 200 ,'message'=>'the book is in your cart already' , 'data'=>null],Response::HTTP_OK);
            $total = $book->copies_number + $number;
            if(!$book->state)
                return response()->json(['status'=> 200 ,'message'=>'the book is not available' , 'data'=>null],Response::HTTP_OK);
            else
                return response()->json(['status'=> 200 ,'message'=>"You can't buy more than $total of this book." , 'data'=>null], Response::HTTP_OK);
        }

    }

    public function deleteFromCart(item $item)
    {
        $book = book::where('id',$item->book_id)->first();
        $copies_number = $book->copies_number + $item->quantity;
        $book->update([
            'copies_number'=>$copies_number
        ]);
        $item->delete();
        return response()->json(['status'=> 204, 'message'=>'delete successful', 'data'=>$item], Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request, item $item)
    {
        $Validator = Validator::make($request->all(),[
            'quantity'=>['required','numeric','min:0']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $book = book::where('id', $item->book_id)->first();
        $total = $book->copies_number + $item->quantity;
        $quantity = $request->input('quantity');
        if($quantity <= $total)
        {
            $book->update([
                'copies_number'=>($total - $quantity)
            ]);

            $item->update([
               'quantity'=> $quantity,
            ]);

            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$item],Response::HTTP_OK);
        }

        return response()->json(['status'=> 200 ,'message'=>"You can't buy more than $total of this book." , 'data'=>null],Response::HTTP_OK);
    }
}
