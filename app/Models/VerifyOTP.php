<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyOTP extends Model
{
    use HasFactory;
    protected $table = "verify_otp";
    protected $fillable=[
        'type',
        'otp',
    ];
}
