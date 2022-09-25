<?php

namespace App\Http\Controllers;

use App\Models\bill;
use App\Models\User;
use App\Models\address;
use App\Models\cart;
use App\Models\item;
use App\Models\bill_item;
use App\Models\donate_cart;
use Illuminate\Http\Request;
use App\HTTP\Controllers\BookController;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\MindSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    //all order for one shipper
    public function index(User $user)
    {
        $data = [];
        $handed = bill::where('state', 1)->where('user_id' , $user->id)->get();

        foreach($handed as $bill){
            array_push($data , ['id' => $bill->id,
                                'date'=> $bill->created_at->toDateString(),
                                'user_name' => $bill->cart()->first()->user()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name,
                                'shipper_name' => $user->first_name." ".$user->last_name,
            ]);
        }
        $orders['handed_over'] = $data;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$orders],Response::HTTP_OK);
    }

    public function indexNot(User $user){
        $data = [];
        $hold = bill::where('state', 0)->where('user_id' , $user->id)->get();
        foreach($hold as $bill){
            array_push($data , ['id' => $bill->id,
                                'date'=> $bill->created_at->toDateString(),
                                'user_name' => $bill->cart()->first()->user()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name,
                                'shipper_name' => $user->first_name." ".$user->last_name,
            ]);
        }

        $orders['on_hold'] = $data;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$orders],Response::HTTP_OK);
    }

    //all orders for admins
    public function orders()
    {
        $data = [];
        $handed = bill::where('state', 1)->get();

        foreach($handed as $bill){
            array_push($data , ['id' => $bill->id,
                                'date'=> $bill->created_at->toDateString(),
                                'user_name' => $bill->cart()->first()->user()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name,
                                'shipper_name' => $bill->shipper()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name
            ]);
        }
        $orders['handed_over'] = $data;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$orders],Response::HTTP_OK);
    }

    public function ordersNot(){
        $data = [];
        $hold = bill::where('state', 0)->get();
        foreach($hold as $bill){
            array_push($data , ['id' => $bill->id,
                                'date'=> $bill->created_at->toDateString(),
                                'user_name' => $bill->cart()->first()->user()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name,
                                'shipper_name' => $bill->shipper()->select(user::raw("CONCAT(first_name,' ',last_name) AS name"))->first()->name
            ]);
        }

        $orders['on_hold'] = $data;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$orders],Response::HTTP_OK);
    }

    public function store(Request $request , cart $cart)
    {
        $exist = bill::where('cart_id' , $cart->id)->exists();

        if(!$exist){
            $shipper = null;

            $users = User::where('role_id',4)->get();

            if($request->has('country')){
                $Validator=Validator::make($request->all(),[
                    'country'=>['required'],
                    'state'=>['required'],
                    'city'=>['required'],
                    'street' => ['required']
                ]);
                if($Validator->fails()){
                   return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
                }

                $address = address :: where('country',$request->input('country'))
                ->where('state' , $request->input('state'))
                ->where('city' , $request->input('city'))
                ->where('street' , $request->input('street'))
                ->first();
            }
            else{
                $address = $cart->user()->first()->address()->first();
            }

            if($request->has('phone_number')){
                $codes = 'AD,AE,AF,AG,AI,AL,AM,AO,AQ,AR,AS,AT,AU,AW,AX,AZ,BA,BB,BD,BE,BF,BG,BH,BI,BJ,BL,BM,BN,BO,BQ,BR,BS,BT,BV,BW,BY,BZ,CA,CC,CD,CF,CG,CH,CI,CK,CL,CM,CN,CO,CR,CU,CV,CW,CX,CY,CZ,DE,DJ,DK,DM,DO,DZ,EC,EE,EG,EH,ER,ES,ET,FI,FJ,FK,FM,FO,FR,GA,GB,GD,GE,GF,GG,GH,GI,GL,GM,GN,GP,GQ,GR,GS,GT,GU,GW,GY,HK,HM,HN,HR,HT,HU,ID,IE,IL,IM,IN,IO,IQ,IR,IS,IT,JE,JM,JO,JP,KE,KG,KH,KI,KM,KN,KP,KR,KW,KY,KZ,LA,LB,LC,LI,LK,LR,LS,LT,LU,LV,LY,MA,MC,MD,ME,MF,MG,MH,MK,ML,MM,MN,MO,MP,MQ,MR,MS,MT,MU,MV,MW,MX,MY,MZ,NA,NC,NE,NF,NG,NI,NL,NO,NP,NR,NU,NZ,OM,PA,PE,PF,PG,PH,PK,PL,PM,PN,PR,PS,PT,PW,PY,QA,RE,RO,RS,RU,RW,SA,SB,SC,SD,SE,SG,SH,SI,SJ,SK,SL,SM,SN,SO,SR,SS,ST,SV,SX,SY,SZ,TC,TD,TF,TG,TH,TJ,TK,TL,TM,TN,TO,TR,TT,TV,TW,TZ,UA,UG,UM,US,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,YT,ZA,ZM,ZW';
                $Validator=Validator::make($request->all(),[
                    'phone_number'=>['nullable' , "phone:$codes"],
                ]);
                if($Validator->fails()){
                   return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
                }

                $phone_number = $request->input('phone_number');
            }
            else{
                $phone_number = $cart->user()->first()->phone_number;
            }

            foreach($users as $user){
                $user_address = address::where('id',$user->address_id)
                ->where('city' , $address->city)
                ->exists();
                if($user_address){
                    $shipper = $user;
                break;
                }
            }

            if($shipper){
                $data = [
                    'body' => 'New Notification',
                    'dataText' => 'You have a new order to deliver ,Please check your order box for more details',
                    'url' => url('/'),
                    'thankyou' => "let's work hard."
                ];

                try{
                    $mail = $shipper->notify(new MindSpace($data));
                }catch(\Throwable $e){
                    return response()->json(['status'=> 200,'message'=>'please try again', 'data'=> null], Response::HTTP_OK);
                }

                $bill = bill::create([
                    'user_id' => $shipper->id,
                    'cart_id' => $cart->id,
                    'address_id' => $address->id,
                    'phone_number' => $phone_number
                ]);

                //bill items
                $items = item::where('cart_id' , $bill->cart_id)->get();
                foreach($items as $item){
                    $price = BookController::newPrice($item->book()->first());
                    if(!$price)
                        $price = $item->book()->first()->price;

                    bill_item::create([
                        'bill_id' => $bill->id,
                        'item_id' => $item->id,
                        'price' => $price
                    ]);
                }

                //free book
                $free_books = donate_cart::where('cart_id' , $bill->cart_id)->get();
                foreach($free_books as $book){
                    bill_item::create([
                        'bill_id' => $bill->id,
                        'donate_cart_id' => $book->id,
                        'price' => 0
                    ]);
                }

                //new cart
                $cart = cart::create([
                    'user_id'=>Auth::id(),
                ]);

                //return message
                $info = [
                    'message' => 'you will receive your order within a week, thank for dealing with us',
                    'contact Us' => "the shipper who will deliver your order is $shipper->first_name"." $shipper->last_name please contact him",
                    'phone number' => $shipper->phone_number,
                    'bill_information' => $bill,
                    'new_cart' => $cart
                ];

                return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$info],Response::HTTP_CREATED);
            }
            else{
                return response()->json([
                    'status'=> 200 ,
                    'message'=>'No distributor available in this area ,Please choose another address' ,
                    'data'=>null],Response::HTTP_OK);
            }
        }
        return response()->json(['status'=> 200 ,'message'=>'this order has been already sent' , 'data'=>null],Response::HTTP_OK);
    }

    public function show(bill $bill)
    {
        $data['bill_header'] = $bill->created_at->toDateString();

        //shipper
        $shipper = $bill->shipper()->select('first_name' , 'last_name' , 'phone_number')->first();
        $data['shipper_name'] = $shipper->first_name . " " . $shipper->last_name;
        $data['shiper_phone_number'] = $shipper->phone_number;

        //owner
        $data['owner_name'] = $bill->cart()->first()->user()->select(User::raw("CONCAT(first_name, ' ', last_name) as name"))->first()->name;
        $data['owner_phone_number'] = $bill->phone_number;
        $data['owner_address'] = $bill->address()->first();

        //content
        $items = [];
        $contents = $bill->bill_items()->get();
        foreach($contents as $content){
            if($content->price == 0){
                $item = $content->donate_cart()->first()->book_donate()->select('name')->first();
                $quantities = 1;
                $price = 0;
                $total_price = 0;
            }
            else{
                $quantities = $content->item()->first()->quantity;
                $item = $content->item()->first()->book()->select('name' , 'price')->first();
                $price = $item->price;
                $total_price = $quantities * $item->price;
            }

            array_push($items , ['book' => $item->name
                                ,'price' => $price
                                ,'quantities' => $quantities
                                ,'total_price' => $total_price]);
        }
        $data['bill_content'] = $items;

        $sum = $bill->bill_items()->sum('price');
        $data['sum'] = $sum;
        $data['delivery_fee'] = 5000;
        $data['total_price'] = $sum + 5000;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    //for shipper when deliver the order
    public function update(Request $request, bill $bill)
    {
        if($bill->state == 0){
            $bill->update([
                'state'=>1
            ]);
        }

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$bill],Response::HTTP_OK);
    }

    //for shipper When the user refuses to receive the order
    public function destroy(bill $bill)
    {
        $books = $bill->bill_items()->get();
        foreach($books as $book){
            if($book->price != 0){
                $copies_number =$book->item()->first()->book()->first()->copies_number + $book->item()->first()->quantity;
                $book->item()->first()->book()->update([
                    'copies_number' => $copies_number,
                ]);
            }
            else{
                $book->donate_cart()->first()->book_donate()->update([
                    'state' => 0
                ]);
            }
        }

        $bill->delete();
        return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$bill],Response::HTTP_NO_CONTENT);
    }
}
