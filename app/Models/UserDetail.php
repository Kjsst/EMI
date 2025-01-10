<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'parent_user_id',
        'address',
        'city',
        'company_name',
        'postal_code',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function ParentUser(){
        return $this->belongsTo(User::class,'parent_user_id','id');
    }
}
