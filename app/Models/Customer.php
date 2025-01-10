<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'merchant_id',
        'alter_mobile',
        'address',
        'imei1',
        'imei2',
//        'device_status',
        'coin_type',
        'aadhar_front',
        'aadhar_back',
        'pan_card',
        'customer_photo',
        'merchant_photo',
        'customer_with_device',
        'bank_name',
        'account_name',
        'account_number',
        'ifsc',
        'blank_cheque',
        'pin',
        'finance_type',
    ];
    protected $appends = [
        'aadhar_front_url',
        'aadhar_back_url',
        'pan_card_url',
        'customer_photo_url',
        'merchant_photo_url',
        'customer_with_device_url',
        'blank_cheque_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function loan()
    {
        return $this->hasOne(Loan::class,'user_id','user_id');
    }
    public function device()
    {
        return $this->hasMany(Device::class,'user_id','user_id');
    }
    public function loanPayment()
    {
        return $this->hasMany(LoanPayment::class,'user_id','user_id');
    }
    public function merchant()
    {
        return $this->belongsTo(User::class,'merchant_id','id');
    }
    public function getAadharFrontUrlAttribute()
    {
        if ($this->aadhar_front)
            return url("storage/images/" . $this->aadhar_front);
        return null;
    }
    public function getAAdharBackUrlAttribute()
    {
        if ($this->aadhar_back)
            return url("storage/images/" . $this->aadhar_back);
        return null;
    }
    public function getPanCardUrlAttribute()
    {
        if ($this->pan_card)
            return url("storage/images/" . $this->pan_card);
        return null;
    }
    public function getCustomerPhotoUrlAttribute()
    {
        if ($this->customer_photo)
            return url("storage/images/" . $this->customer_photo);
        return null;
    }
    public function getMerchantPhotoUrlAttribute()
    {
        if ($this->merchant_photo)
            return url("storage/images/" . $this->merchant_photo);
        return null;
    }
    public function getCustomerWithDeviceUrlAttribute()
    {
        if ($this->customer_with_device)
            return url("storage/images/" . $this->customer_with_device);
        return null;
    }
    public function getBlankChequeUrlAttribute()
    {
        if ($this->blank_cheque)
            return url("storage/images/" . $this->blank_cheque);
        return null;
    }
}
