<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Merchant;
use App\Models\page;
use App\Models\Setting;
use App\Models\TransferCoins;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
    public function contact()
    {
        $contact = Contact::get();
        $data =[];
        foreach ($contact as $con) {
            $data[$con->name] = $con->value;
        }
        return response()->json(['message' => 'Contact data', 'data' => $data], 200);
    }
    public function setting()
    {
        $settings = Setting::get();
        $data =[];
        foreach ($settings as $setting) {
            $setting->value = url('/storage/images/'.$setting->value);
            $data[$setting->name] = $setting->value;
        }
        return response()->json(['message' => 'Setting data', 'data' => $data], 200);
    }

    public function qrInfo(Request $request)
    {
        $id = \auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'QR_image' => 'required',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $merchant = Merchant::with('user')->where('user_id', $id)->first();
        if ($request->hasFile('QR_image')) {
            $file = $request->file('QR_image');
            $filename = "QR_image/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $merchant->QR_image = $filename;
        }

        $merchant->upi_id = $request['upi_id'];
        $merchant->update();

        if ($merchant) {
            return response()->json(['message' => 'personal info', 'data' => $merchant], 200);
        }
        return response()->json(['message' => 'No data found'], 404);
    }

    public function personalInfo(Request $request)
    {
        $id = \auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'shop_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);
        User::where('id',$id)->update(['name'=>$request->name]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $merchant = Merchant::with('user')->where('user_id', $id)->first();
        $merchant->shop_name = $request['shop_name'];
        $merchant->address = $request['address'];
        $merchant->city = $request['city'];
        $merchant->postal_code = $request['postal_code'];
        $merchant->update();

        if ($merchant) {
            return response()->json(['message' => 'personal info', 'data' => $merchant], 200);
        }
        return response()->json(['message' => 'No data found'], 404);
    }

    public function bankDetail(Request $request)
    {
        $id = \auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'ifsc' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $merchant = Merchant::with('user')->where('user_id', $id)->first();
        $merchant->bank_name = $request['bank_name'];
        $merchant->account_name = $request['account_name'];
        $merchant->account_number = $request['account_number'];
        $merchant->ifsc = $request['ifsc'];
        $merchant->update();

        if ($merchant) {
            return response()->json(['message' => 'Bank Details', 'data' => $merchant], 200);
        }
        return response()->json(['message' => 'No data found'], 404);
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $user = User::findOrFail(auth()->user()->id);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->password_text = $request->new_password;
            $user->save();

            return response()->json(['message' => 'password reset', 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'Old password is incorrect'], 422);
        }
    }

    public function createCustomer(Request $request){

        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => 'required|unique:users',
            'mobile' => 'required|unique:users',
            'alter_mobile' => 'required',
            'imei1' => 'required',
            'imei2' => 'required',
            'coin_type' => 'required',
        ]);

        $coin = User::find($id);
        $type = $request['coin_type'];
        if ($coin->$type<=0 || $coin->$type == null){
            return response()->json(['message' => "insufficient wallet balance"], 422);
        }
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $userData =[
            'name'=> $request->name,
            'email'=> $request->email,
            'mobile'=> $request->mobile,
            'role_id'=> 3,
            'status'=> 1,
            'password'=> Hash::make(12345678),
            'password_text'=> 12345678,
        ];
        $user = User::create($userData)->assignRole('customer');
        $customer=[
            'user_id'=>$user->id,
            'merchant_id'=>$id,
            'coin_type' => $request->coin_type='rambaan_coin'?1:2,
            'imei1'=>$request->imei1,
            'imei2'=>$request->imei2,
            'address'=>$request->address,
            'alter_mobile'=>$request->alter_mobile,
            'bank_name'=>$request->bank_name,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'ifsc'=>$request->ifsc,
        ];
        $device = Device::where('imei1',$request->imei1)->first();
        if ($device){
            $device->user_id = $user->id;
            $device->update();
        }
