<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    use HasFactory;

    protected $fillable = ['name','deleted'];

    public function workOrders(){
        return $this->hasMany(WorkOrder::class, 'delivery_option_id');
    }
}
