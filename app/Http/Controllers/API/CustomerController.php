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
use App\Models\TollFreeNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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

    public function dashboard()
    {
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $deviceEnrolled = Device::whereIn('user_id',$ids)->get();
        $deviceInactive = Device::whereIn('user_id',$ids)->where('status','5')->get();
        $deviceLocked = Device::whereIn('user_id',$ids)->where('status','3')->get();
        $deviceUnLocked = Device::whereIn('user_id',$ids)->where('status','2')->get();
        $user = User::find($id);

        $loanDownPayment = Loan::whereIn('user_id',$ids)->sum('down_payment');
        $loanAmount = Loan::whereIn('user_id',$ids)->sum('loan_amount');
        $fileCharge = Loan::whereIn('user_id',$ids)->sum('file_charge');
        $totalLoanSale = $loanDownPayment + $loanAmount + $fileCharge;
        $totalLoan = $loanAmount + $loanDownPayment;

        $loanPendingPayment = LoanPayment::whereIn('user_id',$ids)->where('due_date','<',now())->where('paid_amount',null)->where('paid_by_user',null)->sum('amount');
        $loanReceivePayment = LoanPayment::whereIn('user_id',$ids)->where('due_date','>',now())->where('paid_amount','!=',null)->where('paid_by_user','!=',null)->sum('paid_amount');
        $loanOverduePayment = LoanPayment::whereIn('user_id',$ids)->where('due_date',"<",now())->where('paid_amount',null)->where('paid_date',null)->where('status','!=','Paid')->sum('amount');
        $loanSale = LoanPayment::whereIn('user_id',$ids)->sum('amount');

        $loanPendingPaymentList = LoanPayment::whereIn('user_id',$ids)->where('due_date','<',now())->where('paid_amount',null)->where('paid_by_user',null)->get();
        $loanReceivePaymentList = LoanPayment::whereIn('user_id',$ids)->where('due_date','>',now())->where('paid_amount','!=',null)->where('paid_by_user','!=',null)->get();
        $loanOverduePaymentList = LoanPayment::whereIn('user_id',$ids)->where('due_date',"<",now())->where('paid_amount',null)->where('paid_date',null)->where('status','!=','Paid')->get();

        $tollFreeNumber = TollFreeNumber::select('toll_free_number')->get()->pluck('toll_free_number');
        $brahmastra = Setting::where('id', 1)->get();
        $brahmastraPrime = Setting::where('id', 2)->get();
        $rambaan = Setting::where('id', 6)->get();

        $brahmastraData = [];
        foreach ($brahmastra as $brahm){
            $brahmastraData[] = [
                'id'            => $brahm->id,
                'name'          => $brahm->name,
                'value'         => url('/storage/images/'.$brahm->value),
                'url'           => $brahm->url,
                'created_at'    => $brahm->created_at,
                'updated_at'    => $brahm->updated_at,
            ];
        }

        $brahmastraPrimeData = [];
        foreach ($brahmastraPrime as $brahmPrime){
            $brahmastraPrimeData[] = [
                'id'            => $brahmPrime->id,
                'name'          => $brahmPrime->name,
                'value'         => url('/storage/images/'.$brahmPrime->value),
                'url'           => $brahmPrime->url,
                'created_at'    => $brahmPrime->created_at,
                'updated_at'    => $brahmPrime->updated_at,
            ];
        }

        $rambaanData = [];
        foreach ($rambaan as $ram){
            $rambaanData[] = [
                'id'            => $ram->id,
                'name'          => $ram->name,
                'value'         => url('/storage/images/'.$ram->value),
                'url'           => $ram->url,
                'created_at'    => $ram->created_at,
                'updated_at'    => $ram->updated_at,
            ];
        }

        // $tollFreeNumber = [1245369875,7854369851,4563217895,7854120369];

        if ($user){
            return response()->json([
                'message' => 'Dashboard Data',
                'brahmastra_coin'=>$user->brahmastra_coin,
                'rambaan_coin'=>$user->rambaan_coin,
                'deviceEnrolled'=>$deviceEnrolled,
                'deviceInactive'=>$deviceInactive,
                'deviceLocked'=>$deviceLocked,
                'deviceUnLocked'=>$deviceUnLocked,
                'totalLoanSale'=>$totalLoanSale??0,
                'totalLoan'=>$totalLoan??0,
                'loanDownPayment'=>$loanDownPayment??0,
                'loanPendingPayment'=>$loanPendingPayment??0,
                'loanReceivePayment'=>$loanReceivePayment??0,
                'loanOverduePayment'=>$loanOverduePayment??0,
                'loanPendingPaymentList'=>$loanPendingPaymentList??0,
                'loanReceivePaymentList'=>$loanReceivePaymentList??0,
                'loanOverduePaymentList'=>$loanOverduePaymentList??0,
                'loanSale'=>$loanSale??0,
                'toll_free_number'=>$tollFreeNumber,
                'brahmastra'=>$brahmastraData,
                'brahmastra_prime'=>$brahmastraPrimeData,
                'rambaan'=>$rambaanData,
            ], 200);
        }
        return response()->json(['message' => 'Data not found',], 422);
    }

    public function dashboardWithQuery($name)
    {
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $deviceEnrolled = Device::whereIn('user_id',$ids)->get();
        $deviceInactive = Device::whereIn('user_id',$ids)->where('status','5')->get();
        $deviceLocked = Device::whereIn('user_id',$ids)->where('status','3')->get();
        $deviceUnLocked = Device::whereIn('user_id',$ids)->where('status','2')->get();
        $user = User::find($id);

        $loanDownPayment = Loan::whereIn('user_id',$ids)->sum('down_payment');
        $loanPendingPayment = LoanPayment::whereIn('user_id',$ids)->where('due_date','<',now())->where('paid_amount',null)->where('paid_by_user',null)->sum('amount');
        $loanReceivePayment = LoanPayment::whereIn('user_id',$ids)->where('due_date','>',now())->where('paid_amount','!=',null)->where('paid_by_user','!=',null)->sum('paid_amount');
        $loanSale = LoanPayment::whereIn('user_id',$ids)->sum('amount');
        $tollFreeNumber = TollFreeNumber::select('toll_free_number')->get()->pluck('toll_free_number');
        $brahmastra = Setting::where('id', 1)->get();
        $brahmastraPrime = Setting::where('id', 2)->get();
        $rambaan = Setting::where('id', 6)->get();

        $brahmastraData = [];
        foreach ($brahmastra as $brahm){
            $brahmastraData[] = [
                'id'            => $brahm->id,
                'name'          => $brahm->name,
                'value'         => url('/storage/images/'.$brahm->value),
                'url'           => $brahm->url,
                'created_at'    => $brahm->created_at,
                'updated_at'    => $brahm->updated_at,
            ];
        }

        $brahmastraPrimeData = [];
        foreach ($brahmastraPrime as $brahmPrime){
            $brahmastraPrimeData[] = [
                'id'            => $brahmPrime->id,
                'name'          => $brahmPrime->name,
                'value'         => url('/storage/images/'.$brahmPrime->value),
                'url'           => $brahmPrime->url,
                'created_at'    => $brahmPrime->created_at,
                'updated_at'    => $brahmPrime->updated_at,
            ];
        }

        $rambaanData = [];
        foreach ($rambaan as $ram){
            $rambaanData[] = [
                'id'            => $ram->id,
                'name'          => $ram->name,
                'value'         => url('/storage/images/'.$ram->value),
                'url'           => $ram->url,
                'created_at'    => $ram->created_at,
                'updated_at'    => $ram->updated_at,
            ];
        }

        // $tollFreeNumber = [1245369875,7854369851,4563217895,7854120369];

        if ($user){
            // For Lock Device
            if ($name == "Lock"){
                return response()->json([
                    'message' => 'Dashboard Data',
                    'brahmastra_coin'=>$user->brahmastra_coin,
                    'rambaan_coin'=>$user->rambaan_coin,
                    'deviceLocked'=>$deviceLocked,
                    'loanDownPayment'=>$loanDownPayment??0,
                    'loanPendingPayment'=>$loanPendingPayment??0,
                    'loanReceivePayment'=>$loanReceivePayment??0,
                    'loanSale'=>$loanSale??0,
                    'toll_free_number'=>$tollFreeNumber,
                    'brahmastra'=>$brahmastraData,
                    'brahmastra_prime'=>$brahmastraPrimeData,
                    'rambaan'=>$rambaanData,
                ], 200);
            }
            // For Unlock Device
            if ($name == "Unlock"){
                return response()->json([
                    'message' => 'Dashboard Data',
                    'brahmastra_coin'=>$user->brahmastra_coin,
                    'rambaan_coin'=>$user->rambaan_coin,
                    'deviceUnLocked'=>$deviceUnLocked,
                    'loanDownPayment'=>$loanDownPayment??0,
                    'loanPendingPayment'=>$loanPendingPayment??0,
                    'loanReceivePayment'=>$loanReceivePayment??0,
                    'loanSale'=>$loanSale??0,
                    'toll_free_number'=>$tollFreeNumber,
                    'brahmastra'=>$brahmastraData,
                    'brahmastra_prime'=>$brahmastraPrimeData,
                    'rambaan'=>$rambaanData,
                ], 200);
            }

            // For Inactive Device
            if ($name == "Inactive"){
                return response()->json([
                    'message' => 'Dashboard Data',
                    'brahmastra_coin'=>$user->brahmastra_coin,
                    'rambaan_coin'=>$user->rambaan_coin,
                    'deviceInactive'=>$deviceInactive,
                    'loanDownPayment'=>$loanDownPayment??0,
                    'loanPendingPayment'=>$loanPendingPayment??0,
                    'loanReceivePayment'=>$loanReceivePayment??0,
                    'loanSale'=>$loanSale??0,
                    'toll_free_number'=>$tollFreeNumber,
                    'brahmastra'=>$brahmastraData,
                    'brahmastra_prime'=>$brahmastraPrimeData,
                    'rambaan'=>$rambaanData,
                ], 200);
            }

            // For Enrolment Device
            if ($name == "Enrolment"){
                return response()->json([
                    'message' => 'Dashboard Data',
                    'brahmastra_coin'=>$user->brahmastra_coin,
                    'rambaan_coin'=>$user->rambaan_coin,
                    'deviceEnrolled'=>$deviceEnrolled,
                    'loanDownPayment'=>$loanDownPayment??0,
                    'loanPendingPayment'=>$loanPendingPayment??0,
                    'loanReceivePayment'=>$loanReceivePayment??0,
                    'loanSale'=>$loanSale??0,
                    'toll_free_number'=>$tollFreeNumber,
                    'brahmastra'=>$brahmastraData,
                    'brahmastra_prime'=>$brahmastraPrimeData,
                    'rambaan'=>$rambaanData,
                ], 200);
            }
        }
        return response()->json(['message' => 'Data not found',], 422);
    }

    public function filterDevice($id)
    {
        $id = auth()->user()->id;
        $ids = Customer::where('merchant_id',$id)->pluck('user_id');
        $device = Device::whereIn('user_id',$ids)->where('status',$id)->get();
            if ($device){
                return response()->json(['message' => 'Device found','data'=>$device], 200);
            }
        return response()->json(['message' => 'Device not found'], 422);
    }
    public function device(){
        $id = auth()->user()->id;

        $device = Device::select('imei1','imei2','user_id','status')->where('user_id',$id)->first();
        if ($device){
            return response()->json(['message' => 'Device found','data'=>$device], 200);
        }
        return response()->json(['message' => 'Device Not found',], 422);
    }

    public function handleCallback(Request $request)
    {
        // Retrieve the expected key from the environment
        $expectedKey = env('CALLBACK_SECRET_KEY');
        $headerKey = $request->header('X-Auth-Key');
        // Check if the header key is present and matches the expected key
        if ($headerKey !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Process the request data (for example, log it or perform some action)
        $data = $request->all();
        $syncAtFormatted = Carbon::parse($data['data']['sync_at'])->format('Y-m-d H:i:s');

        Log::info('Callback data received', $data);
        Log::info('----------------------------------------------');
        $device = Device::where('imei1', $data['data']['imei_one'])->first();
        if ($data['notification_type'] == "ENROLLED" && !$device){
            $deviceDetail=[
                'imei1' => $data['data']['imei_one'],
                'imei2' => $data['data']['imei_two'],
                'model' => $data['data']['model'],
                'manufacturer' => $data['data']['manufacturer'],
                'latitude' => $data['data']['latitude'],
                'longitude' => $data['data']['longitude'],
                'mobile_one' => $data['data']['mobile_one'],
                'mobile_one_network' => $data['data']['mobile_one_network'],
                'mobile_two' => $data['data']['mobile_two'],
                'mobile_two_network' => $data['data']['mobile_two_network'],
                'sync_at' => $syncAtFormatted,
                'zt_status' => $data['data']['zt_status'],
                'action_status' => $data['data']['action_status'],
                'type' => $data['data']['type'],
                'status' => $data['data']['status'],
                'notification_type' => $data['notification_type'],
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ];
            Device::create($deviceDetail);
        }
        else {
            if($device) {
                $device->imei2 = $data['data']['imei_two'];
                $device->model = $data['data']['model'];
                $device->manufacturer = $data['data']['manufacturer'];
                $device->latitude = $data['data']['latitude'];
                $device->longitude = $data['data']['longitude'];
                $device->mobile_one = $data['data']['mobile_one'];
                $device->mobile_one_network = $data['data']['mobile_one_network'];
                $device->mobile_two = $data['data']['mobile_two'];
                $device->mobile_two_network = $data['data']['mobile_two_network'];
                $device->sync_at = $syncAtFormatted;
                $device->zt_status = $data['data']['zt_status'];
                $device->action_status = $data['data']['action_status'];
                $device->type = $data['data']['type'];
                $device->status = $data['data']['status'];
                $device->notification_type = $data['notification_type'];
                $device->update();
            }
        }
//         Log::info('Callback data received', $data);
//         Log::info('device detail', $device);
        // Respond to the request
        return response()->json(['message' => 'Callback received successfully', 'data' => $data], 200);
    }

    public function wallet()
    {
        $id = auth()->user()->id;
        $merchant = Merchant::where('user_id',$id)->first();
        if ($merchant){
            return response()->json(['message' => 'Wallet amount','data'=>$merchant], 200);
        }
        return response()->json(['message' => 'Detail Not found',], 422);
    }

}