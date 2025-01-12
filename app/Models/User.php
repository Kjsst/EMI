<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'mobile',
        'username',
        'status',
        'brahmastra_coin',
        'rambaan_coin',
        'password',
        'password_text',
        'mpin',
        'login_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_text',
        'remember_token',
        'created_at',
        'updated_at',
//        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
//        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function merchant(){
        return $this->hasMany(Merchant::class);
    }
    public function customer(){
        return $this->hasMany(Customer::class);
    }
    public function userDetail(){
        return $this->hasOne(UserDetail::class);
    }
}
