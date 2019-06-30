<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\PasswordResetCode;

use Validator;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function sendMail(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
        ]);
        if($validator->fails()) {
            return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
//            return response([
//                'status'    =>400,
//                'message'   =>implode(",",$validator->errors()->all()),
//            ]);
        }

        $email = $request->email;
        $checkMail = User::where('email',$email)->first();
        if($checkMail){
            $code = rand(1000,9999);
            $resetPassword = PasswordResetCode::create([
                'email'=>$email,
                'code'=>$code
            ]);
            $data = array(
                'email'=>$email,
                'code'=>$code
            );
            $email = $request->email;
            Mail::to($email)
                ->send(new SendMail($data));
//            return '{"code":"200","message":"Success","data":'.'['.json_encode($resetPassword).']}';
            return response([
                'status'    =>200,
                'message'   =>'Success',
                'data'      =>$resetPassword
            ]);

        }else{
            $error = 'The Email Not Exist';
//            return '{"code":"400","message":'.'['.json_encode($error).']}';
            return response([
                'status'    =>400,
                'message'   =>$error,
            ]);
        }
    }

    public function checkCode(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'code'=>'required|min:4|max:4',
        ]);
        if($validator->fails()) {
//            return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
            return response([
                'status'    =>400,
                'message'   =>implode(",",$validator->errors()->all()),
            ]);
        }
        $email = $request->email;
        $code = $request->code;
        $checkCode = PasswordResetCode::where('email',$email)->where('code',$code)->first();
        if($checkCode){
//            return '{"code":"200","message":"Success","data":'.'['.json_encode($checkCode).']}';
            return response([
                    'status'    =>200,
                    'message'   =>'Success',
                    'data'      =>$checkCode
                ]);
        }else{
            $error = 'The Code is invalid';
//            return '{"code":"400","message":'.'['.json_encode($error).']}';
            return response([
                    'status'    =>400,
                    'message'   =>$error,
                ]);
        }
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'newpassword' => 'required|string|min:8',
        ]);
        if($validator->fails()) {
//            return '{"code":"400","message":' . '[' . json_encode($validator->errors()) . ']}';
            return response([
                'status'    =>400,
                'message'   =>implode(",",$validator->errors()->all()),
            ]);
        }
        $email = $request->email;
        $newPassword = Hash::make($request->newpassword);
        User::where('email',$email)
            ->update(['password' => $newPassword]);

        $deleteCode = PasswordResetCode::where('email',$email)->first();
        $deleteCode->delete();
        $success = 'Password Updated Successfully';
//        return '{"code":"200","message":'.'['.json_encode($success).']}';
        return response([
            'status'    =>200,
            'message'   =>$success,
        ]);
    }
}
