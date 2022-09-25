<?php

namespace App\Http\Controllers;

use App\Models\wall;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class WallController extends Controller
{
    //show conversation on specific wall
    public function show(wall $wall)
    {
        $conversation = $wall->conversations()->where('parent_id',null)->get();

        $data =[];
        foreach($conversation as $con){
            $count = $con->conversations()->get();
            $count = count($count);
            array_push($data ,['message'=>$con,
                                'user' => $con->user()->select('id', User::raw("CONCAT(first_name, ' ', last_name) as name"),'photo')->first(),
                                'count_of_reply'=>$count]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    //delete all conversation
    public function destroy(wall $wall)
    {
        $wall->conversations()->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>null],Response::HTTP_NO_CONTENT);
    }
}
