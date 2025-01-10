<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable=[
        "billing_amount",
        "file_charge",
        "down_payment",
        "loan_amount",
        "interest",
        "month",
        "monthly_amount",
        "first_emi_date",
        "total_amount",
        "total_interest",
        "user_id",
        "merchant_id",
        "status",
        'lock_type'
    ];
}
