<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::whereIn('id',[4,5,6,7])->get();
        $users = User::where('role_id',5)->get();
        return view('user.create',compact('roles','users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'role'=>'required',
            'password'=>'required',
        ]);
        if ($request->role == 4){
            $request->validate([
                'parent_user_id' => 'required',
            ]);
        }
        $companyName = Role::where('id',$request->role)->pluck('name');
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'role_id' => $request->role,
            'status' => 1,
            'password' => Hash::make($request->password),
            'password_text' => $request->password,
            'brahmastra_coin' => 0,
            'rambaan_coin' => 0,
        ])->assignRole($companyName);

        UserDetail::create([
            'user_id' => $user->id,
            'parent_user_id' => $request->parent_user_id?? null,
            'address' => $request->address?? null,
            'city' => $request->city?? null,
            'company_name' => $request->company_name?? null,
            'postal_code' => $request->postal_code?? null,
        ]);
        return redirect()->back()->with('success','user created successfully');
    }
    public function masterDistributor()
    {
        $MasterDistributors = User::where('role_id',5)->get();
        return view('user.masterDistributor.masterDistributor',compact('MasterDistributors'));
    }
    public function distributors()
    {
        $distributors = UserDetail::with('ParentUser','user')->where('parent_user_id',auth()->user()->id)->orderby('id', 'desc')->get();
        if (auth()->user()->hasRole('admin')) {
            $ids = User::where('role_id', 4)->pluck('id');
            $distributors = UserDetail::with('ParentUser','user')->whereIn('user_id',$ids)->orderby('id', 'desc')->get();
        }
        return view('user.distributor.distributor',compact('distributors'));
    }

    public function distributorsAjaxList(Request $request)
    {
        // Start the query for UserDetail
        $query = UserDetail::with('ParentUser', 'user')
            ->where('parent_user_id', auth()->user()->id)
            ->orderby('id', 'desc');

        // Check if the logged-in user is an admin
        if (auth()->user()->hasRole('admin')) {
            // Get the IDs of users with role_id 4
            $ids = User::where('role_id', 4)->pluck('id');
            // Apply a filter based on those user IDs
            $query = UserDetail::with('ParentUser', 'user')
                ->whereIn('user_id', $ids)
                ->orderby('id', 'desc');
        }

        // Get pagination parameters from DataTables request
        $length = $request->input('length', 10);  // Number of records per page
        $start = $request->input('start', 0);     // Start record for pagination

        // Apply pagination to the query
        $distributors = $query->skip($start)->take($length)->get();  // Get the paginated results

        // Calculate the total number of records (before any filtering)
        $totalRecords = UserDetail::count();

        // Calculate the number of filtered records (after applying the filters)
        $filteredRecords = $query->count();

        // Prepare the data to be returned to DataTables
        $data = $distributors->map(function ($distributor) {

            $viewUrl = route('distributor.view', $distributor->user->id);
            $editUrl = route('distributor.edit', $distributor->user->id);
            $usersUrl = route('distributor.users', $distributor->user->id);

            // Generate the action buttons HTML
            $actionButtons = '
                <a href="' . $viewUrl . '"> <img src="./assets/img/eye.svg" alt="View"></a>
                <a href="' . $editUrl . '"> <img src="./assets/img/edit.svg" alt="Edit"></a>
                <a href="' . $usersUrl . '"> <img src="./assets/img/Categories.png" alt="Users"></a>
            ';


            return [
                'id' => $distributor->id,
                'user_name' => $distributor->user->name ?? '-',
                'username' => $distributor->user->username ?? '-',
                'email' => $distributor->user->email ?? '-',
                'parent' => $distributor->ParentUser->name ?? '-',
                'mobile' => $distributor->user->mobile ?? '-',
                'rambaan_coin' => $distributor->user->rambaan_coin ?? '-',
                'brahmastra_coin' => $distributor->user->brahmastra_coin ?? '-',
                'created_at' => $distributor->user->created_at->format('d/m/y h:i:s A') ?? '-',
                'action' => $actionButtons,  // Include the action buttons
            ];
        });

        // Return the response in the format required by DataTables
        return response()->json([
            'draw' => $request->input('draw'),  // DataTable's draw counter (to maintain state)
            'recordsTotal' => $totalRecords,  // Total number of records in the database
            'recordsFiltered' => $filteredRecords,  // Number of records after filtering
            'data' => $data,  // The actual data for the table
        ]);
    }
    public function distributorDetail($id)
    {
        $distributor = User::with('userDetail')->find($id);
        $MasterDistributor = User::where('id',$distributor->userDetail->parent_user_id)->first();
        $MasterDistributorName = $MasterDistributor->name;
        if($distributor) {
            return view('user.distributor.view', compact('distributor','MasterDistributorName'));
        }
        return view('user.distributor.distributor')->with('error','Not found');
    }
    public function distributorEdit($id)
    {
        $distributor = User::with('userDetail')->find($id);
        $MasterDistributors = User::where('role_id',5)->get();
        if($distributor) {
            return view('user.distributor.edit', compact('distributor','MasterDistributors'));
        }
        return view('user.distributor.distributor')->with('error','Not found');
    }
    public function distributorUsers($id){
        $merchants = Merchant::with('user')->where('distributor_id',$id)->get();
        return view('user.distributor.user', [
            'merchants' => $merchants
        ]);
    }
    public function MasterDistributorUsers($id){
        $distributors = UserDetail::with('user')->where('parent_user_id',$id)->get();
        return view('user.masterDistributor.user', [
            'distributors' => $distributors
        ]);
    }
    public function distributorUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $request->id . '|max:255,',
            'mobile' => 'required|unique:users,mobile,'. $request->id,
            'address' => 'required',
            'company_name' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'parent_user_id'=>'required',
        ]);
