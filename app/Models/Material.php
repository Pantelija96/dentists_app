<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name','deleted'];

    public function workTypes()
    {
        return $this->belongsToMany(WorkType::class, 'material_work_type')
            ->withPivot('additional_price')
            ->withTimestamps();
    }

    protected $casts = [
        'translations' => 'array',
    ];

    protected $appends = ['translated_name'];

    public function getTranslatedNameAttribute(): string
    {
        return $this->translate('translations', 'name');
    }
}


