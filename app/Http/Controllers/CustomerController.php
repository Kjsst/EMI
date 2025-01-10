<?php

namespace App\Http\Controllers;

use App\Helper\CustomHelper;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use DB;

class CustomerController extends Controller
{
    public function index(Request $request){
        /*$merchants = Customer::groupBy('merchant_id')->having(DB::raw('count(merchant_id)'), '>', 1)->pluck('merchant_id');
        foreach ($merchants as $mer){
            $notDuplicate = Customer::where('merchant_id', '!=', $mer)->get();

        }*/
        return view('customer.index', ['merchants' => []]);
    }

    public function getCustomerData(Request $request)
    {
        // Start the query to get customers along with their relationships
        $query = Customer::with('user', 'merchant', 'device')
            ->orderBy('id', 'desc');

        // Check if a specific merchant is provided in the request and filter accordingly
        $merchant = $request->input('merchant', 'all');
        if ($merchant != 'all') {
            $query->where('merchant_id', $merchant);
        }

        $filter = $request->input('finance', 'all');
        if ($filter != 'all' && $filter != 'NONE') {
            $query->where('finance_type', $filter);
        }

        // Add search functionality for user name
        $search = $request->input('name', ''); // Search input from DataTables
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $alternativeMobile = $request->input('alternative_mobile', ''); // Search input from DataTables
        if ($alternativeMobile) {
            $query->where('alter_mobile', 'like', '%' . $alternativeMobile . '%');
        }

        $mobile = $request->input('mobile', ''); // Search input from DataTables
        if ($mobile) {
            $query->whereHas('user', function ($q) use ($mobile) {
                $q->where('mobile', 'like', '%' . $mobile . '%');
            });
        }

        $imei1 = $request->input('imei1', ''); // Search input from DataTables
        if ($imei1) {
            $query->whereHas('device', function ($q) use ($imei1) {
                $q->where('imei1', 'like', '%' . $imei1 . '%');
            });
        }

        $imei2 = $request->input('imei2', ''); // Search input from DataTables
        if ($imei2) {
            $query->whereHas('device', function ($q) use ($imei2) {
                $q->where('imei2', 'like', '%' . $imei2 . '%');
            });
        }

        // Save the original query for filtered count (without pagination)
        $filteredQuery = clone $query;

        // Get pagination parameters from DataTables
        $length = $request->input('length', 10);  // Number of records per page
        $start = $request->input('start', 0);     // Offset (start) for pagination

        // Apply pagination
        $query->skip($start)->take($length);  // Skip and take for pagination

        // Get the filtered data
        $customers = $query->get();  // Fetch the data
        // Update devices based on the customer data (if necessary)
        foreach ($customers as $customer) {
            foreach ($customer->device as $device) {
                if ($customer->imei1 == $device->imei1) {
                    $device->user_id = $customer->user_id;
                    $device->save();  // Perform update (save)
                }
            }
        }

        // Map the customers into the format DataTables expects
        $data = $customers->map(function ($customer, $key) {
            return [
                'sr_no' => $key + 1,
                'name' => $customer->user->name ?? "-",
                'type' => $this->getCoinType($customer->coin_type),
                'imei1' => $customer->device->first()->imei1 ?? "-",
                'imei2' => $customer->device->first()->imei2 ?? "-",
                'merchant_name' => $customer->merchant->name ?? "-",
                'email' => $customer->user->email ?? "-",
                'mobile' => $customer->user->mobile ?? "-",
                'alter_mobile' => $customer->alter_mobile ?? "-",
                'reg_date' => \Carbon\Carbon::parse($customer->created_at)->format('d/m/y h:i:s A') ?? "-",
                'actions' => [
                    'view' => route('customer.view', $customer->id),
                    'edit' => route('customer.edit', $customer->id),
                    'device' => route('customer.device', $customer->user_id),
                    'delete' => route('customer.delete', $customer->id),
                ],
            ];
        });

        // Get the total count of filtered records
        $recordsFiltered = $filteredQuery->count();

        // Return data in the format required by DataTables
        return response()->json([
            'recordsTotal' => Customer::count(),  // Total records in the database (no filters)
            'recordsFiltered' => $recordsFiltered,  // Records after filtering
            'data' => $data,  // The actual data
        ]);
    }


    private function getCoinType($coin_type)
    {
        switch ($coin_type) {
            case 1: return 'Brahmastra';
            case 2: return 'Brahmastra Prime';
            case 3: return 'Rambaan';
            default: return '-';
        }
    }

    public function create(){
        $merchants = User::where('role_id',2)->get();
        return view('customer.create',['merchants'=>$merchants]);
    }

    public function view($id){

        $customer = Customer::with('user','merchant','loanPayment','loan')->where('id',$id)->first();
        $device = Device::where('imei1', $customer->imei1)->first();
        if ($device){
            $device->user_id = $customer->user_id;
            $device->update();
        }
        return view('customer.view',['customer'=>$customer]);
    }

    public function edit($id){
        $customer = Customer::with('user')->where('id',$id)->first();
        $merchants = User::where('role_id',2)->get();
        return view('customer.edit', [
            'customer' => $customer,
            'merchants'=>$merchants
        ]);
    }

