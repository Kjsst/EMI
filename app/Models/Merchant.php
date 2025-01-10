<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distributor_id',
        'frp_email',
        'bank_name',
        'shop_name',
        'account_name',
        'account_number',
        'ifsc',
        'address',
        'city',
        'postal_code',
        'upi_id',
        'QR_image',
//        'brahmastra_coin',
//        'ranbaan_coin'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function Distributor(){
        return $this->belongsTo(User::class,'distributor_id','id');
    }

    protected $appends = ['QR_image_url'];

    public function getQRImageUrlAttribute()
    {
        return url("storage/images/" . $this->QR_image);
    }
}