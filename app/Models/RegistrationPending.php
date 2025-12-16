<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationPending extends Model
{
    use HasFactory;

    protected $table = 'registration_pendings';

    protected $fillable = ['user_id','reviewed_by','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function reviewer(){
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatus()
    {
        return $this->status;
    }
}
