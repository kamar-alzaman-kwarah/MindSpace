<?php

namespace App\Http\Controllers;

use App\Models\book;
use App\Models\category_user;
use App\Models\category_book;
use App\Models\discount;
use App\Models\amateure_admin;
use App\Models\amateure_writer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\HTTP\Controllers\BookController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware ('auth');
    }

    //best seller
    public function seller()
    {
        $books = book ::select('books.id' ,'books.name' , 'books.cover', 'books.price',
                                //DB::raw('count(books.id) as count'),
                                DB::raw('sum(items.quantity) as quantity'))
                ->join('items' , 'items.book_id' , '=' , 'books.id')
                ->join('bill_items' , 'items.id' , '=' , 'bill_items.item_id')
                ->join('bills' , function ($join){
                        $join->on('bill_items.bill_id' , '=' , 'bills.id')
                             ->where('bills.created_at' , '>' , date( "Y-m-d h:i:s" , strtotime("-1 months")))
                             ->where('bills.state' , 1);
                })
                ->groupBy('books.id' , 'books.name' , 'books.cover' , 'books.price')
                ->orderBy('quantity','desc')
                ->take('15')
                ->get();

        $data = [];
        foreach($books as $book){
            array_push($data , ['book'=>$book ,
                                'new_price'=>BookController::newPrice($book),
                                "rate"=>BookController::showRate($book)]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    //new books
    public function newBook()
    {
        $data = [];
        $books = book::orderBy('publishing_year','desc')
        ->take(15)
        ->select('id' , 'name' , 'cover' ,'price')
        ->get();

        foreach($books as $book){
            array_push($data , ['book'=>$book ,
                                'new_price'=>BookController::newPrice($book),
                                "rate"=>BookController::showRate($book)]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    //high rate
    public function highRate()
    {
        $rates = [];
        $high_rate = [];

        $books = book::get();
        foreach($books as $book)
            array_push($rates, BookController::showRate($book)['rate']);

        $rates = array_unique($rates);
        rsort($rates);
        $rates = array_slice($rates, 0, 20);

        foreach($rates as $rate)
        {
            foreach($books as $book)
            {
                if(BookController::showRate($book)['rate'] == $rate)
                {
                    array_push($high_rate,[ 'book_id'=> $book->id,
                                            'name'=>$book->name,
                                            'cover'=>$book->cover,
                                            'rate'=>$rate,
                                            'price'=>$book->price,
                                            'new_price'=>BookController::newPrice($book)]);
                }
                if(sizeof($high_rate)>=15){
                    break;
                }
            }

            if(sizeof($high_rate)>=15){
                break;
            }
        }

        return response()->json(['status'=> 200 ,'message'=>'successful', 'data'=>$high_rate],Response::HTTP_OK);
    }

    //amateure book
    public function amateure_writer_pdf()
    {
        $data = [];
        $amateure_admin = amateure_admin::select('amateure_id')->get();
        $amateure_writer = amateure_writer::whereIn('id', $amateure_admin)
        ->get();
        foreach($amateure_writer as $aw)
        {
            
            $book = book::where('amateur',1)->where('name',$aw->name)->where('description',$aw->description)->first();
            if($book){
                array_push($data, ['book_id' =>$book->id,
                                    'book_name'=>$aw->name,
                                    'rate'=>BookController::showRate($book),
                                    'cover'=>$book->cover,
                                ]);
        
            }
        }


        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    //categories user
    public function categories()
    {
        $category_user =category_user::where('user_id' , Auth::id())->get();
        $data =[];

        foreach($category_user as $cat){
            $data_book = [];
            $category_book = category_book::whereIn('category_id' , $cat)
            ->select('book_id')
            ->get();
            $books = book::whereIn('id' , $category_book)->inRandomOrder()->limit(15)->get();
            foreach($books as $book){
                array_push($data_book , ['book'=>$book ,
                                         'new_price'=>$new_price = BookController::newPrice($book),
                                         "rate"=>BookController::showRate($book)]);
            }
            array_push($data , ['category_name' => $cat->category()->first()->name,
                                'books' => $data_book]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function advertisement(){
        $data = [];
        array_push($data ,"public/photos/advertisement/donate.jpg" ,
                        "public/photos/advertisement/delivery.jpg",
                        );

        if((date('n') == 4 || date('n') == 8 || date('n') == 12) && date('j') > 1 && date('j') < 10){
            array_push($data ,"public/photos/advertisement/amature.jpg");
        }

        $discount = discount::where('start_date' , '<=' , date('Y-m-d h:i:s'))
        ->where('end_date' , '>=' ,  date('Y-m-d h:i:s'))
        ->exists();
        if($discount){
            array_push($data ,"public/photos/advertisement/discount.jpg");
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }
}