    public function store(Request $request){
        if ($request->monthly_amount_hidden != null) {
            $request->validate([
                'first_emi_date' => 'required',
            ]);
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'alter_mobile' => 'required',
            'imei1' => 'required|min:15,max:15',
            'imei2' => 'required|min:15,max:15',
            'coin_type' => 'required',
            'merchant_id'=>'required',
            'address'=>'required',
            'password'=>'required'

        ]);
        $user = User::create([
            'name' => $request['name'],
            'mobile' => $request['mobile'],
            'email' => $request['email'],
            'role_id' => 3,
            'status' => 1,
            'password' => Hash::make($request['password']),
            'password_text' => $request['password'],
        ])->assignRole('customer');

        $customer =[
            'user_id' => $user->id,
            'merchant_id' => $request['merchant_id'],
            'alter_mobile' => $request['alter_mobile'],
            'address' => $request['address'],
            'imei1' => $request['imei1'],
            'imei2' => $request['imei2'],
            'coin_type' => $request['coin_type'],
            'bank_name' => $request['bank_name'],
            'account_name' => $request['account_name'],
            'account_number' => $request['account_number'],
            'ifsc' => $request['ifsc'],
            'pin' => $request['pin']??null,
        ];
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
        $device = Device::where('imei1',$request->imei1)->first();
        if ($device){
            $device->user_id = $user->id;
            $device->update();
        }
        if ($request->monthly_amount_hidden != null) {
            $data = [
                "billing_amount" => $request->billing_amount,
                "file_charge" => $request->file_charge?? 0,
                "down_payment" => $request->down_payment,
                "loan_amount" => $request->loan_amount_hidden,
                "interest" => $request->interest,
                "month" => $request->month,
                "monthly_amount" => $request->monthly_amount_hidden,
                "first_emi_date" => $request->first_emi_date,
                "total_amount" => $request->total_amount,
                "total_interest" => $request->total_interest,
                "user_id" => $user->id,
                "merchant_id" => $request->merchant_id,
                "status" => 1
            ];
            $this->emi($data);
        }

        return redirect()->route('customer')->with('success','Customer created successfully.');
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $request->user_id . '|max:255,',
            'mobile' => 'required|unique:users,mobile,'. $request->user_id,
            'address' => 'required',
            'imei1' => 'required|min:15,max:15',
            'imei2' => 'required|min:15,max:15',
            'coin_type' => 'required',
            'merchant_id'=>'required',
            'alter_mobile' => 'required',
        ]);

        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        if($request->password) {
            $user->password = Hash::make($request['password']);
            $user->password_text = ($request['password']);
        }
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
        $customer->user_id = $user->id;
        $customer->merchant_id = $request->merchant_id;
        $customer->address = $request->address;
        $customer->imei1 = $request->imei1;
        $customer->imei2 = $request->imei2;
        $customer->alter_mobile = $request->alter_mobile;
        $customer->coin_type = $request->coin_type;
        $customer->bank_name = $request->bank_name;
        $customer->account_name = $request->account_name;
        $customer->account_number = $request->account_number;
        $customer->ifsc = $request->ifsc;
        $customer->pin = $request->pin?? null;

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
                // $this->emi($data);
            }else {
                $loan->billing_amount = $request->billing_amount;
                $loan->file_charge = $request->file_charge;
                $loan->down_payment = $request->down_payment;
                $loan->loan_amount = $request->loan_amount;
                $loan->interest = $request->interest;
                $loan->month = $request->month;
                $loan->monthly_amount = $request->monthly_amount;
                $loan->first_emi_date = $request->first_emi_date;
                $loan->total_amount = $request->total_amount;
                $loan->total_interest = $request->total_interest;
                $loan->user_id = $user->id;
                $loan->merchant_id = auth()->user()->id;
                $loan->status = 1;
                $loan->lock_type = $request->lock_type ? $request->lock_type : 1;

                $loan->update();


                $loanDetails = LoanPayment::where('loan_id', $loan->id)->get();
                if($loanDetails){
                    LoanPayment::where('loan_id', $loan->id)->delete();
                    $j = ($request['month']);
                    $first_date = $request['first_emi_date'];
                    $amount = $request ['monthly_amount'];
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
        $customer->update();

        return redirect()->route('customer')->with('success','customer updated successfully');
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

    public function device($id)
    {
        $device = Device::where('user_id',$id)->first();
        if($device) {
            $url = env('DEVICE_URL') . $device->imei1;
            $response = CustomHelper::GetDeviceApi($url);
            if (!isset($response['error'])) {
                $data = $response['data'];
                return view('customer.device', ['data' => $data, 'status' => $device->status]);
            }
        }
        else{
            return redirect()->route('customer')->with('error','Device not found');
        }
    }

    public function deviceStatus(Request $request)
    {
        $url = env('DEVICE_URL').$request->imei1.'/'.$request->type;
        $response = CustomHelper::GetDeviceApi($url);
        if (!isset($response['error'])) {
            return response()->json(['success' => 'Device '.$request->type.' Successfully'], 200);
        }
        else{
            return response()->json(['error' => $response['error']], 400);
        }
    }
    public function delete($id)
    {
        $customer = Customer::with('device')->find($id);
        if ($customer) {
            $user = User::find($customer->user_id);
            $devices = Device::where('user_id',$customer->user_id)->get();
            foreach ($devices as $device) {
                print_r($device->imei1);
                $url = env('DEVICE_URL') . $customer->device[0]->imei1 . '/uninstall';
                $response = CustomHelper::GetDeviceApi($url);
                $device->delete();
            }
            $customer->delete();
            $user->delete();
            return redirect()->route('customer')->with('success','Customer deleted successfully');
        }
        else{
            return redirect()->route('customer')->with('error','Customer noy found');
        }
    }

}
