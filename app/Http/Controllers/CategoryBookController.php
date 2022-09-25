<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\category_book;
use Illuminate\Http\Request;

class CategoryBookController extends Controller
{

    public function store(Request $request)
    {
        $Validator=Validator::make($request->all(),[
            'book_id'=>['required'],
            'category_id'=>['required']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $cb=category_book::create([
            'book_id'=>$request->input('book_id'),
            'category_id'=>$request->input('category_id')
        ]);

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$cb],Response::HTTP_CREATED);
    }

    public function update(Request $request, category_book $category_book)
    {
        if($request->has('book_id'))
        {
            $book_id = $request->input('book_id');
            $category_book->update([
                'book_id'=> $book_id,
            ]);
        }

        if($request->has('category_id'))
        {
            $category_id = $request->input('category_id');
            $category_book->update([
                'category_id'=> $category_id,
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$category_book],Response::HTTP_OK);
    }

    public function destroy(category_book $category_book)
    {
            $category_book->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$category_book],Response::HTTP_NO_CONTENT);
    }
}
