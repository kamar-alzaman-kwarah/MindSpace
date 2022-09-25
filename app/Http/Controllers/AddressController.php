<?php

namespace App\Http\Controllers;

use App\Models\address;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    public function index()
    {
        $address=address::get();

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$address],Response::HTTP_OK);
    }

    public function country()
    {
        $country=address::distinct()
        ->pluck('country');

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$country],Response::HTTP_OK);
    }

    public function state(Request $request)
    {
        $state=address::where('country' , $request->country)
        ->distinct()
        ->pluck('state');

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$state],Response::HTTP_OK);
    }

    public function city(Request $request)
    {
        $city=address::where('country',$request->country )
        ->where('state' , $request->state)
        ->distinct()
        ->pluck('city');

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$city],Response::HTTP_OK);
    }

    public function street(Request $request)
    {
        $street=address::where('country',$request->country )
        ->where('state' , $request->state)
        ->where('city' , $request->city)
        ->distinct()
        ->pluck('street');

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$street],Response::HTTP_OK);
    }

    public function countryShipper(){
        $c = [];
        $users = User::where('role_id',4)->select('address_id')->get();
        foreach($users as $u){
            $countries = Address::where('id',$u->address_id)->pluck('country');
            array_push($c,$countries);
        }
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$c],Response::HTTP_OK);
    }

    public function stateShipper(Request $request)
    {
        $s = [];
        $users = User::where('role_id',4)->select('address_id')->get();
        foreach($users as $u){
            $state=address::where('country' , $request->country)->where('id',$u->address_id)
            ->distinct()
            ->pluck('state');
            array_push($s,$state);
        }
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$s],Response::HTTP_OK);
    }

    public function cityShipper(Request $request)
    {
        $ci = [];
        $users = User::where('role_id',4)->select('address_id')->get();
        foreach($users as $u){
            $city=address::where('country',$request->country )
            ->where('state' , $request->state)
            ->where('id',$u->address_id)
            ->distinct()
            ->pluck('city');
            array_push($ci,$city);
        }
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$ci],Response::HTTP_OK);
    }

    public function streetShipper(Request $request)
    {
        $st = [];
        $users = User::where('role_id',4)->select('address_id')->get();
        foreach($users as $u){
            $street=address::where('country',$request->country )
            ->where('state' , $request->state)
            ->where('city' , $request->city)
            ->where('id',$u->address_id)
            ->distinct()
            ->pluck('street');
            array_push($st,$street);
        }
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$st],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $address = address :: create([
            'country'=> $request->input('country') ,
            'state' => $request->input('state') ,
            'city' => $request->input('city') ,
            'street' => $request->input('street')
        ]);

        return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$address],Response::HTTP_CREATED);
    }

    public function destroy(address $address)
    {
        $address->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$address],Response::HTTP_NO_CONTENT);
    }
}
