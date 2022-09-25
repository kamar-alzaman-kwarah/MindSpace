<?php

namespace App\Http\Controllers;

use App\Models\donate_admin;
use App\Models\book_donate;
use App\Models\donate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\MindSpace;
use Illuminate\Support\Facades\Auth;

class DonateAdminController extends Controller
{
    public function index(){
        $donate_admin = donate_admin::select('donate_id')->get();

        $donates = donate::whereIn('id', $donate_admin)
        ->get();
        $orders = [];
        foreach($donates as $donate){
            array_push($orders ,[
                'user' => $donate->user()->select('id' , DB::raw("CONCAT(first_name, ' ', last_name) as name") , 'photo')->first(),
                'donate' => $donate->select('id' , 'phone_number')->first(),
            ]);
        }
        $data['accepted'] = $orders;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function indexNot(){
        $donate_admin = donate_admin::select('donate_id')->get();

        $orders = [];
        $donates = donate::whereNotIn('id', $donate_admin)
        ->get();
        foreach($donates as $donate){
            array_push($orders ,[
                'user' => $donate->user()->select('id' , DB::raw("CONCAT(first_name, ' ', last_name) as name") , 'photo')->first(),
                'donate' => $donate->select('id' , 'phone_number')->first(),
            ]);
        }
        $data['on_hold'] = $orders;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function accept(donate $donate)
    {
        $books = book_donate::where('donate_id',$donate->id)
        ->where('acceptance',1)
        ->select('name')
        ->get();

        $data = [
            'body'=>' New notification',
            'dataText'=>"Your book $books has been approved!",
            'url'=>url('/'),
            'thankyou'=>'Keep the good work.'
        ];

        $user = User::where('id', $donate->user_id)->first();
        try{
            $mail = $user->notify(new MindSpace($data));
        }catch(\Throwable $e){
            return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
        }

        $donate_admin = donate_admin::create([
            'user_id'=>Auth::id(),
            'donate_id'=>$donate->id
        ]);

        book_donate::where('donate_id',$donate->id)->where('acceptance',0)->delete();

        return response()->json(['status'=> 201,'message'=>'successful', 'data'=>['accepted order'=>$donate_admin,'books'=>$books]], Response::HTTP_CREATED);
    }

    public function reject(Donate $donate)
    {
        $data = [
            'body'=>' New notification',
            'dataText'=>"Sorry, Your book $donate->name has been rejected",
            'url'=>url('/'),
            'thankyou'=>'Best luck'
        ];


        $user = User::where('id', $donate->user_id)->first();
        try{
            $mail = $user->notify(new MindSpace($data));
        }catch(\Throwable $e){
            return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
        }

        $donate->delete();

        return response()->json(['status'=> 204 ,'message'=>'deleted successful', 'data'=>null], Response::HTTP_NO_CONTENT);
    }
}
