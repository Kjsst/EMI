<?php

namespace App\Http\Controllers;

use App\Exports\AccountExport;
use App\Models\Contact;
use App\Models\Merchant;
use App\Models\page;
use App\Models\Setting;
use App\Models\TransferCoins;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Device;
use App\Models\Customer;
use App\Models\CoinOffer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function contactPage(){
        $contacts = Contact::all();
        return view('contact',['contacts'=>$contacts]);
    }

    public function contact(Request $request)
        {
            $data = $request->except('_token');
            foreach ($data as $name => $value) {
                Contact::updateOrCreate(
                    ['name' => $name],
                    ['value' => $value]
                );
            }

            return redirect()->back()->with('success','contact detail updated successfully');
        }

    public function termsPage()
    {
        $content = page::where('slug', 'terms')->first();
        return view('page', ['content' => $content,'type'=>'terms']);
    }

    public function privacyPage()
    {
        $content = page::where('slug', 'privacy')->first();
        return view('page', ['content' => $content,'type'=>'privacy']);
    }

    public function aboutUsPage()
    {
        $content = page::where('slug', 'aboutUs')->first();
        return view('page', ['content' => $content,'type'=>'aboutUs']);
    }

    public function terms(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $name => $content) {
            page::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => $name,
                    'content' => $content
                ],

            );
        }
        return redirect()->back()->with('success', 'contact detail updated successfully');
    }

    public function privacy(Request $request)
    {
        $data = $request->except('_token');
        foreach ($data as $name => $content) {
            page::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => $name,
                    'content' => $content
                ],

            );
        }
        return redirect()->back()->with('success', 'contact detail updated successfully');
    }

    public function setting(){
         $datas = Setting::all();
         $setting =[];
         foreach ($datas as $set){
             $setting[$set->name]=$set->value;
         }
         return view('setting',['setting'=>$setting]);
    }

    public function storeSetting(Request $request){
         $data = $request->except('_token');
         foreach ($data as $name => $value) {
             if ($request->hasFile($name)) {
                 $file = $request->file($name);
                 $filename = "APK/" . time() . '.' . $file->getClientOriginalName();
                 $file->storeAs('images', $filename, 'public');
                 $value = $filename;
             }
             Setting::updateOrCreate(
                 ['name' => $name],
                 ['value' => $value]
             );
             echo "<pre>"; print_r($name);
             echo "<pre>"; print_r($value);
         }
         return redirect()->back()->with('success','setting saved successfully');
    }

    public function qrlSetting(){
         $setting = Setting::whereIn('id', [1, 2, 6])->get();

         return view('qrlsetting',['settings'=>$setting]);
     }

    public function qrlStoreSetting(Request $request){
        // $data = $request->except('_token');

        $brahmastra = Setting::where('id', 1)->first();
        $brahmastraPrime = Setting::where('id', 2)->first();
        $rambaan = Setting::where('id', 6)->first();

            $brahmastrafilename = null;
            if ($request->hasFile($brahmastra->name)) {
                $oldFile = $brahmastra->value;
                $file = $request->file($brahmastra->name);
                if ($oldFile) {
                    Storage::disk('public')->delete("images/".$oldFile);
                }
                $brahmastrafilename = "APK/".time() . '.' . $file->getClientOriginalName();
                $file->storeAs('images', $brahmastrafilename, 'public');
            }
            $brahmastraurl = $request->input($brahmastra->name.'_url');
            $brahmastra->name = $brahmastra->name;
            $brahmastra->value = $brahmastrafilename ? $brahmastrafilename : $brahmastra->value;
            $brahmastra->url = $brahmastraurl ? $brahmastraurl : null;
            $brahmastra->update();


            $brahmastraPrimefilename = null;
            if ($request->hasFile($brahmastraPrime->name)) {
                $oldFile = $brahmastraPrime->value;
                $file = $request->file($brahmastraPrime->name);
                if ($oldFile) {
                    Storage::disk('public')->delete("images/".$oldFile);
                }
                $brahmastraPrimefilename = "APK/".time() . '.' . $file->getClientOriginalName();
                $file->storeAs('images', $brahmastraPrimefilename, 'public');
            }
            $brahmastraPrimeurl = $request->input($brahmastraPrime->name.'_url');
            $brahmastraPrime->name = $brahmastraPrime->name;
            $brahmastraPrime->value = $brahmastraPrimefilename ? $brahmastraPrimefilename : $brahmastraPrime->value;
            $brahmastraPrime->url = $brahmastraPrimeurl ? $brahmastraPrimeurl : null;
            $brahmastraPrime->update();


            $rambaanfilename = null;
            if ($request->hasFile($rambaan->name)) {
                $oldFile = $rambaan->value;
                $file = $request->file($rambaan->name);
                if ($oldFile) {
                    Storage::disk('public')->delete("images/".$oldFile);
                }
                $rambaanfilename = "APK/".time() . '.' . $file->getClientOriginalName();
                $file->storeAs('images', $rambaanfilename, 'public');
            }
            $rambaanurl = $request->input($rambaan->name.'_url');
            $rambaan->name = $rambaan->name;
            $rambaan->value = $rambaanfilename ? $rambaanfilename : $rambaan->value;
            $rambaan->url = $rambaanurl ? $rambaanurl : null;
            $rambaan->update();


        return redirect()->back()->with('success','setting saved successfully');
    }

    public function coins(Request $request){
        $id = auth()->user()->id;
        $roles = Role::whereIn('id', [2, 4, 5])->get();
        $users = User::whereIn('role_id', [2, 4, 5])->where('id','!=',$id)->get();
        if (auth()->user()->hasRole('distributor')) {
            $merchantIds = Merchant::where('distributor_id',$id)->pluck('user_id');
            $roles = Role::where('id', 2)->get();
            $users = User::where('role_id', 2)->where('id','!=',$id)->whereIn('id',$merchantIds)->get();
        }
        elseif (auth()->user()->hasRole('master distributor')) {
            $distributorIds = UserDetail::where('parent_user_id',$id)->pluck('user_id');
//            $merchantIds = Merchant::whereIn('distributor_id',$distributorIds)->pluck('user_id');
//            $Ids = array_merge(json_decode($distributorIds),json_decode($merchantIds));
            $roles = Role::where('id',4)->get();
            $users = User::where('role_id', 4)->where('id','!=',$id)->whereIn('id',$distributorIds)->get();
        }
        $offers = CoinOffer::get();
        $balance = 0;
        if ($request->ajax())
        {
            if($request->role_id == 1) {
                $users = User::whereIn('role_id', [2, 4, 5])->where('id','!=',$id)->get();
            }else {
                $users = User::where('role_id', $request->role_id)->where('id', '!=', $id)->get();
            }

            if($request->coin_type) {
                $coin = User::where('id', $request->id)->first();
                if($request->coin_type == 'brahmastra_coin'){
                    $balance = $coin->brahmastra_coin;
                }elseif($request->coin_type == 'rambaan_coin') {
                    $balance = $coin->rambaan_coin;
                }
            }


            // $offers = CoinOffer::where('id', $request->id)->get();
            if($request->buy_coin) {
                $offers = CoinOffer::where('buy_coin', $request->buy_coin)->get();
            }


            if(auth()->user()->hasRole('distributor')) {
                $users = User::where('role_id', $request->role_id)->where('id', '!=', $id)->whereIn('id',$merchantIds)->get();
            }
            if(auth()->user()->hasRole('master distributor')) {
                $users = User::where('role_id', $request->role_id)->where('id', '!=', $id)->whereIn('id',$distributorIds)->get();
            }
            return response()->json([$users,$offers,$balance]);
        }

        return view('coins',compact('roles','users','offers','balance'));
    }

    public function credit(Request $request)
    {
        $request->validate([
            "user_id" => 'required',
            "coin_type" => 'required',
            "coins" => 'required',
            "remarks" => 'required',
        ]);
        $id = auth()->user()->id;
        $type = $request->coin_type;

        $transferCoinsUser = TransferCoins::where('from_user_id', $id)->orderBy('created_at', 'DESC')->first();
        $from_user_brahmastra_coin = 0;
        $from_user_rambaan_coin = 0;
        if($transferCoinsUser){
        if($transferCoinsUser->from_user_rambaan_coin != null && $type == 'rambaan_coin') {
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin - $request->coins;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin - $request->coins;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin;
            }
        }elseif($transferCoinsUser->from_user_brahmastra_coin != null && $type == 'brahmastra_coin') {
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin - $request->coins;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin - $request->coins;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin;
            }
        }else {
            $user = User::where('id', $id)->first();
            if($type == 'rambaan_coin') {
                $from_user_rambaan_coin = $user->rambaan_coin - $request->coins;
                $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin ? $transferCoinsUser->from_user_brahmastra_coin : NULL;
            }elseif ($type == 'brahmastra_coin'){
                $from_user_brahmastra_coin = $user->brahmastra_coin - $request->coins;
                $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin ? $transferCoinsUser->from_user_rambaan_coin : NULL;
            }
        }}


        $transferCoinsToUser = TransferCoins::where('to_user_id', $request->user_id)->orderBy('created_at', 'DESC')->first();
        $to_user_brahmastra_coin = 0;
        $to_user_rambaan_coin = 0;
        if($transferCoinsToUser){
        if($transferCoinsToUser->to_user_rambaan_coin != null && $type == 'rambaan_coin') {
            if($type == 'rambaan_coin') {
                $to_user_rambaan_coin = $transferCoinsToUser->to_user_rambaan_coin + $request->coins;
                $to_user_brahmastra_coin = $transferCoinsToUser->to_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $to_user_brahmastra_coin = $transferCoinsToUser->to_user_brahmastra_coin + $request->coins;
                $to_user_rambaan_coin = $transferCoinsToUser->to_user_rambaan_coin;
            }
        }elseif($transferCoinsToUser->to_user_brahmastra_coin != null && $type == 'brahmastra_coin') {
            if($type == 'rambaan_coin') {
                $to_user_rambaan_coin = $transferCoinsToUser->to_user_rambaan_coin + $request->coins;
                $to_user_brahmastra_coin = $transferCoinsToUser->to_user_brahmastra_coin;
            }elseif ($type == 'brahmastra_coin'){
                $to_user_brahmastra_coin = $transferCoinsToUser->to_user_brahmastra_coin + $request->coins;
                $to_user_rambaan_coin = $transferCoinsToUser->to_user_rambaan_coin;
            }
        }else {
            $user = User::where('id', $request->user_id)->first();
            // dd($user);
            if($type == 'rambaan_coin') {
                $to_user_rambaan_coin = $user->rambaan_coin + $request->coins;
                $to_user_brahmastra_coin = $transferCoinsToUser->to_user_brahmastra_coin ? $transferCoinsToUser->to_user_brahmastra_coin : NULL;
            }elseif ($type == 'brahmastra_coin'){
                $to_user_brahmastra_coin = $user->brahmastra_coin + $request->coins;
                $to_user_rambaan_coin = $transferCoinsToUser->to_user_rambaan_coin ? $transferCoinsToUser->to_user_rambaan_coin : NULL;
            }
        }}

        // Coin Offer
        $offerCoin = 0;
        $is_offer = false;

        $offers = CoinOffer::where('buy_coin', $request->coins)->first();

        // dd($request->buy_coin , $request->get_coin, $offer, $offer->id);

        if($offers){
            if($request->coins == $offers->buy_coin && $request->coin_type == $offers->coin_type) {
                $offerCoin = $offers->get_coin;
                $is_offer = true;
            }
        }

        $admin = User::where('id',1)->first();
        if($is_offer) {
            TransferCoins::create([
                'from_user_id'=>$admin->id,
                'to_user_id'=>$request->user_id,
                'coin_quantity'=>$offerCoin,
                'coin_type'=>$type,
                'from_user_rambaan_coin'=>$from_user_rambaan_coin ?? NULL,
                'from_user_brahmastra_coin'=>$from_user_brahmastra_coin ?? NULL,
                'to_user_rambaan_coin'=>$to_user_rambaan_coin ?? NULL,
                'to_user_brahmastra_coin'=>$to_user_brahmastra_coin ?? NULL,
                'remarks'=>"Added Extra Offer Coins",
                'type'=>"credit"
            ]);

            // $coin = $admin->$type;
            // if ($coin <= $offerCoin){
            //     return redirect()->back()->with('error',"You don't have enough coins");
            // }
            // $userCoin = $coin-$offerCoin;
            // User::where('id', $admin->id)->update([$type=>$userCoin]);
        }

        $user = User::find($id);
        if (!auth()->user()->hasRole('admin')){
            $coin = $user->$type;
            if ($coin <= $request->coins){
                return redirect()->back()->with('error',"You don't have enough coins");
            }
            $userCoin = $coin-$request->coins;
            User::where('id',$id)->update([$type=>$userCoin]);
        }
        $toUser = user::find($request->user_id);
        $transferCoin = $toUser->$type;
        $transferCoin = $transferCoin + $request->coins + $offerCoin;
        User::where('id',$request->user_id)->update([$type=>$transferCoin]);
        TransferCoins::create([
            'from_user_id'=>$id,
            'to_user_id'=>$request->user_id,
            'coin_quantity'=>$request->coins,
            'coin_type'=>$type,
            'from_user_rambaan_coin'=>$from_user_rambaan_coin ?? NULL,
            'from_user_brahmastra_coin'=>$from_user_brahmastra_coin ?? NULL,
            'to_user_rambaan_coin'=>$to_user_rambaan_coin ?? NULL,
            'to_user_brahmastra_coin'=>$to_user_brahmastra_coin ?? NULL,
            'remarks'=>$request->remarks,
            'type'=>"credit"
        ]);
        return redirect()->back()->with('success','coin credited successfully');
    }

    public function debit(Request $request)
    {
        $request->validate([
            "from_user_id" => 'required',
            "from_coin_type" => 'required',
            "from_coins" => 'required',
            "from_remarks" => 'required',
        ],
        [
            "from_user_id.required"=>"The user field is required",
            "from_coin_type.required"=>"The coin type field is required",
            "from_coins.required"=>"The coin field is required",
            "from_remarks.required"=>"The remarks field is required",
        ]);
        $id = auth()->user()->id;
        $type = $request->from_coin_type;

        // $transferCoinToUser = TransferCoins::where('to_user_id', $id)->orderBy('created_at', 'DESC')->first();
        // // dd($transferCoinToUser);
        $to_user_brahmastra_coin = 0;
        $to_user_rambaan_coin = 0;
        // if($transferCoinToUser->to_user_rambaan_coin != null && $type == 'rambaan_coin'){
        //     if($type == 'rambaan_coin') {
        //         $to_user_rambaan_coin = $transferCoinToUser->to_user_rambaan_coin - $request->coins;
        //         $to_user_brahmastra_coin = $transferCoinToUser->to_user_brahmastra_coin;
        //     }elseif ($type == 'brahmastra_coin'){
        //         $to_user_brahmastra_coin = $transferCoinToUser->to_user_brahmastra_coin - $request->coins;
        //         $to_user_rambaan_coin = $transferCoinToUser->to_user_rambaan_coin;
        //     }
        // }elseif($transferCoinToUser->to_user_brahmastra_coin != null && $type == 'brahmastra_coin'){
        //     if($type == 'rambaan_coin') {
        //         $to_user_rambaan_coin = $transferCoinToUser->to_user_rambaan_coin - $request->coins;
        //         $to_user_brahmastra_coin = $transferCoinToUser->to_user_brahmastra_coin;
        //     }elseif ($type == 'brahmastra_coin'){
        //         $to_user_brahmastra_coin = $transferCoinToUser->to_user_brahmastra_coin - $request->coins;
        //         $to_user_rambaan_coin = $transferCoinToUser->to_user_rambaan_coin;
        //     }
        // }else {
        //     $user = User::where('id', $id)->first();
        //     if($type == 'rambaan_coin') {
        //         $from_user_rambaan_coin = $user->rambaan_coin - $request->coins;
        //         $from_user_brahmastra_coin = $transferCoinsUser->from_user_brahmastra_coin ? $transferCoinsUser->from_user_brahmastra_coin : NULL;
        //     }elseif ($type == 'brahmastra_coin'){
        //         $from_user_brahmastra_coin = $user->brahmastra_coin - $request->coins;
        //         $from_user_rambaan_coin = $transferCoinsUser->from_user_rambaan_coin ? $transferCoinsUser->from_user_rambaan_coin : NULL;
        //     }
        // }

        // $transferCoinsUser = TransferCoins::where('from_user_id', $request->user_id)->orderBy('created_at', 'DESC')->first();
        $from_user_brahmastra_coin = 0;
        $from_user_rambaan_coin = 0;


        $user = User::find($request->from_user_id);
        $coin = $user->$type;
        if ($request->from_coins >= $coin){
            return redirect()->back()->with('error',"User don't have enough coins");
        }
        if (!auth()->user()->hasRole('admin')){
            $userCoin = $coin+$request->from_coins;
            User::where('id',$id)->update([$type=>$userCoin]);
        }

        $toUser = user::find($request->from_user_id);
        $transferCoin = $toUser->$type;
        $transferCoin = $transferCoin - $request->from_coins;
        User::where('id',$request->from_user_id)->update([$type=>$transferCoin]);
        TransferCoins::create([
            'to_user_id'=>$id,
            'coin_quantity'=>$request->from_coins,
            'from_user_id'=>$request->from_user_id,
            'coin_type'=>$type,
            'from_user_rambaan_coin'=>$from_user_rambaan_coin ?? NULL,
            'from_user_brahmastra_coin'=>$from_user_brahmastra_coin ?? NULL,
            'to_user_rambaan_coin'=>$to_user_rambaan_coin ?? NULL,
            'to_user_brahmastra_coin'=>$to_user_brahmastra_coin ?? NULL,
            'remarks'=>$request->from_remarks,
            'type'=>"debit"
        ]);
        return redirect()->back()->with('success','coin debited successfully');
    }

    public function customerReport(Request $request)
    {
        $id = auth()->user()->id;
        $query = TransferCoins::with('fromUser', 'toUser');
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));
        $filter = $request->input('filter', 'all');

        // Filter by dates
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();
        $query->whereBetween('created_at', [$fromDate, $toDate]);

        // Filter by type if provided
        if ($filter != 'all') {
            $query->where('type', $filter);
        }

        // Restrict results based on user role
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('employee')) {
            $query->where(function ($query) use ($id) {
                $query->where('from_user_id', $id)
                    ->orWhere('to_user_id', $id);
            });
        }

        $users = $query->orderBy('created_at', 'DESC')->get();
        // $userIds = $users->pluck('to_user_id');
        // $balances = User::whereIn('id', $userIds)->select('id', 'brahmastra_coin', 'rambaan_coin')->get();
        $data = [];
        foreach ($users as $user){
            $to_user_balances = User::where('id', $user->to_user_id)->select('id', 'brahmastra_coin', 'rambaan_coin')->get();
            $from_user_balances = User::where('id', $user->from_user_id)->select('id', 'brahmastra_coin', 'rambaan_coin')->get();

            $fromusers = $users->pluck('from_user_id');
            $getfromuser = User::where('id', $user->from_user_id)->get();

            $balance_to_user = 0;
            $balance_from_user = 0;
            $parent = '-';

            foreach ($getfromuser as $fromuser){
                if ($user->from_user_id == $fromuser->id){
                    if($fromuser->role_id == 1) {
                        //  1 for admin user
                        $parent = '-';
                    }elseif ($fromuser->role_id == 2) {
                        //  2 for merchant user
                        $merchants = Merchant::with('Distributor','user')->where('user_id',$fromuser->id)->get();
                        foreach ($merchants as $merchant){
                            $parent = $merchant->Distributor->name;
                        }
                    }elseif ($fromuser->role_id == 4) {
                        //  4 for distributor user
                        $ids = User::where('role_id', $fromuser->role_id)->pluck('id');
                        $distributors = UserDetail::with('ParentUser','user')->whereIn('user_id',$ids)->get();
                        foreach ($distributors as $distributor){
                            $parent = $distributor->ParentUser->name;
                        }
                    }elseif ($fromuser->role_id == 5) {
                        //  5 for master distributor user
                        $parent = '-';
                    }
                }
            }


            foreach ($to_user_balances as $bal){
                if ($user->to_user_id == $bal->id){
                    if($user->coin_type == 'brahmastra_coin') {
                        $balance_to_user = $bal->brahmastra_coin;
                    }elseif ($user->coin_type == 'brahmastra_prime_coin') {
                        $balance_to_user = $bal->brahmastra_coin;
                    }else {
                        $balance_to_user = $bal->rambaan_coin;
                    }
                }
            }

            foreach ($from_user_balances as $bal){
                if($user->from_user_id == $bal->id) {
                    if($user->coin_type == 'brahmastra_coin') {
                        $balance_from_user = $user->from_user_brahmastra_coin ? $user->from_user_brahmastra_coin : $bal->brahmastra_coin;
                    }elseif ($user->coin_type == 'brahmastra_prime_coin') {
                        $balance_from_user = $user->from_user_brahmastra_coin ? $user->from_user_brahmastra_coin : $bal->brahmastra_coin;
                    }else {
                        $balance_from_user = $user->from_user_rambaan_coin ? $user->from_user_rambaan_coin : $bal->rambaan_coin;
                    }
                }
            }

            $data[] = [
                'id'                => $user->id,
                'from_user_id'      => $user->fromUser->name ?? '-',
                'to_user_id'        => $user->toUser->name ?? '-',
                'parent'            => $parent ?? '-',
                'coin_quantity'     => $user->coin_quantity,
                'coin_type'         => $user->coin_type,
                'type'              => $user->type,
                'remarks'           => $user->remarks,
                'balance_to_user'   => $balance_to_user ?? '-',
                'balance_from_user' => $balance_from_user ?? '-',
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
            ];
        }

        return view('report.customer-report', compact('data', 'from', 'to','filter'));
    }
    public function export(Request $request)
    {
        $from = $request->input('from', Carbon::now()->format('Y-m-d'));
        $to = $request->input('to', Carbon::now()->format('Y-m-d'));
        $filter = $request->input('filter', 'all');

        return Excel::download(new AccountExport($from, $to, $filter), 'accountHistory.xlsx');
    }

    public function devicesList(Request $request)
    {
        return view('device');
    }

    public function devicesAjaxList(Request $request): JsonResponse
    {
        $nullDevices = DB::table('devices')->whereNull('user_id')->orderByDesc('id')->limit(150)->get();
        $imeiNumbers = $nullDevices->pluck('imei1');
        if(!empty($imeiNumbers)) {
            $customers = DB::table('customers')->whereIn('imei1', $imeiNumbers)->pluck('user_id', 'imei1');
            $updateData = [];
            foreach ($nullDevices as $device) {
                if (isset($customers[$device->imei1])) {
                    $updateData[] = [
                        'id' => $device->id,
                        'user_id' => $customers[$device->imei1],
                    ];
                }
            }
        }

        foreach ($updateData as $data) {
            DB::table('devices')->where('id', $data['id'])->update(['user_id' => $data['user_id']]);
        }

        // Preload customers with their relations for easier access
        $customers = Customer::with('merchant')->get()->keyBy('user_id');  // Efficient lookup by user_id

        // Start building the query for Device model
        $query = Device::with('user')->orderBy('id', 'desc');

        // Apply filter for device status if provided
        $filter = $request->input('drive_status', 'all');
        if ($filter !== 'all') {
            if ($filter === 'enrolled') {
                // Special filter for "enrolled" status
                $query->whereIn('status', [2, 3]);
            } else {
                // Apply other device status filter
                $query->where('status', $filter);
            }
        }


        $user_id = $request->input('user_id');
        if ($user_id != '') {
            $query->where('user_id', $user_id);
        }

        $search = $request->input('name', ''); // Search input from DataTables
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }


        $imei1 = $request->input('imei1', ''); // Search input from DataTables
        if ($imei1) {
            $query->where('imei1', 'like', '%' . $imei1 . '%');
        }

        $imei2 = $request->input('imei2', ''); // Search input from DataTables
        if ($imei2) {
            $query->where('imei2', 'like', '%' . $imei2 . '%');
        }

        // Save the original query for filtered count (without pagination)
        $filteredQuery = clone $query;

        // Get pagination parameters from DataTables
        $length = $request->input('length', 10);  // Number of records per page
        $start = $request->input('start', 0);     // Offset (start) for pagination

        // Apply pagination
        $query->skip($start)->take($length);

        // Get the filtered data
        $devices = $query->get();

        // Prepare the response data for DataTables
        $users = $devices->map(function ($device) use ($customers) {

            $merchantData = $customers->get($device->user_id);
            $merchant_name = $merchantData ? $merchantData->merchant->name ?? '-' : '-';
            $merchant_id = $merchantData ? $merchantData->merchant->id ?? '-' : '-';

            return [
                'id' => $device->id,
                'user_name' => $device->user->name ?? '-',
                'merchant_name' => $merchant_name,
                'merchant_id' => $merchant_id,
                'imei1' => $device->imei1,
                'imei2' => $device->imei2,
                'model' => $device->model,
                'manufacturer' => $device->manufacturer,
                'mobile_one' => $device->mobile_one,
                'mobile_one_network' => $device->mobile_one_network,
                'mobile_two' => $device->mobile_two,
                'mobile_two_network' => $device->mobile_two_network,
                'status' => $device->status,
                'created_at' => $device->created_at, // Format the date
            ];
        });

        // Get the total count of records after filtering (no pagination applied here)
        $recordsFiltered = $filteredQuery->count();

        // Return data in the format required by DataTables
        return response()->json([
            'draw' => $request->input('draw'),  // Draw counter to maintain state
            'recordsTotal' => Device::count(),  // Total records in the database (no filters)
            'recordsFiltered' => $recordsFiltered,  // Records after filtering
            'data' => $users,  // The actual data
        ]);
    }
}
