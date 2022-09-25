<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\wall;
use App\Models\cart;
use App\Models\role;
use App\Models\address;
use App\Models\author;
use App\Models\playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'email'=>['required','string','email'],
            'password'=>['required', 'string' , 'min:9'],
        ]);
        if($Validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        if(!Auth::attempt(['email' => $email, 'password' => $password])){
            return response()->json(['status' => 422 ,"message"=> "Invalid account", 'data'=>null],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user=$request->user();
        $tokenResult=$user->createToken('Personal Access Token');

        $data["user"] = $user;
        $dara['wall'] = wall::where('user_id' , $user->id)->first();
        $data['cart'] = cart::where('user_id' , $user->id)->get()->last();
        $data['private_playlist'] = playlist::where('user_id' , $user->id)->where('name' , 'private')->first();
        $data['favorite_playlist'] = playlist::where('user_id' , $user->id)->where('name' , 'favorite')->first();
        $data["token_type"]='Bearer';
        $data["access_token"]=$tokenResult->accessToken;

        return response()->json(['status'=> 200 ,'message'=>'login successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $info = $request->info;
        $info = json_decode($info, true);

        $codes = 'AD,AE,AF,AG,AI,AL,AM,AO,AQ,AR,AS,AT,AU,AW,AX,AZ,BA,BB,BD,BE,BF,BG,BH,BI,BJ,BL,BM,
                BN,BO,BQ,BR,BS,BT,BV,BW,BY,BZ,CA,CC,CD,CF,CG,CH,CI,CK,CL,CM,CN,CO,CR,CU,CV,CW,CX,CY,
                CZ,DE,DJ,DK,DM,DO,DZ,EC,EE,EG,EH,ER,ES,ET,FI,FJ,FK,FM,FO,FR,GA,GB,GD,GE,GF,GG,GH,GI,
                GL,GM,GN,GP,GQ,GR,GS,GT,GU,GW,GY,HK,HM,HN,HR,HT,HU,ID,IE,IL,IM,IN,IO,IQ,IR,IS,IT,JE,
                JM,JO,JP,KE,KG,KH,KI,KM,KN,KP,KR,KW,KY,KZ,LA,LB,LC,LI,LK,LR,LS,LT,LU,LV,LY,MA,MC,MD,
                ME,MF,MG,MH,MK,ML,MM,MN,MO,MP,MQ,MR,MS,MT,MU,MV,MW,MX,MY,MZ,NA,NC,NE,NF,NG,NI,NL,NO,
                NP,NR,NU,NZ,OM,PA,PE,PF,PG,PH,PK,PL,PM,PN,PR,PS,PT,PW,PY,QA,RE,RO,RS,RU,RW,SA,SB,SC,
                SD,SE,SG,SH,SI,SJ,SK,SL,SM,SN,SO,SR,SS,ST,SV,SX,SY,SZ,TC,TD,TF,TG,TH,TJ,TK,TL,TM,TN,
                TO,TR,TT,TV,TW,TZ,UA,UG,UM,US,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,YT,ZA,ZM,ZW';

        $Validator = Validator::make($info, [
            'first_name'=> ['required', 'string','min:3' ,'max:255'],
            'last_name'=> ['required', 'string','min:3' ,'max:255'],
            'email'=>['required', 'string', 'email', 'unique:users,email'],
            'password'=>['required', 'string', 'min:9'],
            'phone_number'=>['nullable',"phone:$codes"],
            'bio'=>['nullable','string','max:2042'],
            'country'=>['required'],
            'state'=>['required'],
            'city'=>['required'],
            'street'=>['required']
        ]);
        if($Validator->fails()) {
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);        }

        $validator2 = Validator::make($request->all(), [
            'photo'=>['nullable','mimes:png,jpg,jpeg,bmp,gif']
        ]);
        if($validator2->fails()) {
            return response()->json(['status' => 400 ,'message' => $validator2->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        $file_name = null;
        $image_file = null;
        if($request->photo) {
            $file_extension = $request->photo->getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $path = 'photos/users';
            $request->photo->move($path, $file_name);
            $image_file = "public/photos/users/$file_name";
        }

        if(array_key_exists('bio', $info))
            $bio = $info['bio'];
        else
            $bio = null;

        if(array_key_exists('phone_number', $info))
            $phone_number = $info['phone_number'];
        else
            $phone_number = null;

        $address = address :: where('country',$info['country'])
        ->where('state' , $info['state'])
        ->where('city' , $info['city'])
        ->where('street' , $info['street'])
        ->select('id')
        ->get()
        ->first();

        $info['password'] = Hash::make($info['password']);

        $user = User::create([
            'first_name'=> $info['first_name'],
            'last_name'=> $info['last_name'],
            'bio'=> $bio,
            'photo'=> $file_name,
            'email'=> $info['email'],
            'password'=> $info['password'],
            'phone_number'=> $phone_number,
            'address_id'=> $address->id
        ]);

        $wall = wall::create([
            'user_id' => $user->id,
        ]);

        $cart = Cart::create([
            'user_id'=>$user->id,
        ]);

        $private = playlist::create([
            'name' => 'private',
            'state' => 1,
            'user_id' => $user->id,
        ]);

        $favorite = playlist::create([
            'name' => 'favorite',
            'state' => 0,
            'user_id' => $user->id,
        ]);

        $tokenResult = $user->createToken('Personal Access Token');
        $data["user"] = $user;
        $data['wall'] = $wall;
        $data['cart'] = $cart;
        $data['private_playlist'] = $private;
        $data['favorite_playlist'] = $favorite;
        $data["token_type"] = 'Bearer';
        $data["access_token"] = $tokenResult->accessToken;

        if($image_file)
            $data['image_file'] = $image_file;

        return response()->json(['status'=> 201 ,'message'=>'sign up successful' , 'data'=>$data],Response::HTTP_CREATED);
    }

    public function addAdmin(User $user){
        $role = role:: where ('role_name','admin')->get()->first()->id;

        $user->update([
            'role_id' => $role
        ]);

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$user],Response::HTTP_OK);
    }

    public function addShipper(User $user){
        $role = role:: where ('role_name','shipper')->get()->first()->id;

        $user->update([
            'role_id' => $role
        ]);

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$user],Response::HTTP_OK);
    }

    public function addUser(User $user){
        $role = role:: where ('role_name','user')->get()->first()->id;

        $user->update([
            'role_id' => $role
        ]);

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$user],Response::HTTP_OK);
    }

    public function addSuperAdmin(User $user){
        $role = role:: where ('role_name','super_admin')->get()->first()->id;

        $user->update([
            'role_id' => $role
        ]);

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$user],Response::HTTP_OK);
    }

    public function getLink(User $user)
    {
        $link = url("/api/users/{$user->id}");
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$link],Response::HTTP_OK);
    }

    public function show(User $user)
    {
        $address = $user->address()->get();

        $wall = $user->wall()->get();

        $favorite = $user->favorites()->get();
        $author =[];

        foreach($favorite as $fav){
            array_push($author ,$fav->author()->select('id' , author::raw("CONCAT(first_name,' ',last_name) AS name"), 'photo')->get()->first());
        }

        $data['user']=$user;
        $data['address']=$address;
        $data['wall']=$wall;
        $data['favorite']=$author;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request , User $user)
    {
        $Validator=Validator::make($request->all(),[
            'first_name'=> ['nullable', 'string','min:3' ,'max:255'],
            'last_name'=> ['nullable', 'string','min:3' ,'max:255'],
            'password'=>['nullable', 'string', 'min:9'],
            'new_password'=>['nullable', 'string', 'min:9'],
            'phone_number'=>['nullable','min:9','max:9'],
            'bio'=>['nullable','string','max:2042'],
            'address_id'=>['nullable']
        ]);
        if($Validator->fails()){
            return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }

        if($request->has('first_name')){
            $user->update([
                'first_name'=> $request->input('first_name')
            ]);
        }

        if($request->has('last_name')){
            $user->update([
                'last_name'=> $request->input('last_name')
            ]);
        }

        if($request->has('bio')){
            $user->update([
                'bio'=> $request->input('bio')
            ]);
        }

        if($request->has('phone_number')){
            $user->update([
                'phone_number'=> $request->input('phone_number')
            ]);
        }

        if($request->has('password')){
            if(password_verify($request->password ,$user->password)){
                if($request->has('new_password')){
                    $new_password= Hash::make($request->input('new_password'));
                    $user->update([
                        'password'=> $new_password
                    ]);
                }
            }
        }

        $image_file = null;
        if($request->photo){
            $validator2 = Validator::make($request->all(), [
                'photo'=>['mimes:png,jpg,jpeg,bmp,gif']
            ]);
            if($validator2->fails()){
                return response()->json(['status' => 400 ,'message' => $validator2->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }
            $file_extension = $request->photo->getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $path = 'photos/users';
            $request->photo->move($path, $file_name);
            $image_file = "public/photos/users/$file_name";
            $user->update([
                'photo'=> $file_name,
            ]);
        }

        $data['user']=$user;
        if($image_file)
            $data['photo'] = $image_file;

            return response()->json(['status'=> 200 ,'message'=>'update successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $token=$request->user()->token();
        $token->revoke();
        return response()->json(['status'=> 200 ,'message'=>'logout successful' , 'data'=>null],Response::HTTP_OK);
    }

    public function destroy(Request $request , User $user)
    {
        $controller = auth()->user();
        if($user->id == $controller->id && $user->role_id != 3){
            if(password_verify($request->password ,$user->password)){
                $user->update(['activated' => 1]);
                $user->oauth_access_tokens()->delete();
                return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$user],Response::HTTP_NO_CONTENT);
            }
            else{
                return response()->json(['status' => 400 ,'message' => "Failed , enter the password" , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }
        }
        else if($controller->role_id == 3  && $user->role_id != 3){
            $user->update(['activated' => 1]);
            $user->oauth_access_tokens()->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$user],Response::HTTP_NO_CONTENT);
        }
        else if($controller->role->id == 2 && $user->role_id != 3  && $user->role_id != 2 && $user->role_id != 4){
            $user->update(['activated' => 1]);
            $user->oauth_access_tokens()->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$user],Response::HTTP_NO_CONTENT);
        }
        else{
            return response()->json([['status'=>200,"message"=> "Failed , you can not delete it" , 'data'=> null] , Response:: HTTP_OK]);
        }
    }
}
