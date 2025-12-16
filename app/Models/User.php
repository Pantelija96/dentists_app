<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'region_id','type','role','first_name','last_name','company_name','pib','email','password',
        'phone','language','address1','address2','country','city','municipality','postal_code',
        'is_approved','number_of_notifications','last_login'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'number_of_notifications' => 'integer',
        'last_login' => 'datetime',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function registrationRequests()
    {
        return $this->hasOne(RegistrationPending::class, 'user_id');
    }
}
