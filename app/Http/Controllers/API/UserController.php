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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
// use GuzzleHttp\Exception\GuzzleException;
// use GuzzleHttp\Client;

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
            'email' => 'required',
            'mobile' => 'required',
            'alter_mobile' => 'required',
            'imei1' => 'required|unique:customers',
            'imei2' => 'required|unique:customers',
            'coin_type' => 'required',
        ]);

        $coin = User::find($id);
        $type = $request['coin_type'];

        // if the data_type is brahmastra_prime_coin that time cut the brahmastra_coin
        if ($type == 'brahmastra_prime_coin') {
            $type = 'brahmastra_coin';
        }

        if ($coin->$type<=0 || $coin->$type == null){
            return response()->json(['message' => "insufficient wallet balance"], 422);
        }
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $loginId = substr($request->imei1 ,3);

        $userData =[
            'name'=> $request->name,
            'email'=> $request->email,
            'mobile'=> $request->mobile,
            'role_id'=> 3,
            'status'=> 1,
            'login_id'=> $loginId,
            'password'=> Hash::make(12345678),
            'password_text'=> 12345678,
        ];
        $user = User::create($userData)->assignRole('customer');
        if($request->coin_type == 'rambaan_coin') {
            $coin_type = 3;
        }elseif ($request->coin_type == 'brahmastra_coin') {
            $coin_type = 1;
        }elseif ($request->coin_type == 'brahmastra_prime_coin'){
             $coin_type = 2;
        }
        $customer=[
            'user_id'=>$user->id,
            'merchant_id'=>$id,
            'coin_type' => $coin_type,
            'imei1'=>$request->imei1,
            'imei2'=>$request->imei2,
            'address'=>$request->address,
            'alter_mobile'=>$request->alter_mobile,
            'bank_name'=>$request->bank_name,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'ifsc'=>$request->ifsc,
            'finance_type'=>$request->finance_type,
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

        $transferCoinsUser = TransferCoins::where('from_user_id', $id)->orderBy('created_at', 'DESC')->first();
        $from_user_brahmastra_coin = 0;
        $from_user_rambaan_coin = 0;

        $to_user_brahmastra_coin = 0;
        $to_user_rambaan_coin = 0;

        if($transferCoinsUser){
        if($transferCoinsUser->from_user_rambaan_coin != null && $type == 'rambaan_coin') {
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin - 1;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin - 1;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin;
            }
        }elseif($transferCoinsUser->from_user_brahmastra_coin != null && $type == 'brahmastra_coin') {
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin - 1;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin - 1;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin;
            }
        }else {
            $user = User::where('id', $id)->first();
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $user->rambaan_coin - 1;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin ? $transferCoinsUser->from_user_brahmastra_coin : NULL;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $user->brahmastra_coin - 1;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin ? $transferCoinsUser->from_user_rambaan_coin : NULL;
            }
        }}

        if ($customerData) {
            $coin->$type = $coin->$type - 1;
            $coin->update();
            TransferCoins::create([
                'from_user_id'=>$id,
                'to_user_id'=>$user->id,
                'coin_quantity'=>1,
                'coin_type'=>$request->coin_type,
                'from_user_rambaan_coin'=>$from_user_rambaan_coin ?? NULL,
                'from_user_brahmastra_coin'=>$from_user_brahmastra_coin ?? NULL,
                'to_user_rambaan_coin'=>$to_user_rambaan_coin ?? NULL,
                'to_user_brahmastra_coin'=>$to_user_brahmastra_coin ?? NULL,
                'type'=>"add customer",
                'remarks'=>"new customer added"
            ]);
        }
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
                "status" => 1,
                'lock_type' => $request->lock_type,
            ];
            $this->emi($data);
        }

        $user['customer_password']="12345678";
        return response()->json(['message' => 'Customer registered successfully.','data'=>$user], 200);
    }

    public function customers(Request $request)
    {
        Log::info('device detail');
        $id = auth()->user()->id;
        $query = Customer::with(['user', 'loan','device','loanPayment'])->where('merchant_id', $id)
            ->orderBy('id', 'desc');

        $search = $request->input('name', ''); // Search input from DataTables
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $mobile = $request->input('mobile', '');
        if ($mobile) {
            $query->whereHas('user', function ($q) use ($mobile) {
                $q->where('mobile', 'like', '%' . $mobile . '%');
            });
        }

        $customers = $query->paginate(25);

        //$cusotmers = Customer::where('merchant_id', $id)->with(['user', 'loan','device','loanPayment'])->paginate(15);
        if (count($customers) > 0) {
            return response()->json(['message' => 'Customers data', 'data' => $customers], 200);
        }
        return response()->json(['message' => 'No customers found'], 422);
    }
    public function customer($id){
//        $id = auth()->user()->id;
        $cusotmer = Customer::where('id', $id)->with(['user', 'loan','device','loanPayment'])->first();
        // Log::alert('device detail', $cusotmer);

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
            // if emi first date is 31 that time due dates is month last date
            $date31 = new Carbon( $date );
            $newDate = $date31->format('d') == "31" ? date('Y-m-d', strtotime('last day of '. '+' . $i . ' month')) : date('Y-m-d', strtotime($date . '+' . $i . ' month'));
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
            'payment_mode' => 'required',
        ]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        $loan = LoanPayment::find($request->emi_id);

        if($loan){
            $loan->update([
                'status'=>$request->status,
                'payment_mode'=>$request->payment_mode,
                'paid_amount'=>$request->paid_amount??$loan->amount,
                'paid_by_user'=>$id,
                'remarks'=>$request->remarks,
                'paid_date'=>now(),
            ]);
            return response()->json(['message' => "EMI Paid successfully",'data'=>$loan], 200);
        }
        return response()->json(['message' => "EMI Detail not found"], 422);
    }

   public function checkDevice(Request $request){
       $id = auth()->user()->id;
       $validator = Validator::make($request->all(), [
           'imei' => 'required|min:15|max:15',
       ]);
       // If validation fails, return error response
       if ($validator->fails()) {
           return response()->json(['message' => $validator->errors()->first()], 422);
       }
       $checkImie = Customer::where('imei1',$request->imei)->first();
       if ($checkImie){
           $status = $checkImie->device_status;
           if ($status == 1) {
               $checkImie->update(['device_status' => 0]);
           }
           else{
           $checkImie->update(['device_status' => 1]);
           }
           return response()->json(['message' => 'Device found','data' =>"true","status"=>$status], 200);
       }
       return response()->json(['message' => 'Device Not found','data' =>"false" ], 422);
   }

    public function devices(){
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $devices = Device::whereIn('user_id',$ids)->paginate(15);
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

    public function transitionEmi(){
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $pendingEmi = LoanPayment::whereIn('user_id',$ids)->where('status','=','success')->pluck('user_id');
        $pendingData = LoanPayment::whereIn('user_id',$ids)->where('status','=','success')->get();
        $users = Customer::with('user','loan','loanPayment')->whereIn('user_id',$pendingEmi)->where('merchant_id',$id)->get();
        if(count($users)>0)
            return response()->json(['message' => 'user data','data'=>$users], 200);
        return response()->json(['message' => 'Not success emi found'], 422);
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

    public function update_customer(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'imei1' => 'required|min:15,max:15',
            'imei2' => 'required|min:15,max:15',
            'coin_type' => 'required',
            'merchant_id'=>'required',
            'alter_mobile' => 'required',
            'user_id' => 'required',
        ]);

        $id = $request->id;
        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->update();

        $customer = Customer::find($id);
        if ($request->hasFile('aadhar_front')) {
            $oldFile = $customer->aadhar_front;
            $file = $request->file('aadhar_front');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->aadhar_front = $filename;
        }
        if ($request->hasFile('aadhar_back')) {
            $oldFile = $customer->aadhar_back;
            $file = $request->file('aadhar_back');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->aadhar_back = $filename;
        }
        if ($request->hasFile('pan_card')) {
            $oldFile = $customer->pan_card;
            $file = $request->file('pan_card');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->pan_card = $filename;
        }
        if ($request->hasFile('customer_photo')) {
            $oldFile = $customer->customer_photo;
            $file = $request->file('customer_photo');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->customer_photo = $filename;
        }
        if ($request->hasFile('merchant_photo')) {
            $oldFile = $customer->merchant_photo;
            $file = $request->file('merchant_photo');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->merchant_photo = $filename;
        }
        if ($request->hasFile('customer_with_device')) {
            $oldFile = $customer->customer_with_device;
            $file = $request->file('customer_with_device');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->customer_with_device = $filename;
        }
        if ($request->hasFile('blank_cheque')) {
            $oldFile = $customer->blank_cheque;
            $file = $request->file('blank_cheque');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "customer_data/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $customer->blank_cheque = $filename;
        }

        $type = $request['coin_type'];

        // if the data_type is brahmastra_prime_coin that time cut the brahmastra_coin
        if ($type == 'brahmastra_prime_coin') {
            $type = 'brahmastra_coin';
        }

        if($request->coin_type == 3) {
            $coin_type = 3;
        }elseif ($request->coin_type == 1) {
            $coin_type = 1;
        }elseif ($request->coin_type == 2){
             $coin_type = 2;
        }

        $customer->user_id = $user->id;
        $customer->merchant_id = $request->merchant_id;
        $customer->address = $request->address;
        $customer->imei1 = $request->imei1;
        $customer->imei2 = $request->imei2;
        $customer->alter_mobile = $request->alter_mobile;
        $customer->coin_type = $coin_type;
        $customer->bank_name = $request->bank_name;
        $customer->account_name = $request->account_name;
        $customer->account_number = $request->account_number;
        $customer->ifsc = $request->ifsc;
        $customer->finance_type = $request->finance_type;

        if ($customer->user_id == $user->id) {
            $loan = Loan::where('user_id', $customer->user_id)->first();

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
                "status" => 1,
                'lock_type' => $request->lock_type ? $request->lock_type : 1 ,
            ];

            if (! $loan) {
                $this->emi($data);
            }else {
                if($loan){
                $loan->billing_amount = $request->billing_amount ? $request->billing_amount : $loan->billing_amount;
                $loan->file_charge = $request->file_charge ? $request->file_charge : $loan->file_charge;
                $loan->down_payment = $request->down_payment ? $request->down_payment : $loan->down_payment;
                $loan->loan_amount = $request->loan_amount ? $request->loan_amount : $loan->loan_amount;
                $loan->interest = $request->interest ? $request->interest : $loan->interest;
                $loan->month = $request->month ? $request->month : $loan->month;
                $loan->monthly_amount = $request->monthly_amount ? $request->monthly_amount : $loan->monthly_amount;
                $loan->first_emi_date = $request->first_emi_date ? $request->first_emi_date : $loan->first_emi_date;
                $loan->total_amount = $request->total_amount ? $request->total_amount : $loan->total_amount;
                $loan->total_interest = $request->total_interest ? $request->total_interest : $loan->total_interest;
                $loan->user_id = $user->id;
                $loan->merchant_id = auth()->user()->id;
                $loan->status = 1;
                $loan->lock_type = $request->lock_type ? $request->lock_type : 1;

                $loan->update();

                $loanDetails = LoanPayment::where('loan_id', $loan->id)->get();
                if($loanDetails){
                    LoanPayment::where('loan_id', $loan->id)->delete();
                    $j = ($request['month'] ? $request['month'] : $loan->month);
                    $first_date = $request['first_emi_date'] ? $request['first_emi_date'] : $loan['first_emi_date'];
                    $amount = $request ['monthly_amount'] ? $request ['monthly_amount'] : $loan ['monthly_amount'];
                    $user = $loan ['user_id'];
                    for ($i = 0; $i < $j; $i++) {
                        $date = $first_date;
                        // if emi first date is 31 that time due dates is month last date
                        $date31 = new Carbon( $date );
                        $newDate = $date31->format('d') == "31" ? date('Y-m-d', strtotime('last day of '. '+' . $i . ' month')) : date('Y-m-d', strtotime($date . '+' . $i . ' month'));

                         $data = [
                            'user_id'=>$user,
                            'loan_id'=>$loan->id,
                            'due_date'=>$newDate,
                            'amount'=>$amount,
                        ];
                        LoanPayment::create($data);
                    }
                }
            }
            }
        }

        $customer->update();

        return response()->json(['success'=>'true','message'=>'Update data successfully', 'data' => $customer],200);
    }

    public function cointransition(Request $request){
        $id = auth()->user()->id;
        $query = TransferCoins::with('fromUser', 'toUser');
        $from = $request->input('from');
        $to = $request->input('to');
        // $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        // $to = $request->input('to', Carbon::now()->format('Y-m-d'));
        $coinTypeFilter = $request->input('type');
        // $filter = $request->input('filter', 'all');

        // Filter by dates
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        if($from && $to){
            $query->whereBetween('created_at', [$fromDate, $toDate])->orderByDesc('created_at');
        }
        // $query->whereBetween('created_at', [$fromDate, $toDate])->orderByDesc('created_at');

        // Filter by coin_type if provided
        if ($coinTypeFilter == 'all' || $coinTypeFilter == null) {
            $query->whereBetween('created_at', [$fromDate, $toDate])->orderByDesc('created_at');
        }else {
            $query->where('coin_type', $coinTypeFilter)->orderByDesc('created_at');

        }

        // Filter by type if provided
        // if ($filter != 'all') {
        //     $query->where('type', $filter);
        // }

        // Restrict results based on user role
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('employee')) {

            $query->where(function ($query) use ($id) {
                $query->where('from_user_id', $id)
                    ->orWhere('to_user_id', $id);
            });
        }

        if($from && $to || $coinTypeFilter){
            $users = $query->get();
        }else {
            $users = TransferCoins::with('fromUser', 'toUser')->get();
        }


        return response()->json(['message' => 'Coin transition data','data'=>$users], 200);
    }

    public function createBanner(Request $request){

        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'banner' => 'required',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $banner = new Banner;
        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $filename = "banner/" . time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $banner['banner'] = $filename;
        }
        // $banner->banner = $request->banner;
        $banner->save();

        return response()->json(['message' => 'Banner Successfully Added.','data'=>$banner], 200);
    }

    public function banners(){
        // $id = auth()->user()->id;
        $banners = Banner::all();

        if (count($banners) > 0) {
            return response()->json(['message' => 'Baneers data', 'data' => $banners], 200);
        }
        return response()->json(['message' => 'No Baneers found'], 422);
    }

    public function autoLock(Request $request) {
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $pendingEmi = LoanPayment::whereIn('user_id',$ids)->where('due_date',"<",now())->where('paid_amount',null)->where('paid_date',null)->where('status','!=','success')->pluck('user_id');
        $users = Customer::with('user','loan','loanPayment')->whereIn('user_id',$pendingEmi)->where('merchant_id',$id)->get();

        if(count($users)>0){
            $pendingImeiLists = Customer::whereIn('user_id',$pendingEmi)->where('merchant_id',$id)->pluck('imei1');

            // $urls = [];
            if($pendingImeiLists) {
                foreach ($pendingImeiLists as $list) {
                    $urls[] = Http::withHeaders(['Authorization' => 'lhE8IVY4b1W0dP/ioK575dzAW2LgrPiUZXq5kfQFhNDzEeSigtGNW9VIfEl+xvC2QnazQ1JEgcDCc9JtcIguw+FWndboy7lmVUZjyfhiSEisasyBW3p/qv5cW/1EQE4P3T3F8IuqvZBJfoqgmnF4YPQNtU1dL4r9lR/wkqbDvTptSdOVyLp/50v8wLxuNG5zp8qTNrvwZ/DzCBNBKfD14l+/oZDV90YifIWrdXD54Hfzcq8AeUCS5ItO5XbrEuvYXnKeyqAwLmu5pncs1cgo6NOcEW+sdYFW9q+qgzWDs6o5txVan+MtsfC8truZbcyhcxkxBYXkgYg6T4yHjVUxzIZUpkIJe6wdw5CajRdnMGk='])->get(`https://emm.kjsstpay.com/api/customer/$list`);
                }
            }


            return response()->json(['message' => 'autoLock users', 'data' => $users], 200);
        }
        return response()->json(['message' => 'Not pending emi found',], 422);
    }
}
