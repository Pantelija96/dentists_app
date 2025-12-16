<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = ['name','field_type','options','deleted'];

    protected $casts = ['options'=>'array'];

    public function workTypes()
    {
        return $this->belongsToMany(WorkType::class, 'parameter_work_type')
            ->withPivot('required')
            ->withTimestamps();
    }
}
