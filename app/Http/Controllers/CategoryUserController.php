<?php

namespace App\Http\Controllers;

use App\Models\category_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CategoryUserController extends Controller
{
    public function index(User $user)
    {
        $category_user =category_user::where('user_id' , Auth::id())->get();
        $category =[];

        foreach($category_user as $cat){
            array_push($category ,$cat->category()->select('id' , 'name' , 'image')->first());
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$category],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories_list'=>['required'],
        ]);
        if($validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $list = $request->categories_list;
        $categories_list = json_decode($list , true);
        $user = User::where('id' , Auth::id())->get()->first();

        foreach($categories_list as $category){
            $found = category_user::where('user_id' , $user->id)->where('category_id' , $category)->exists();
            if(!$found){
                $user->category_users()->create([
                    'category_id' => $category
                ]);
            }
        }

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>null],Response::HTTP_CREATED);
    }

    public function update(Request $request, category_user $category_user)
    {
        $category_user->update([
            'category_id'=> $request->category_id
        ]);

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$category_user],Response::HTTP_OK);
    }

    public function destroy(category_user $category_user)
    {
        $list_of_category = category_user:: where('user_id',Auth::id())->get()->all();
        if(count($list_of_category) > 3){
                $category_user->delete();
                return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$category_user],Response::HTTP_NO_CONTENT);
        }
        else{
            return response()->json(['status'=> 200 ,'message'=>'you can not delete it' , 'data'=>null],Response::HTTP_OK);
        }
    }
}
