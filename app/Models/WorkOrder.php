<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','status_id','delivery_option_id','name','finished','total_price','draft','locked','i_want_to_deliver', 'deleted'];
    protected $casts = [
        'finished'=>'boolean',
        'draft'=>'boolean',
        'locked'=>'boolean',
        'i_want_to_deliver'=>'boolean',
        'total_price'=>'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(WorkOrderStatus::class, 'status_id');
    }

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class, 'delivery_option_id');
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function files()
    {
        return $this->hasMany(WorkOrderFile::class);
    }

    public function comments()
    {
        return $this->hasMany(WorkOrderComment::class);
    }
}
