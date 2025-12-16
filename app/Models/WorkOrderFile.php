<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderFile extends Model
{
    use HasFactory;

    protected $fillable = ['work_order_id','file_type','file_path','original_name'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
