<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSetting extends Model
{
    use HasFactory;


    protected $guarded = ['id','slug'];


    protected $with = ['admin'];


    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