//        Device::create([
//            'user_id'=> $user->id,
//            'imei1' => $request['imei1'],
//            'imei2' => $request['imei2'],
//        ]);
        if ($request->hasFile('aadhar_front')) {
            $file = $request->file('aadhar_front');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['aadhar_front'] = $filename;
        }
        if ($request->hasFile('aadhar_back')) {
            $file = $request->file('aadhar_back');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['aadhar_back'] = $filename;
        }
        if ($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['pan_card'] = $filename;
        }
        if ($request->hasFile('customer_photo')) {
            $file = $request->file('customer_photo');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['customer_photo'] = $filename;
        }
        if ($request->hasFile('merchant_photo')) {
            $file = $request->file('merchant_photo');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['merchant_photo'] = $filename;
        }
        if ($request->hasFile('customer_with_device')) {
            $file = $request->file('customer_with_device');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['customer_with_device'] = $filename;
        }
        if ($request->hasFile('blank_cheque')) {
            $file = $request->file('blank_cheque');
            $filename = "customer_data/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer['blank_cheque'] = $filename;
        }
        $customerData = Customer::create($customer);
        if ($request->emi == 1) {
            $data = [
                "billing_amount" => $request->billing_amount,
                "file_charge" => $request->file_charge,
                "down_payment" => $request->down_payment,
                "loan_amount" => $request->loan_amount,
                "interest" => $request->interest,
                "month" => $request->month,
                "monthly_amount" => $request->monthly_amount,
                "first_emi_date" => $request->first_emi_date,
                "total_amount" => $request->total_amount,
                "total_interest" => $request->total_interest,
                "user_id" => $user->id,
                "merchant_id" => auth()->user()->id,
                "status" => 1
            ];
            $this->emi($data);
        }
        if ($customerData) {
            $coin->$type = $coin->$type - 1;
            $coin->update();
            TransferCoins::create([
                'from_user_id'=>$id,
                'to_user_id'=>$customerData->id,
                'coin_quantity'=>1,
                'coin_type'=>$request->coin_type,
                'type'=>"add customer",
                'remarks'=>"new customer added"
            ]);
        }
        $user['customer_password']="12345678";
        return response()->json(['message' => 'Customer created successfully','data'=>$user], 200);
    }

    public function customers()
    {
        $id = auth()->user()->id;
        $cusotmers = Customer::where('merchant_id', $id)->with(['user', 'loan','device'])->get();
        if (count($cusotmers) > 0) {
            return response()->json(['message' => 'Customers data', 'data' => $cusotmers], 200);
        }
        return response()->json(['message' => 'No customers found'], 422);
    }
    public function customer($id)
    {
//        $id = auth()->user()->id;
        $cusotmer = Customer::where('id', $id)->with(['user', 'loan','device'])->first();
        if ($cusotmer) {
            return response()->json(['message' => 'Customers data', 'data' => $cusotmer], 200);
        }
        return response()->json(['message' => 'No customers found'], 422);
    }

    public function emi($data)
    {
        $loan = Loan::create($data);
        $j = ($data['month']);
        $first_date = $data['first_emi_date'];
        $amount = $data ['monthly_amount'];
        $user = $data ['user_id'];
        for ($i = 0; $i < $j; $i++) {
            $date = $first_date;
            $newDate = date('Y-m-d', strtotime($date . '+' . $i . ' month'));
            $data = [
                'user_id' => $user,
                'loan_id' => $loan->id,
                'due_date' => $newDate,
                'amount' => $amount,
            ];
            LoanPayment::create($data);
        }
    }

    public function updateEMI(Request $request){
        $id =auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'emi_id' => 'required',
            'status' => 'required',
            'remarks' => 'required',
        ]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $loan = LoanPayment::find($request->emi_id);
        if($loan){
            $loan->update([
                'status'=>$request->status,
                'payment_mode'=>'offline',
                'paid_amount'=>$request->paid_amount??$loan->amount,
                'paid_by_user'=>$id,
                'remarks'=>$request->remarks,
                'paid_date'=>now(),
            ]);
            return response()->json(['message' => "EMI Paid successfully"], 200);
        }
        return response()->json(['message' => "EMI Detail not found"], 422);
    }

