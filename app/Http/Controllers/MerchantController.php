<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MerchantController extends Controller
{
    public function index(){
        $merchants = Merchant::with('Distributor','user')->where('distributor_id',auth()->user()->id)->orderby('id', 'desc')->get();

        if (auth()->user()->hasRole('admin')) {
            $merchants = Merchant::with('Distributor','user')->orderby('id', 'desc')->get();
            // dd($merchants);
        }
        return view('merchant.index', [
            'merchants' => $merchants,
        ]);
    }
    public function create(){
        $distributors = User::where('role_id',4)->get();
        return view('merchant.create',['distributors'=>$distributors]);
    }
    public function view($id){
        $merchant = Merchant::with('user')->where('user_id',$id)->first();
        return view('merchant.view',['merchant'=>$merchant]);
    }
    public function edit($id){
        $merchant = Merchant::with('user')->where('user_id',$id)->first();
        $distributors = User::where('role_id',4)->get();
        return view('merchant.edit', [
            'merchant' => $merchant,
            'distributors' => $distributors,
        ]);
    }
    public function users($id){
        $customers = Customer::with('user')->where('merchant_id',$id)->get();
        $distributors = User::where('role_id',4)->get();
        return view('merchant.user', [
            'customers' => $customers
        ]);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'shop_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'password' => 'required',
        ]);
        if ($request->hasFile('QR_image')) {
            $file = $request->file('QR_image');
            $filename = "QR_image/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $request['QRImage'] = $filename;
        }
        $user = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'mobile' => $request['mobile'],
            'email' => $request['email'],
            'role_id' => 2,
            'status' => 1,
            'password' => Hash::make($request['password']),
            'password_text' => $request['password'],
            'brahmastra_coin' => $request['brahmastra_coin']??0,
            'rambaan_coin' => $request['ramnaan_coin']??0,
        ])->assignRole('merchant');

        Merchant::create([
            'user_id' => $user->id,
            'distributor_id' => auth()->user()->id,
            'frp_email' => $request['frp_email']??null,
            'shop_name' => $request['shop_name'],
            'address' => $request['address'],
            'city' => $request['city'],
            'postal_code' => $request['postal_code'],
            'bank_name' => $request['bank_name'],
            'account_name' => $request['account_name'],
            'account_number' => $request['account_number'],
            'ifsc' => $request['ifsc'],
            'upi_id' => $request['upi_id']??null,
            'QR_image' => $request['QRImage']??null,
        ]);

        return redirect()->route('merchant')->with('success','merchant created successfully');
    }
    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $request->user_id . '|max:255,',
            'email' => 'required|unique:users,email,' . $request->user_id . '|max:255,',
            'mobile' => 'required|unique:users,mobile,'. $request->user_id,
            'shop_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
        ]);

        $user = User::where('id',$request->user_id)->first();
        $user->name = $request['name'];
        $user->username = $request['username'];
        $user->mobile = $request['mobile'];
        $user->email = $request['email'];
        $user->brahmastra_coin = $request['brahmastra_coin'] ? $request['brahmastra_coin'] : $user->brahmastra_coin;
        $user->rambaan_coin = $request['rambaan_coin'] ? $request['rambaan_coin'] : $user->rambaan_coin;
        if($request->password) {
            $user->password = Hash::make($request['password']);
            $user->password_text = $request['password'];
        }
        $user->update();

        $merchant = Merchant::where('id',$id)->first();
        $oldFile = $merchant->QR_image;
        if ($request->hasFile('QR_image')) {
            $file = $request->file('QR_image');
            if ($oldFile) {
                Storage::disk('public')->delete("images/".$oldFile);
            }
            $filename = "QR_image/".time() . '.' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $merchant->QR_image = $filename;
        }
        $merchant->user_id = $user->id;
        $merchant->shop_name = $request['shop_name'];
        $merchant->address = $request['address'];
        $merchant->city = $request['city'];
        $merchant->postal_code = $request['postal_code'];
        $merchant->bank_name = $request['bank_name'];
        $merchant->account_name = $request['account_name'];
        $merchant->account_number = $request['account_number'];
        $merchant->ifsc = $request['ifsc'];
        $merchant->upi_id = $request['upi_id'];
        $merchant->distributor_id = $request['distributor_id']??auth()->user()->id;
        $merchant->update();

        return redirect()->route('merchant')->with('success','merchant updated successfully');
    }

    public function searchMerchant(Request $request) {
        $term = $request->input('term');
        $customers = Customer::with('merchant')
            ->when($term, function ($query) use ($term) {
                $query->whereHas('merchant', function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%');
                });
            })
            ->get()  // Get the data first with all relationships
            ->mapWithKeys(function ($customer) {
                // Concatenate merchant's name and email (or any contact-related fields)
                $contact = $customer->merchant ? $customer->merchant->name . '(' . $customer->merchant->email.')' : null;

                // Return the user_id as the key and name_email as the value directly in a key-value pair format
                return [$customer->user_id => $contact];
            });
        return response()->json($customers);
    }
}
