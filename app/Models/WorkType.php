<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkType extends Model
{
    use HasFactory;

    protected $fillable = ['name','base_price','deleted'];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_work_type')
            ->withPivot('additional_price')
            ->withTimestamps();
    }

    public function parameters()
    {
        return $this->belongsToMany(Parameter::class, 'parameter_work_type')
            ->withPivot('required')
            ->withTimestamps();
    }

    public function materialParameterRules()
    {
        return $this->hasMany(MaterialParameterRule::class);
    }
}
