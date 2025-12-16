<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['work_order_id','work_type_id','material_id','tooth_number','parameters','price'];
    protected $casts = [
        'parameters'=>'array',
        'price'=>'float',
        'teeth' => 'array',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
