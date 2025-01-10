<?php

namespace App\Http\Controllers\API;

use App\Helper\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\User;
use App\Models\VerifyOTP;
use \Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   public function MerchantLogin(Request $request){
       $validator = Validator::make($request->all(), [
           'username' => 'required',
           'password' => 'required',
       ]);

       // If validation fails, return error response
       if ($validator->fails()) {
           return response()->json(['message' => $validator->errors()->first()], 422);
       }

       $user = User::where('username',$request->username)->where('role_id',2)->first();
       if ($user) {
           // Attempt to log in the user
           if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
               // Authentication passed...
               $user = Auth::user();
               $token = $user->createToken('Personal Access Token')->plainTextToken;
               $merchant = Merchant::with('user')->where('user_id', \auth()->user()->id)->first();
               return response()->json(['message' => 'User Login  successfully','data'=>$merchant, 'token' => $token], 200);
           } else {
               // Authentication failed
               return response()->json(['message' => 'Password is wrong !!'], 401);
           }
       }
       return response()->json(['message' => 'Please enter valid username'], 401);
   }
   public function CustomerLogin(Request $request){
       $validator = Validator::make($request->all(), [
           'email' => 'required',
           'password' => 'required',
       ]);

       // If validation fails, return error response
       if ($validator->fails()) {
           return response()->json(['message' => $validator->errors()->first()], 422);
       }

       $user = User::where('email',$request->email)->where('role_id',3)->first();
       if ($user) {
           // Attempt to log in the user
           if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
               // Authentication passed...
               $user = Auth::user();
               $token = $user->createToken('Personal Access Token')->plainTextToken;
               $customer = Customer::with('user')->where('user_id', \auth()->user()->id)->first();
               return response()->json(['message' => 'User Login  successfully','data'=>$customer, 'token' => $token], 200);
           } else {
               // Authentication failed
               return response()->json(['message' => 'Password is wrong !!'], 401);
           }
       }
       return response()->json(['message' => 'Please enter valid email'], 401);
   }

   public function logout(Request $request)
   {
//       dd(auth()->user()->tokens);
       auth()->user()->tokens->each(function ($token, $key) {
           $token->delete();
       });
//       Auth::logout();
       return response()->json(['message' => 'User Logged out  successfully'], 200);
   }

    public function verifyField(Request $request){
        $otp = random_int(100000, 999999);
        $message ="Your login OTP is ".$otp." from ". auth()->user()->name . env('NAME');
        if ($request->type == "mobile") {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|unique:users',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
            $url = env('SMS_URL').'&mobileNos='.$request->mobile.'&message='.$message;
            $response = CustomHelper::GetApi($url);
            $typeName = $request->mobile;
        }
        else{
            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users',
            ]);
            $typeName = $request->email;
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
        }
        VerifyOTP::updateOrCreate(['type'=>$typeName],['otp'=>$otp]);
//        if ($response->responseCode== 3001) {
        return response()->json(['message' => 'OTP sent successfully','data'=>$typeName], 200);
//        }
//        return response()->json(['message' => 'something wants wrong','data'=>$response], 422);
    }

    public function verifyOTP(Request $request){
        $field = VerifyOTP::where('type',$request->type)->first();

        if($field && $field->otp == $request->otp){
            $field->delete();
            return response()->json(['message' => 'OTP verify successfully'], 200);
        }
        return response()->json(['message' => 'Please enter valid OTP'], 422);
    }

    public function setmpin(Request $request)
    {
        $validate = $request->validate([
            'mpin' => 'required'
        ]);
        $id = auth()->user()->id;

        $data = User::find($id);
        $data->mpin = $request->mpin;
        $data->save();

        return response()->json(['message' => 'MPIN set successfully'], 200);
    }

    public function forgotmpin(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'mpin' => 'required',
        ]);
        $id = auth()->user()->id;

        $data = User::find($id);

        if($data->username == $request->username && Hash::check($request->password, $data->password) ){
            $data->mpin = $request->mpin;
            $data->save();
            return response()->json(['message' => 'MPIN set successfully'], 200);
        }else{
            return response()->json(['message' => 'Invaliad authorisation'], 401);
        }

    }

    public function getmpin()
    {
        $id = auth()->user()->id;

        $data = User::find($id);

        return response()->json(['message' => 'MPIN get successfully','MPIN' => $data->mpin], 200);
    }
}
