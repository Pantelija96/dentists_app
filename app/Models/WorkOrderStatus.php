<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name','color','lock_work_order'];

    protected $casts = ['lock_work_order'=>'boolean'];

    public function workOrders(){
        return $this->hasMany(WorkOrder::class, 'status_id');
    }
}