//    public function checkDevice(Request $request){
//        $id = auth()->user()->id;
//        $validator = Validator::make($request->all(), [
//            'imei' => 'required|min:15|max:15',
//        ]);
//        // If validation fails, return error response
//        if ($validator->fails()) {
//            return response()->json(['message' => $validator->errors()->first()], 422);
//        }
//        $checkImie = Customer::where('imei1',$request->imei)->first();
//        if ($checkImie){
//            $status = $checkImie->device_status;
//            if ($status == 1) {
//                $checkImie->update(['device_status' => 0]);
//            }
//            else{
//            $checkImie->update(['device_status' => 1]);
//            }
//            return response()->json(['message' => 'Device found','data' =>"true","status"=>$status], 200);
//        }
//        return response()->json(['message' => 'Device Not found','data' =>"false" ], 422);
//    }
    public function devices(){
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $devices = Device::whereIn('user_id',$ids)->get();
        if (count($devices)>0){
            return response()->json(['message' => 'Device found','data'=>$devices], 200);
        }
        return response()->json(['message' => 'Device Not found',], 422);
    }

    public function transferEmailVerify(Request $request){
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $email = User::where('email',$request->email)->where('role_id',2)->where('id','!=',$id)->first();
        if ($email){
            return response()->json(['message' => 'Email verify successfully','data'=>$email], 200);
        }
        return response()->json(['message' => 'Merchant Not found'], 422);
    }

    public function transferCoin(Request $request){
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'quantity' => 'required',
            'type' => 'required|in:brahmastra_coin,rambaan_coin',
        ]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $email = User::where('email',$request->email)->where('role_id',2)->where('id','!=',$id)->first();
        if ($email){
            $user = User::where('id',$id)->first();
            $merchant = User::where('id',$email->id)->first();
            $type = $request->type;
            $coin = $user->$type;
            $merchantCoin = $merchant->$type;
            if ($coin <= $request->quantity){
                return response()->json(['message' => "You don't have enough coins",], 422);
            }
            $userCoin = $coin-$request->quantity;
            $merchantCoin = $merchantCoin + $request->quantity;
            User::where('id',$email->id)->update([$type=>$merchantCoin]);
            User::where('id',$id)->update([$type=>$userCoin]);
            TransferCoins::create([
                'from_user_id'=>$id,
                'to_user_id'=>$email->id,
                'coin_quantity'=>$request->quantity,
                'coin_type'=>$type
            ]);
            return response()->json(['message' => 'Coin transfer successfully',], 200);
        }
        return response()->json(['message' => 'Merchant Not found'], 422);
    }

    public function pendingEmi(){
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $pendingEmi = LoanPayment::whereIn('user_id',$ids)->where('due_date',"<",now())->where('paid_amount',null)->where('paid_date',null)->where('status','!=','success')->pluck('user_id');
        $users = Customer::with('user','loan','loanPayment')->whereIn('user_id',$pendingEmi)->where('merchant_id',$id)->get();
        if(count($users)>0)
            return response()->json(['message' => 'user data','data'=>$users], 200);
        return response()->json(['message' => 'Not pending emi found',], 422);
    }
    public function emiDetail($id){

        $emiDetail = LoanPayment::where('loan_id',$id)->get();
        if(count($emiDetail)>0)
            return response()->json(['message' => 'user data','data'=>$emiDetail], 200);
        return response()->json(['message' => 'Not pending emi found',], 422);
    }
    public function emiPaymentDetail($id){

        $emiDetail = LoanPayment::find($id);
        if($emiDetail)
            return response()->json(['message' => 'user data','data'=>$emiDetail], 200);
        return response()->json(['message' => 'Not pending emi found',], 422);
    }
    public function page($name)
    {
        $page = page::where('slug',$name)->where('content','!=',"")->first();
        if($page) {
            return response()->json(['message' => 'page data', 'data' => $page], 200);
        }
        return response()->json(['message' => 'Not content found',], 422);
    }
}
