<?php

namespace App\Http\Controllers;

use App\Models\rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(Request $request)
    {
        $Validator=Validator::make($request->all(),[
             'stars_number'=>['required','numeric','min:1','max:5'],
             'book_id'=>['required'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $book_id = $request->input('book_id');
        $rate = Rate::where('user_id', Auth::id())
        ->where('book_id',$book_id)
        ->first();

        if(!$rate)
        {
            $star = Rate::create([
                'stars_number'=>  $request->input('stars_number'),
                'user_id'=>Auth::id(),
                'book_id'=>$book_id

            ]);

            return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$star],Response::HTTP_CREATED);
        }

        else
        {
            RateController::update($request, $rate);
            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$rate],Response::HTTP_OK);
        }
    }

    public function update(Request $request, rate $rate)
    {
        if($request->has('stars_number'))
        {
            $stars_number=$request->input('stars_number');
            $rate->update([
                'stars_number'=> $stars_number,
            ]);
        }
    }

    public function destroy(rate $rate)
    {
        $rate->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$rate],Response::HTTP_NO_CONTENT);
    }
}
