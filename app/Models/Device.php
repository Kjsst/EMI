<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'imei1',
        'imei2',
        'model',
        'manufacturer',
        'latitude',
        'longitude',
        'mobile_one',
        'mobile_one_network',
        'mobile_two',
        'mobile_two_network',
        'sync_at',
        'zt_status',
        'action_status',
        'type',
        'status',
        'notification_type',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}