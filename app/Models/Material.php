<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['name','deleted'];

    public function workTypes()
    {
        return $this->belongsToMany(WorkType::class, 'material_work_type')
            ->withPivot('additional_price')
            ->withTimestamps();
    }
}
