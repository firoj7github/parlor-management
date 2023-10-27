<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'message'       => 'object',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    public function scopeAuth($query){
        $query->where('user_id',auth()->user()->id);
    }
}
