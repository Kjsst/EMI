<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoinOffer;
use Illuminate\Support\Facades\Validator;

class CoinOfferController extends Controller
{
    public function index()
    {
        $coin_offer = CoinOffer::get();
        return view('coinoffer', [
            'coinoffers' => $coin_offer,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'buy_coin' => 'required',
            'get_coin' => 'required',
            'coin_type' => 'required'
        ]);

        $coin_offer = new CoinOffer;
        $coin_offer->buy_coin = $request->buy_coin;
        $coin_offer->get_coin = $request->get_coin;
        $coin_offer->coin_type = $request->coin_type;
        $coin_offer->save();

        return redirect()->back()->with('success','Coin offer created successfully');
    }

    public function delete($id)
    {
        $coin_offer = CoinOffer::find($id);
        if ($coin_offer) {
            $coin_offer->delete();
            return redirect()->route('coinoffer')->with('success','Coin offer deleted successfully');
        }
        else{
            return redirect()->route('coinoffer')->with('error','Coin offer not found');
        }
    }
}