<?php

namespace App\Http\Controllers;

use App\Models\donate_cart;
use App\Models\Cart;
use App\Models\Bill;
use App\Models\book_donate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class DonateCartController extends Controller
{
    public function store(Request $request)
    {
        $number = [];
        $sold_carts = [];
        $carts = Cart::where('user_id',Auth::id())
        ->where('created_at','>=' , date("Y-m-d h:i:s" , strtotime("-1 months")))
        ->select('id')
        ->get();

        $current_cart = cart::where('user_id' , Auth::id())->get()->last();

        $free = donate_cart::where('cart_id',$current_cart->id)
        ->select(donate_cart::raw('count(book_donate_id) as count'))
        ->first();

       if($free->count >= 2){
            return response()->json(['status'=> 200 ,'message'=>'you cant take another book' , 'data'=>null],Response::HTTP_OK);
       }

        foreach($carts as $cart){
            $bill = Bill::whereIn('cart_id',$cart)->first();
            if($bill)
                array_push($sold_carts , $bill);
        }

        if(sizeof($sold_carts)>0){
            foreach($sold_carts as $sold_cart){
                $donates = donate_cart::where('cart_id',$sold_cart->cart_id)
                ->select('book_donate_id')
                ->where('created_at','>=' , date("Y-m-d h:i:s" , strtotime("-1 months")))
                ->get();

                foreach($donates as $donate){
                    array_push($number , $donate);
                }
            }

            if(count($number) >= 2){
                return response()->json(['status'=> 200 ,'message'=>'you cant take another book' , 'data'=>null],Response::HTTP_OK);
            }
        }
        $Validator=Validator::make($request->all(),[
            'book_donate_id'=>['required']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $book_donate = book_donate::where('id',$request->book_donate_id)->where('acceptance',1)->where('state' , 0)->first();
        if($book_donate){
            $book = $request->input('book_donate_id');
        }
        else{
            return response()->json(['status'=> 200 ,'message'=>'this book is not available' , 'data'=>null],Response::HTTP_OK);
        }
        $book_donate = book_donate::where('id' , $book_donate->id)->update(['state' => 1]);

        $dc=donate_cart::create([
            'cart_id'=>cart::where('user_id', Auth::id())->get()->last()->id,
            'book_donate_id'=>$book
        ]);
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$dc],Response::HTTP_OK);
    }

    public function destroy(donate_cart $donate_cart)
    {
        $books = $donate_cart->book_donate()->get();
        foreach($books as $book){
            book_donate::where('id' , $book->id)->update(['state' => 0]);
        }
        $donate_cart->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$donate_cart],Response::HTTP_NO_CONTENT);
    }
}
