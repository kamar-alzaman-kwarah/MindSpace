<?php

namespace App\Http\Controllers;

use App\Models\donate;
use App\Models\User;
use App\Models\donate_admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class DonateController extends Controller
{
    public function index(User $user)
    {
        $donate_admin = donate_admin::select('donate_id')->get();

        $data['accepted'] = donate::whereIn('id', $donate_admin)
        ->where('user_id',Auth::id())
        ->get();

        $data['on_hold'] = donate::whereNotIn('id', $donate_admin)
        ->where('user_id',Auth::id())
        ->get();

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $codes = 'AD,AE,AF,AG,AI,AL,AM,AO,AQ,AR,AS,AT,AU,AW,AX,AZ,BA,BB,BD,BE,BF,BG,BH,BI,BJ,BL,BM,BN,BO,BQ,BR,BS,BT,BV,BW,BY,BZ,CA,CC,CD,CF,CG,CH,CI,CK,CL,CM,CN,CO,CR,CU,CV,CW,CX,CY,CZ,DE,DJ,DK,DM,DO,DZ,EC,EE,EG,EH,ER,ES,ET,FI,FJ,FK,FM,FO,FR,GA,GB,GD,GE,GF,GG,GH,GI,GL,GM,GN,GP,GQ,GR,GS,GT,GU,GW,GY,HK,HM,HN,HR,HT,HU,ID,IE,IL,IM,IN,IO,IQ,IR,IS,IT,JE,JM,JO,JP,KE,KG,KH,KI,KM,KN,KP,KR,KW,KY,KZ,LA,LB,LC,LI,LK,LR,LS,LT,LU,LV,LY,MA,MC,MD,ME,MF,MG,MH,MK,ML,MM,MN,MO,MP,MQ,MR,MS,MT,MU,MV,MW,MX,MY,MZ,NA,NC,NE,NF,NG,NI,NL,NO,NP,NR,NU,NZ,OM,PA,PE,PF,PG,PH,PK,PL,PM,PN,PR,PS,PT,PW,PY,QA,RE,RO,RS,RU,RW,SA,SB,SC,SD,SE,SG,SH,SI,SJ,SK,SL,SM,SN,SO,SR,SS,ST,SV,SX,SY,SZ,TC,TD,TF,TG,TH,TJ,TK,TL,TM,TN,TO,TR,TT,TV,TW,TZ,UA,UG,UM,US,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,YT,ZA,ZM,ZW';
        $Validator=Validator::make($request->all(),[
            'name'=>['required','string','min:1'],
            'phone_number'=>['required',"phone:$codes"],
        ]);
        if($Validator->fails()){
           return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
        }
       $donate=Donate::create([
           'name'=>  $request->input('name'),
           'user_id'=>Auth::id(),
           'phone_number'=>$request->input('phone_number')

       ]);

       return response()->json(['status'=> 201 ,'message'=>'successful' , 'data'=>$donate],Response::HTTP_CREATED);
    }

    public function show(donate $donate)
    {
        $user = $donate->user()->select('id' , user::raw("CONCAT(first_name,' ',last_name) AS name") , 'photo')->first();
        $books = $donate->book_donates()->get();

        $data = ['donate' => $donate , 'user' => $user , 'book' => $books];

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=> $data],Response::HTTP_OK);
    }


    public function update(Request $request, donate $donate)
    {
        $donate_admin = donate_admin::where('donate_id',$donate->id)
        ->first();
        if(!$donate_admin){
            if($request->has('name')){
                $name = $request->input('name');
                $donate->update([
                'name'=> $name,
                ]);
            }

            if($request->has('phone_number')){
                $phone_number = $request->input('phone_number');
                $donate->update([
                'phone_number'=> $phone_number,
                ]);
            }

            $data ['donate'] = $donate;
            return response()->json(['status'=> 200, 'message'=>'successful', 'data'=>$data],Response::HTTP_OK);
        }
        else{
            return response()->json(['status'=> 201, 'message'=>'can not', 'data'=>null],Response::HTTP_OK);
        }

    }

    public function destroy(donate $donate)
    {
        $donate_admin = donate_admin::where('donate_id',$donate->id)
        ->first();
        if(!$donate_admin){
            $donate->delete();
            return response()->json(['status'=> 204 ,'message'=>'delete successful' , 'data'=>$donate],Response::HTTP_NO_CONTENT);
        }
        else{
            return response()->json(['status'=> 201, 'message'=>'can not', 'data'=>null],Response::HTTP_OK);
        }
    }
}
