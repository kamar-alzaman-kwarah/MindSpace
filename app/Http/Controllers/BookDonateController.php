<?php

namespace App\Http\Controllers;

use App\Models\book_donate;
use App\Models\donate_admin;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class BookDonateController extends Controller
{
    public function index(){
        $books = book_donate::where('acceptance',1)->where('state' , 0)->get();

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'books'=>$books],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $info = $request->info;
        $info = json_decode($info, true);

        $Validator = Validator::make($info,[
            'name'=>['required','string' , 'min:1'],
            'donate_id'=>['required'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $image_validator=Validator::make($request->all(),[
            'photo'=>'required|mimes:png,jpg,jpeg,bmp,gif'
        ]);
        if($image_validator->fails()){
            return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
        $file_extension=$request->photo->getClientOriginalExtension();
        $file_name=time().'.'.$file_extension;
        $path='photos/books';
        $request->photo->move($path,$file_name);
        $photo_file="public/photos/books/$file_name";

        $order = book_donate::create([
            'name'=> $info['name'],
            'donate_id'=>$info['donate_id'],
            'photo'=>$file_name,
        ]);

        $data['order'] = $order;
        $data['photo_file'] = $photo_file;

        return response()->json(['status'=> 201,'message'=>'successful', 'data'=>$data], Response::HTTP_CREATED);
    }

    public function update_admin(Request $request,book_donate $book_donate)
    {
        if($request->has('acceptance')){
            $acceptance=$request->input('acceptance');
            $book_donate->update([
               'acceptance'=> $acceptance,
            ]);
        }
        $data['book_donate'] = $book_donate;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request, book_donate $book_donate)
    {
        $can = donate_admin::where('donate_id' , $book_donate->donate_id)
        ->exists();

        if(!$can){
            $Validator=Validator::make($request->all(),[
                'name'=>['nullabel' , 'string' , 'min:1'],
            ]);
            if($Validator->fails()){
                return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            if($request->has('name')){
                $book_name=$request->input('name');
                $book_donate->update([
                   'name'=> $book_name,
                ]);
            }

            if($request->has('donate_id')){
                $donate_id=$request->input('donate_id');
                $book_donate->update([
                   'donate_id'=> $donate_id,
                ]);
            }

            $file_name = null;
            if($request->photo){
                $image_validator=Validator::make($request->all(),[
                    'photo'=>'mimes:png,jpg,jpeg,bmp,gif'
                ]);
                if($image_validator->fails()){
                    return response()->json(['status' => 400 ,'message' => $image_validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
                }
                $file_extension=$request->photo->getClientOriginalExtension();
                $file_name=time().'.'.$file_extension;
                $path='photos/books';
                $request->photo->move($path,$file_name);

                $book_donate->update([
                    'photo'=>$file_name,
                ]);
                $photo_file="public/photos/books/$file_name";
            }
            $date['book_donate'] = $book_donate;
            if($file_name){
            $data['file_name'] = $photo_file;}

            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
        }
        else{
            return response()->json(['status'=> 204 ,'message'=>'you can not update' , 'data'=>null],Response::HTTP_NO_CONTENT);
        }
    }

    public function destroy(book_donate $book_donate)
    {
        $can = donate_admin::where('donate_id' , $book_donate->donate_id)
        ->exists();

        if(!$can){
            $book_donate->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$book_donate],Response::HTTP_NO_CONTENT);
        }
        else{
            return response()->json(['status'=> 204 ,'message'=>'you can not delete' , 'data'=>null],Response::HTTP_NO_CONTENT);
        }
    }
}
