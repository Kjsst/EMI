<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TollFreeNumber;
use Illuminate\Support\Facades\Validator;

class TollFreeNumberController extends Controller
{
    public function index()
    {
        $toll_free_number = TollFreeNumber::get();
        return view('tollfreenumber', [
            'tollfreenumbers' => $toll_free_number,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'toll_free_number' => 'required|digits:10',
        ]);

        $toll_free_number = new TollFreeNumber;
        $toll_free_number->toll_free_number = $request->toll_free_number;
        $toll_free_number->save();

        return redirect()->back()->with('success','Troll free number created successfully');
    }

    public function delete($id)
    {
        $toll_free_number = TollFreeNumber::find($id);
        if ($toll_free_number) {
            $toll_free_number->delete();
            return redirect()->route('tollfreenumber')->with('success','Toll free number deleted successfully');
        }
        else{
            return redirect()->route('tollfreenumber')->with('error','Toll free numberr not found');
        }
    }

    public function createTollFreeNumber(Request $request){
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'toll_free_number' => 'required|digits:10',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $toll_free_number = TollFreeNumber::create([
                'toll_free_number'=>$request->toll_free_number
            ]);

        return response()->json(['message' => 'Toll free number Successfully Added.','data'=>$toll_free_number], 200);
    }

    public function tollFreeNumbers(){
        $toll_free_number = TollFreeNumber::all();
        if (count($toll_free_number) > 0) {
            return response()->json(['message' => 'Toll free numbers', 'data' => $toll_free_number], 200);
        }
        return response()->json(['message' => 'Not found'], 422);
    }
}