<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TollFreeNumber extends Model
{
    use HasFactory;
    protected $fillable=[
        'toll_free_number',
    ];
}
