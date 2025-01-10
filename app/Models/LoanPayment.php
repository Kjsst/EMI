<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;
    protected $fillable=[
        "user_id",
        "loan_id",
        "amount",
        "payment_mode",
        'paid_date',
        'paid_by_user',
        'due_date',
        "status",
        "paid_amount",
        "remarks"
    ];
}
