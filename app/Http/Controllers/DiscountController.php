<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function index()
    {
        $discount = discount::where('start_date', '<=', date('Y-n-j'))
        ->where('end_date', '>=', date('Y-n-j'))
        ->get();
        $counter = 0;
        $data = null;
        foreach($discount as $dis)
        {
            $data[$counter] = book::where('id', $dis->book_id)->first();
            $data[$counter]['newPrice'] = $data[$counter]->price-($data[$counter]->price*$dis->ratio/100);
            $counter+=1;
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'book_id'=>'required',
            'ratio'=>['required', 'numeric', 'min:1', 'max:99'],
            'start_date'=>['required', 'date'],
            'end_date'=>['required', 'date']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $discount = discount::create([
            'user_id'=>Auth::id(),
            'book_id'=>$request->input('book_id'),
            'ratio'=>$request->input('ratio'),
            'start_date'=>$request->input('start_date'),
            'end_date'=>$request->input('end_date')
        ]);

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$discount],Response::HTTP_CREATED);
    }

    public function update(Request $request, discount $discount)
    {
        $Validator = Validator::make($request->all(),[
            'book_id'=>'nullable',
            'ratio'=>['nullable', 'numeric', 'min:1', 'max:100'],
            'start_date'=>['nullable', 'date'],
            'end_date'=>['nullable', 'date']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('book_id'))
        {
            $discount->update([
               'book_id'=> $request->input('book_id'),
            ]);
        }

        if($request->has('ratio'))
        {
            $discount->update([
               'ratio'=> $request->input('ratio'),
            ]);
        }

        if($request->has('start_date'))
        {
            $discount->update([
               'start_date'=> $request->input('start_date'),
            ]);
        }

        if($request->has('end_date'))
        {
            $discount->update([
               'end_date'=> $request->input('end_date'),
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$discount],Response::HTTP_OK);
    }

    public function destroy(discount $discount)
    {
        $discount->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$discount],Response::HTTP_NO_CONTENT);
    }

}
