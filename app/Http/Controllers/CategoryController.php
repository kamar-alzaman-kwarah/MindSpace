<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\category_book;
use App\Models\book;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category= Category::get();
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$category],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $info=$request->info;
        $info=json_decode($info,true);
        $Validator=Validator::make($info,[
            'name'=>['required' , 'string' , 'min:1'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $image_validator=Validator::make($request->all(),[
            'image'=>'required|mimes:png,jpg,jpeg,bmp,gif'
        ]);
        if($image_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $file_extension=$request->image->getClientOriginalExtension();
        $file_name=time().'.'.$file_extension;
        $path='photos/categories';
        $request->image->move($path,$file_name);
        $photo_file="public/photos/categories/$file_name";

        $category=Category::create([
            'name'=> $info['name'],
            'image'=>$file_name,
        ]);

        $data['category']=$category;
        $data['image']=$photo_file;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function show(category $category)
    {
        $books = [];
        $books_id = category_book::where('category_id', $category->id)->select('book_id')->get();
        foreach($books_id as $item)
            array_push($books, book::where('id', $item->book_id)->select('id', 'name', 'cover')->first());

        $data['books']=$books;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request, category $category)
    {
        $Validator=Validator::make($request->all(),[
            'name'=>['nullable' , 'string' , 'min:1'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('name')){
            $name=$request->input('name');
            $category->update([
               'name'=> $name,
            ]);
        }

        if($request->image){
            $image_validator=Validator::make($request->all(),[
                'image'=>'mimes:png,jpg,jpeg,bmp,gif'
            ]);
            if($image_validator->fails()){
                return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }
            $file_extension=$request->image->getClientOriginalExtension();
            $file_name=time().'.'.$file_extension;
            $path='photos/categories';
            $request->image->move($path,$file_name);

            $category->update([
                'image'=>$file_name,
            ]);
            $photo_file="public/photos/categories/$file_name";
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$category],Response::HTTP_OK);
    }

    public function destroy(category $category)
    {
        $category->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$category],Response::HTTP_NO_CONTENT);
    }
}
