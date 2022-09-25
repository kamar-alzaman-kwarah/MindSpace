<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\donate_cart;
use App\Models\book_donate;
use App\Models\Item;
use App\Models\Book;
use App\HTTP\Controllers\BookController;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function show(cart $cart)
    {
        $books = [];
        $sum = 0;
        $items = Item::where('cart_id',$cart->id)->get();
        $data['cart'] = $cart;
        foreach($items as $item)
        {
            $book = book::where('id',$item->book_id)->select('id','name' , 'price')->first();
            $price = $book->price;
            $new_price =BookController::newPrice($book);
            if($new_price){
                $price = $new_price;
            }
            array_push($books , ['item_id' => $item->id,
                                'book'=>$book,
                                'price' => $price,
                                'books_number'=> $item->quantity,
                                'total_price' => $item->quantity * $price,
                               ]);
            $sum += $item->quantity * $price;
        }

        $free = donate_cart::where('cart_id',$cart->id)->get();
        foreach($free as $fr){
            array_push($books , ['free_id' => $fr->id,
                                'book'=>book_donate::where('id',$fr->book_donate_id)
                                        ->select('name')->first(),
                                'price' => 0,
                                'books_number' => 1,
                                'total_price' => 0,
                                ]);
        }
        $data['cart_books'] = $books;

        $address = $cart->user()->first()->address()->select('country' , 'state' , 'city' , 'street')->first();
        $phone_number = $cart->user()->first()->phone_number;

        $data['user'] = ['address' => $address , 'phone_number' => $phone_number];

        $data['sum'] = $sum;
        $data['delivery_fee'] = 5000;
        $data['total_price'] = $sum + 5000;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function destroy(cart $cart)
    {
        $cart->items()->delete();
        $cart->donate_carts()->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$cart],Response::HTTP_NO_CONTENT);
    }
}
