<?php

namespace App\Http\Controllers;

use App\Models\amateure_admin;
use App\Models\amateure_writer;
use App\Models\User;
use App\Models\book;
use App\Models\book_author;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\MindSpace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AmateureAdminController extends Controller
{
    public function index(){
        $amateure_admin = amateure_admin::select('amateure_id')->get();
        $data =[];
        foreach($amateure_admin as $amateure){
            array_push($data ,['amateure_id' => $amateure->id,
                                "user"=> $amateure->amateure_writer()->first()->user()->select('id' , DB::raw("CONCAT(first_name, ' ', last_name) as name") , 'photo')->first(),
                                "PDF_name" =>$amateure->amateure_writer()->select('name')->first()->name]);
        }
        return response()->json(['status'=> 200, 'message'=>'successful', 'data'=>$data],Response::HTTP_OK);
    }

    public function indexNot()
    {
        $amateure_admin = amateure_admin::select('amateure_id')->get();
        $amateure_writer = amateure_writer::whereNotIn('id', $amateure_admin)->get();
        $data = [];
        foreach($amateure_writer as $amateure){
            array_push($data ,["amateure_id" => $amateure->id,
                                "user"=> $amateure->user()->select('id' , DB::raw("CONCAT(first_name, ' ', last_name) as name") , 'photo')->first(),
                                "PDF_name" =>$amateure->name]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function accept(amateure_writer $amateure_writer)
    {
        $data = [
            'body'=>' New notification',
            'dataText'=>"Your book $amateure_writer->name has been approved!",
            'url'=>url('/'),
            'thankyou'=>'Keep the good work.'
        ];

        $user = User::where('id', $amateure_writer->user_id)->first();

        try{
            $mail = $user->notify(new MindSpace($data));
        }catch(\Throwable $e){
            return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
        }

        $amateure_admin = amateure_admin::create([
            'user_id'=>Auth::id(),
            'amateure_id'=>$amateure_writer->id
        ]);

         $book = Book::create([
            'name'=>$amateure_writer->name,
            'description'=>$amateure_writer->description,
            'cover'=>'pdf.png',
            'page_number'=>0,
            'copies_number'=>0,
            'publishing_year'=>date('y-n-j'),
            'publishing_house'=>'Mind Space',
            'price'=>0,
            'state'=>0,
            'amateur'=>1,
            'PDF'=>$amateure_writer->PDF
        ]);

        return response()->json(['status'=> 201,'message'=>'successful', 'data'=>$amateure_admin], Response::HTTP_CREATED);
    }

    public function reject(amateure_writer $amateure_writer)
    {
        $data = [
            'body'=>' New notification',
            'dataText'=>"Sorry, Your book $amateure_writer->name has been rejected",
            'url'=>url('/'),
            'thankyou'=>'Best luck'
        ];

        $user = User::where('id', $amateure_writer->user_id)->first();
        try{
            $mail = $user->notify(new MindSpace($data));
        }catch(\Throwable $e){
            return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
        }

        $amateure_writer->delete();

        return response()->json(['status'=> 204 ,'message'=>'deleted successful', 'data'=>$amateure_writer], Response::HTTP_NO_CONTENT);
    }
}
