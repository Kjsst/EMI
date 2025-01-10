<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferCoins extends Model
{
    use HasFactory;
    protected $fillable=[
        'from_user_id',
        'to_user_id',
        'coin_quantity',
        'coin_type',
        'remarks',
        'type',
        'from_user_brahmastra_coin',
        'from_user_rambaan_coin',
        'to_user_brahmastra_coin',
        'to_user_rambaan_coin',
    ];
    public function fromUser()
    {
        return $this->belongsTo(User::class,'from_user_id','id');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class,'to_user_id','id');
    }
}