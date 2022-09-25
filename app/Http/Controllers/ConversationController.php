<?php

namespace App\Http\Controllers;

use App\Models\conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(),[
            'message'=> ['required','min:1'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $data = conversation::create([
            'user_id' => Auth::id(),
            'parent_id' => $request->parent,
            'wall_id' => $request->wall,
            'message' => $request->message,
        ]);

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_CREATED);
    }

    public function show(conversation $conversation)
    {
        $data['message'] = $conversation;
        $data['user'] = $conversation->user()->select('id' , User::raw("CONCAT(first_name, ' ', last_name) as name"), 'photo')->first();

        $reply = $conversation->conversations()->get();
        $reply_data = [];
        foreach($reply as $rep){
            array_push($reply_data ,['message'=>$rep
                                    ,'user'=>$rep->user()->select('id' , User::raw("CONCAT(first_name, ' ', last_name) as name"), 'photo')->first()]);
        }
        $data['count_of_replies'] = count($reply);
        $data['reply'] = $reply_data;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request, conversation $conversation)
    {
        $Validator = Validator::make($request->all(),[
            'message'=> ['nullable','min:1'],
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('message')){
            $conversation->update([
                'message'=>$request->message
            ]);

            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$conversation],Response::HTTP_OK);
        }
    }

    public function destroy(conversation $conversation)
    {
        $conversation->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$conversation],Response::HTTP_NO_CONTENT);
    }
}