//        dd($request->all());
        $distributor = User::find($request->id);
        $distributor->name = $request->name;
        $distributor->email = $request->email;
        $distributor->mobile = $request->mobile;
        if($request->password) {
            $distributor->password_text = $request->password;
            $distributor->password = Hash::make($request->password);
        }
        $distributor->update();
        $distributorDetail = UserDetail::where('user_id',$request->id)->first();
        $distributorDetail->company_name = $request->company_name;
        $distributorDetail->city = $request->city;
        $distributorDetail->address = $request->address;
        $distributorDetail->postal_code = $request->postal_code;
        $distributorDetail->update();
        $MasterDistributors = User::where('role_id',5)->get();
        if($distributor) {
            return redirect()->back()->with('success','Distributor data updated successfully');
        }
        return view('user.distributor.distributor')->with('error','Something went wrong');
    }
    public function MasterDistributorDetail($id)
    {
        $masterDistributor = User::with('userDetail')->find($id);

        if($masterDistributor) {
            return view('user.masterDistributor.view', compact('masterDistributor'));
        }
        return view('user.masterDistributor.distributor')->with('error','Not found');
    }
    public function employee()
    {
        $employees = User::where('role_id',6)->get();
        return view('user.employee',compact('employees'));
    }
    public function super_admin()
    {
        $employees = User::where('role_id',7)->get();
        return view('user.super_admin',compact('employees'));
    }

    public function super_admin_destroy($id)
    {
        $sAdmin = User::find($id);
        if ($sAdmin) {
            $sAdmin->delete();
            return redirect()->route('super-admin')->with('success','Super Admin deleted successfully');
        }
        else{
            return redirect()->route('super-admin')->with('error','Super Admin not found');
        }
    }
    public function super_admin_edit($id){
        $superAdmin = User::find($id);
        if($superAdmin) {
            return view('user.super_admin_edit', compact('superAdmin'));
        }
        return redirect()->route('super-admin')->with('error','Super Admin not found');
    }

    public function super_admin_update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id . '|max:255',
            'mobile' => 'required|unique:users,mobile,' . $id,
            'address' => 'required',
            'company_name' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
        ]);

        // Find the distributor by ID
        $distributor = User::find($id);

        if (!$distributor) {
            // Handle case where user is not found
            return redirect()->route('super-admin.edit', ['id' => $id])->with('error', 'Distributor not found');
        }

        // Update the distributor fields
        $distributor->name = $request->name;
        $distributor->email = $request->email;
        $distributor->mobile = $request->mobile;

        // Update password only if it's provided
        if ($request->filled('password')) {
            $distributor->password_text = $request->password;
            $distributor->password = Hash::make($request->password);
        }

        // Save the distributor data
        $distributor->save();  // save is enough, no need to call update() explicitly

        // Update the distributor's details
        $distributorDetail = UserDetail::where('user_id', $id)->first();

        if ($distributorDetail) {
            $distributorDetail->company_name = $request->company_name;
            $distributorDetail->city = $request->city;
            $distributorDetail->address = $request->address;
            $distributorDetail->postal_code = $request->postal_code;

            // Save the distributor detail data
            $distributorDetail->save();  // save is enough, no need to call update() explicitly
        }

        // Redirect back with success message
        return redirect()->route('super-admin')->with('success', 'Super admin updated successfully');
    }
}
