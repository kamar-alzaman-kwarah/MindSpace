<?php

namespace App\Http\Controllers;

use App\Models\amateure_admin;
use App\Models\amateure_writer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AmateureWriterController extends Controller
{
    public function index(User $user)
    {
        $amateure_admin = amateure_admin::select('amateure_id')->get();

        $data['accepted'] = amateure_writer::whereIn('id', $amateure_admin)
        ->where('user_id', Auth::id())
        ->get();

        $data['on_hold'] = amateure_writer::whereNotIn('id', $amateure_admin)
        ->where('user_id', Auth::id())
        ->get();

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function check()
    {
        if((date('n') == 4 || date('n') == 8 || date('n') == 12) && date('j') > 1 && date('j') < 15){
            return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>"open"],Response::HTTP_OK);
        }
        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>"close"],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        if((date('n') == 4 || date('n') == 8 || date('n') == 12) && date('j') > 1 && date('j') < 15)
            $isOpen = true;
        else
            $isOpen = false;
        if($isOpen)
        {
            $codes = 'AD,AE,AF,AG,AI,AL,AM,AO,AQ,AR,AS,AT,AU,AW,AX,AZ,BA,BB,BD,BE,BF,BG,BH,BI,BJ,BL,BM,BN,BO,BQ,BR,BS,BT,BV,BW,BY,BZ,CA,CC,CD,CF,CG,CH,CI,CK,CL,CM,CN,CO,CR,CU,CV,CW,CX,CY,CZ,DE,DJ,DK,DM,DO,DZ,EC,EE,EG,EH,ER,ES,ET,FI,FJ,FK,FM,FO,FR,GA,GB,GD,GE,GF,GG,GH,GI,GL,GM,GN,GP,GQ,GR,GS,GT,GU,GW,GY,HK,HM,HN,HR,HT,HU,ID,IE,IL,IM,IN,IO,IQ,IR,IS,IT,JE,JM,JO,JP,KE,KG,KH,KI,KM,KN,KP,KR,KW,KY,KZ,LA,LB,LC,LI,LK,LR,LS,LT,LU,LV,LY,MA,MC,MD,ME,MF,MG,MH,MK,ML,MM,MN,MO,MP,MQ,MR,MS,MT,MU,MV,MW,MX,MY,MZ,NA,NC,NE,NF,NG,NI,NL,NO,NP,NR,NU,NZ,OM,PA,PE,PF,PG,PH,PK,PL,PM,PN,PR,PS,PT,PW,PY,QA,RE,RO,RS,RU,RW,SA,SB,SC,SD,SE,SG,SH,SI,SJ,SK,SL,SM,SN,SO,SR,SS,ST,SV,SX,SY,SZ,TC,TD,TF,TG,TH,TJ,TK,TL,TM,TN,TO,TR,TT,TV,TW,TZ,UA,UG,UM,US,UY,UZ,VA,VC,VE,VG,VI,VN,VU,WF,WS,YE,YT,ZA,ZM,ZW';
            $info = $request->info;
            $info = json_decode($info, true);

            $Validator = Validator::make($info,[
                'name'=>['required','string' , 'min:1'],
                'description'=>['required','string'],
                'phone_number'=>['required', "phone:$codes"]
            ]);
            if($Validator->fails()){
                return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            $pdf_validator = Validator::make($request->all(), [
                'PDF'=>['required','mimes:pdf']
            ]);
            if($pdf_validator->fails()){
                return response()->json(['status' => 400, 'message' => $pdf_validator->errors()->messages(), 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            $file_extension = $request->PDF->getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $path = 'files/pdf';
            $request->PDF->move($path, $file_name);
            $pdf_file = "public/files/pdf/$file_name";

            $order = amateure_writer::create([
                'name'=> $info['name'],
                'description'=>$info['description'],
                'phone_number'=>$info['phone_number'],
                'PDF'=>$file_name,
                'user_id'=>Auth::id()
            ]);

            $data['order'] = $order;
            $data['pdf_file'] = $pdf_file;

            return response()->json(['status'=> 201,'message'=>'successful', 'data'=>$data], Response::HTTP_CREATED);
        }

        else
            return response()->json(['status'=> 200 ,'message'=>'currently closed.' , 'data'=>null],Response::HTTP_OK);
    }

    public function show(amateure_writer $amateure_writer)
    {
        $data['user_name']=$amateure_writer->user()->select('id' , amateure_writer::raw("CONCAT(first_name, ' ', last_name) as name") , 'photo')->first();
        $data['content'] = $amateure_writer;

        return response()->json(['status'=> 200 ,'message'=>'successful' , 'data'=>$data],Response::HTTP_OK);
    }

    public function update(Request $request, amateure_writer $amateure_writer)
    {
        $amateure_admin = amateure_admin::where('amateure_id',$amateure_writer->id)
        ->first();
        if(!$amateure_admin){
            $Validator = Validator::make($request->all(),[
                'name'=>['nullable','string' , 'min:1'],
                'description'=>['nullable','string'],
                'phone_number'=>['nullable', 'string', 'min:9', 'max:9']
            ]);
            if($Validator->fails()){
                return response()->json(['status' => 400 ,'message' => $Validator->errors()->messages() , 'data'=>null], Response::HTTP_BAD_REQUEST);
            }

            if($request->has('name')){
                $name = $request->input('name');
                $amateure_writer->update([
                   'name'=> $name,
                ]);
            }

            if($request->has('description')){
                $description=$request->input('description');
                $amateure_writer->update([
                   'description'=> $description,
                ]);
            }

            if($request->has('phone_number')){
                $phone_number = $request->input('phone_number');
                $amateure_writer->update([
                   'phone_number'=> $phone_number,
                ]);
            }

            $pdf_file = null;
            if($request->PDF)
            {
                $pdf_validator = Validator::make($request->all(), [
                    'PDF'=>'mimes:pdf'
                ]);
                if($pdf_validator->fails()){
                    return response()->json(['status' => 400,'message' => $pdf_validator->errors()->messages(), 'data'=>null], Response::HTTP_BAD_REQUEST);
                }

                $file_extension = $request->PDF->getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $path = 'files/pdf';
                $request->PDF->move($path, $file_name);
                $pdf_file = "public/files/pdf/$file_name";

                $amateure_writer->update([
                    'PDF'=> $file_name,
                ]);
            }

            $data ['amateure_writer'] = $amateure_writer;
            if($pdf_file)
            {
                $data['pdf_file'] = $pdf_file;
            }

            return response()->json(['status'=> 200, 'message'=>'successful', 'data'=>$data],Response::HTTP_OK);
        }
        else{
            return response()->json(['status'=> 200, 'message'=>'can not', 'data'=>null],Response::HTTP_OK);
        }

    }

    public function destroy(amateure_writer $amateure_writer)
    {
        $amateure_admin = amateure_admin::where('amateure_id',$amateure_writer->id)
        ->first();
        if(!$amateure_admin){
            $amateure_writer->delete();
            return response()->json(['status'=> 204 ,'message'=>'deleted successful', 'data'=>$amateure_writer], Response::HTTP_NO_CONTENT);
        }
        else{
            return response()->json(['status'=> 200, 'message'=>'can not', 'data'=>null],Response::HTTP_OK);
        }
    }
}
