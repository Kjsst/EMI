<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinOffer extends Model
{
    use HasFactory;
    protected $fillable=[
        'buy_coin',
        'get_coin',
        'coin_type',
    ];
}
