<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name','color','lock_work_order'];

    protected $casts = ['translations' => 'array','lock_work_order'=>'boolean'];

    public function workOrders(){
        return $this->hasMany(WorkOrder::class, 'status_id');
    }

    protected $appends = ['translated_name'];

    public function getTranslatedNameAttribute(): string
    {
        return $this->translate('translations', 'name');
    }
}
